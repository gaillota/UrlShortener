<?php

namespace AG\ShortenerBundle\Controller;

use Doctrine\ORM\EntityManager;
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
     * @return array
     * @Template
     */
    public function indexAction()
    {
        $linkList = $this->em->getRepository('AGShortenerBundle:Link')->findAll();

        return array(
            'linkList' => $linkList
        );
    }
}