<?php

namespace AG\ShortenerBundle\Controller;

use AG\ShortenerBundle\Entity\Link;
use AG\ShortenerBundle\Form\LinkEditType;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class LinkController extends Controller
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
     * @var Paginator
     */
    private $paginator;

    /**
     * @return array
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function linksAction()
    {
        $linksQuery = $this->em->getRepository('AGShortenerBundle:Link')->findAllQuery($this->getUser());

        return array(
            'links' => $this->paginator->paginate(
                $linksQuery,
                $this->request->query->getInt('page', 1),
                20
            )
        );
    }

    /**
     * @param Link $link
     * @return array
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function detailsAction(Link $link)
    {
        if ($link->getOwner() != $this->getUser() && !$this->getUser()->hasRole('ROLE_ADMIN'))
            throw new AccessDeniedException("Vous n'êtes pas autorisé à voir ce lien.");

        $linkChartData = $this->get('ag_shortener.chart_data');
        $linkChartData->setLink($link);
        $linkChartData->computeData();

        return array(
            'link' => $link,
            'chartData' => $linkChartData
        );
    }

    /**
     * @param Link $link
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Link $link)
    {
        if ($link->getOwner() != $this->getUser() && !$this->getUser()->hasRole('ROLE_ADMIN'))
            throw new AccessDeniedException("Vous n'êtes pas autorisé à editer ce lien.");

        $form = $this->createForm(LinkEditType::class, $link);
        $form->handleRequest($this->request);
        
        if ($form->isValid()) {
            $this->em->persist($link);
            $this->em->flush();
            return $this->redirectToRoute('ag_shortener_links_details', array(
                'id' => $link->getId() 
            ));
        }
        
        return array(
            'link' => $link,
            'form' => $form->createView(),
        );
    }

    /**
     * @param Link $link
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function removeAction(Link $link)
    {
        if ($link->getOwner() != $this->getUser() && !$this->getUser()->hasRole('ROLE_ADMIN'))
            throw new AccessDeniedException("Vous n'êtes pas autorisé à supprimer ce lien.");


        // CSRF token generation
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $this->em->remove($link);
            $this->em->flush();

            return strpos($this->request->get('_route'), 'admin') === false ? $this->redirectToRoute('ag_shortener_my_links') : $this->redirectToRoute('ag_shortener_admin');
        }

        return array(
            'link' => $link,
            'form' => $form->createView()
        );
    }

}