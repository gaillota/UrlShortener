<?php

namespace AG\ShortenerBundle\Controller;

use AG\ShortenerBundle\Entity\Link;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
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
     */
    public function indexAction()
    {
        $linkList = $this->em->getRepository('AGShortenerBundle:Link')->findAllAdminQuery();

        return array(
            'linkList' => $this->paginator->paginate(
                $linkList,
                $this->request->query->getInt('page', 1),
                20
            )
        );
    }

    /**
     * @param Link $link
     * @return array
     * @Template
     */
    public function detailsAction(Link $link)
    {
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
     */
    public function removeAction(Link $link)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->em->remove($link);
            $this->em->flush();

            return $this->redirectToRoute('ag_shortener_admin');
        }

        return array(
            'link' => $link,
            'form' => $form->createView()
        );
    }
}