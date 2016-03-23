<?php

namespace AG\ShortenerBundle\Controller;

use AG\ShortenerBundle\ChartData\LinkChartData;
use AG\ShortenerBundle\Entity\Link;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController extends Controller
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
    public function myLinksAction()
    {
        $user = $this->getUser();
        $linksQuery = $this->em->getRepository('AGShortenerBundle:Link')->findAllQuery($user->getId());

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
        if ($link->getOwner() != $this->getUser())
            throw new AccessDeniedException("Vous n'êtes pas autorisé à supprimer ce lien.");

        $linkChartData = $this->get('ag_shortener.chart_data');
        $linkChartData->setLink($link);
        $linkChartData->setClicks();
        $linkChartData->setScans();
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
    public function removeAction(Link $link)
    {
        if ($link->getOwner() != $this->getUser())
            throw new AccessDeniedException("Vous n'êtes pas autorisé à supprimer ce lien.");

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->em->remove($link);
            $this->em->flush();

            return $this->redirectToRoute('ag_shortener_my_links');
        }

        return array(
            'link' => $link,
            'form' => $form->createView()
        );
    }

}