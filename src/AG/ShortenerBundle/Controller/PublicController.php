<?php

namespace AG\ShortenerBundle\Controller;

use AG\ShortenerBundle\Entity\Click;
use AG\ShortenerBundle\Entity\Link;
use AG\ShortenerBundle\Entity\Scan;
use AG\ShortenerBundle\Form\LinkType;
use AG\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

        if ($form->isValid()) {
            //Generate random password of 6 characters
            $token = bin2hex(openssl_random_pseudo_bytes(3));

            $link->setToken($token);
            $this->em->persist($link);
            $this->em->flush();

            return array(
                'form' => $form->createView(),
                'link' => $link
            );
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param $token
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
        $click->setLink($link);
        $this->em->persist($click);
        $this->em->flush();

        return $this->redirect($link->getUrl());
    }

    /**
     * @param $token
     */
    public function scanRedirectAction($token)
    {
        $link = $this->em->getRepository('AGShortenerBundle:Link')->findOneBy(array(
            'token' => $token
        ));

        if (null == $link) {
            return $this->createNotFoundException('URL not found');
        }

        $click = new Scan;
        $click->setLink($link);
        $this->em->persist($click);
        $this->em->flush();

        return $this->redirect($link->getUrl());
    }
}
