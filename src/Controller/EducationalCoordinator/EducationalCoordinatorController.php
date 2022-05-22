<?php

namespace App\Controller\EducationalCoordinator;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/educational_coordinator", name="app.educational_coordinator.")
 * @Security("is_granted('ROLE_EDUCATIONAL_COORDINATOR')")
 */
class EducationalCoordinatorController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('educational_coordinator/index.html.twig');
    }
}
