<?php
namespace App\Controller;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class UserController extends AbstractController
{   
    #[Route('/viewUser', name: 'view_user')]
    public function viewUser( UserRepository $userRepository  ): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $userRepository->viewUser();
        return $this->render('user/view_user.html.twig', [
             'users' => $user,
        ]);
    }

    #[Route('/viewAdmin', name: 'view_admin')]
    public function viewAdmin( UserRepository $userRepository  ): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $admin = $userRepository->viewAdmin();
        return $this->render('user/view_admin.html.twig', [
             'admins' => $admin,
        ]);
    }

    #[Route('/delete/{id}', name: 'user_delete')]
    public function deleteUser($id, UserRepository $userRepository, ManagerRegistry $managerRegistry)
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $userRepository->find($id);
        if ($user == null) {
            $this->addFlash('Error', 'User not found !');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($user);
            $manager->flush();
            $this->addFlash('Success', 'User oject has been chanced !');
        }
        return $this->redirectToRoute('view_user');
    }

}
