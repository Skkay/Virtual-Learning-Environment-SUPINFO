<?php

namespace App\Controller\AcademicDirector;

use App\Entity\Level;
use App\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic_director/report_card", name="app.academic_director.report_card.")
 * @Security("is_granted('ROLE_ACADEMIC_DIRECTOR')")
 */
class ReportCardController extends AbstractController
{
    /**
     * @Route("/{student}/{level}", name="show")
     */
    public function show(Student $student, Level $level): Response
    {
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

        return $this->render('report_card.html.twig', [ // todo: changer path
            'student' => $student,
            'level' => $level,
            'moduleStartsWith' => $moduleStartsWith,
        ]);
    }
}
