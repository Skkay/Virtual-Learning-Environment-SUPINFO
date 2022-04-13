<?php

namespace App\Controller\Accounts;

use App\Entity\AccountsStudentComment;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accounts_comment", name="app.accounts_student_comment.")
 * @Security("is_granted('ROLE_ADMINISTRATION')")
 */
class AccountsStudentCommentController extends AbstractController
{
    private $em;
    private $serializer;

    public function __construct(ManagerRegistry $doctrine, SerializerInterface $serializer)
    {
        $this->em = $doctrine->getManager();
        $this->serializer = $serializer;
    }

    /**
     * @Route("/update/{id}", methods={"POST"})
     * @ParamConverter("post", class="array", converter="fos_rest.request_body")
     */
    public function index(array $post, AccountsStudentComment $accountsStudentComment): Response
    {
        if (!isset($post['comment'])) {
            throw new BadRequestHttpException('Missing "comment" field');
        }

        $accountsStudentComment->setComment($post['comment']);
        $this->em->persist($accountsStudentComment);
        $this->em->flush();

        return $this->json($this->serializer->serialize($accountsStudentComment, 'json'));
    }
}
