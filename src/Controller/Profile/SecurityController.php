<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\SetPasswordType;
use App\Form\UpdatePasswordType;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/me/security", name="app.profile.security.")
 * @Security("is_granted('ROLE_USER')")
 */
class SecurityController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/set_password", name="set_password")
     */
    public function setPassword(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        if ($user->getPassword() !== null) {
            $this->addFlash('password_set', ['type' => 'alert-danger', 'message' => 'profile.security.misc.password_already_defined']);

            return $this->redirectToRoute('app.profile.index');
        }

        $form = $this->createForm(SetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->userService->setPassword($user, $data['password']);

            $this->addFlash('password_set', ['type' => 'alert-success', 'message' => 'profile.security.misc.password_set']);

            return $this->redirectToRoute('app.profile.index');
        }

        return $this->renderForm('profile/security/set_password.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/update_password", name="update_password")
     */
    public function updatePassword(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        if ($user->getPassword() === null) {
            $this->addFlash('password_set', ['type' => 'alert-danger', 'message' => 'profile.security.misc.password_not_defined']);

            return $this->redirectToRoute('app.profile.index');
        }

        $form = $this->createForm(UpdatePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->userService->setPassword($user, $data['password']);

            $this->addFlash('password_set', ['type' => 'alert-success', 'message' => 'profile.security.misc.password_updated']);

            return $this->redirectToRoute('app.profile.index');
        }

        return $this->renderForm('profile/security/update_password.html.twig', [
            'form' => $form,
        ]);
    }
}
