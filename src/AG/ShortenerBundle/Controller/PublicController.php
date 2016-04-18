<?php

namespace AG\ShortenerBundle\Controller;

use AG\ShortenerBundle\Entity\Click;
use AG\ShortenerBundle\Entity\Link;
use AG\ShortenerBundle\Form\LinkType;
use DeviceDetector\DeviceDetector;
use Doctrine\ORM\EntityManager;
use Snowplow\RefererParser\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PublicController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function indexAction()
    {
        $link = new Link;
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($this->request);

        // Get 10 last links of logged in user if there's one
        $links = null;
        if (null !== $user = $this->getUser()) {
            $links = $this->em->getRepository('AGShortenerBundle:Link')->findBy(array(
                'owner' => $user
            ), array(
                'createdAt' => 'DESC'
            ), 10);
        }

        if ($form->isValid()) {
            do {
                //Generate random token of 6 characters
                $token = bin2hex(openssl_random_pseudo_bytes(3));
            } while(null !== $this->em->getRepository('AGShortenerBundle:Link')->findOneBy(array('token' => $token)));

            $link->setToken($token);
            $this->em->persist($link);
            $this->em->flush();

            return array(
                'form' => $form->createView(),
                'link' => $link,
                'links' => $links,
            );
        }

        return array(
            'form' => $form->createView(),
            'links' => $links,
        );
    }

    /**
     * @return array
     * @Template
     */
    public function findAction()
    {
        $form = $this->createFormBuilder()
            ->add('token', TextType::class, array(
                'attr' => array(
                    'autofocus' => true
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Rechercher',
            ))
            ->getForm();
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $data = $form->getData();
            $token = $data['token'];
            $link = $this->em->getRepository('AGShortenerBundle:Link')->findOneBy(array(
                'token' => $token
            ));
            
            return array(
                'link' => $link,
                'form' => $form->createView(),
            );
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * Redirect to url
     */
    public function clickRedirectAction($token)
    {
        $link = $this->em->getRepository('AGShortenerBundle:Link')->findOneBy(array(
            'token' => $token
        ));

        if (null == $link) {
            $this->addFlash('error', 'Url not found');
            $this->redirectToRoute('ag_shortener_homepage');
        }

        $click = new Click;

        // Link for which click has happened
        $click->setLink($link);

        // IP of client who clicked on link (maybe useless because internal IP ?)
        $click->setIp($this->request->getClientIp());

        // Browser name
        $dd = new DeviceDetector($this->request->headers->get('User-Agent'));
        $dd->skipBotDetection();
        $dd->parse();
        $click->setBrowser($dd->getClient('name'));

        // Country where client is located
        $geoip = $this->get('maxmind.geoip')->lookup($click->getIp());
        if ($geoip) {
            $click->setCountry($geoip->getCountryName());
        }

        // Referer from which client comes
        $parser = new Parser();
        $referer = $parser->parse($this->request->headers->get('referer'));
        if ($referer->isKnown()) {
            $click->setReferer($referer->getSource());
        }

        $this->em->persist($click);
        $this->em->flush();

        return $this->redirect($link->getUrl());
    }
}
