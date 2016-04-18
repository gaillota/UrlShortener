<?php

namespace AG\UserBundle\Controller;

use AG\UserBundle\Form\ColorEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CustomController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @return array
     * @Template
     */
    public function editColorsAction()
    {
        $form = $this->createForm(ColorEditType::class, $this->getUser());
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $this->get('fos_user.user_manager')->updateUser($this->getUser());
        }

        return array(
            'form' => $form->createView()
        );
    }

}
