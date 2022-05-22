<?php

namespace App\Controller\AcademicDirector;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director", name="app.academic_director.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class AcademicDirectorController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('academic_director/index.html.twig');
    }
}
