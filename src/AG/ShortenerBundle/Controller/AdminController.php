<?php

namespace AG\ShortenerBundle\Controller;

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
}