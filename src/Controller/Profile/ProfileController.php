<?php

namespace App\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/me", name="app.profile.")
 * @Security("is_granted('ROLE_USER')")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
