<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository ): Response
    {
		$this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $userRepository->viewUser();
		/** @var User $user */
		$user = $this->getUser();

		return match ($user->isVerified()) {
			true => $this->render("admin/index.html.twig", [
				'users' => $user,
			]),
			false => $this->render("admin/please-verify-email.html.twig"),
		};

    }
}
