<?php

namespace App\Controller\Student;

use App\Entity\Level;
use App\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/report_card", name="app.report_card.")
 * @Security("is_granted('ROLE_USER')")
 */
class ReportCardController extends AbstractController
{
    /**
     * @Route("/{level}", name="show")
     */
    public function show(Level $level)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $student = $user->getStudent();

        if ($student === null) {
            throw new NotFoundHttpException('Current logged user is not a student');
        }

        switch ($level->getLabel()) {
            case 'B.ENG 1':
                $moduleStartsWith = '1'; break;
            case 'B.ENG 2':
                $moduleStartsWith = '2'; break;
            case 'B.ENG 3':
                $moduleStartsWith = '3'; break;
            case 'M.ENG 1':
                $moduleStartsWith = '4'; break;
            case 'M.ENG 2':
                $moduleStartsWith = '5'; break;
            default:
                throw new \Exception(); break;
        }

        return $this->render('report_card/show.html.twig', [
            'student' => $student,
            'level' => $level,
            'moduleStartsWith' => $moduleStartsWith,
        ]);
    }
}
