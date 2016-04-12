<?php

namespace AG\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AG\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @return array
     */
    public function editColorsAction()
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $this->get('fos_user.user_manager')->updateUser($this->getUser());
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return array(
            'form' => $form->createView()
        );
    }

}
