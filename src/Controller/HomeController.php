<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app.home")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app.about');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app.admin.index');
        }

        if ($this->isGranted('ROLE_ACADEMIC_DIRECTOR')) {
            return $this->redirectToRoute('app.academic_director.index');
        }

        if ($this->isGranted('ROLE_EDUCATIONAL_COORDINATOR')) {
            return $this->redirectToRoute('app.educational_coordinator.index');
        }

        if ($this->isGranted('ROLE_ACCOUNTS')) {
            return $this->redirectToRoute('app.accounts.index');
        }

        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app.dashboard.index');
        }
    }

    /**
     * @Route("/about", name="app.about")
     */
    public function about(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
