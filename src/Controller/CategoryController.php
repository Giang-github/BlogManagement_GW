<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/addCategory', name: 'add_category')]
    public function insertCategory(Request $request, ManagerRegistry $managerRegistry): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('Success', 'Add category succeed !');
            return $this->redirectToRoute('view_category');
        }
        return $this->renderForm(
            'category/add_category.html.twig',
            [
                'categoryForm' => $form
            ]
        );
    }
    #[Route('/viewCategory', name: 'view_category')]
    public function viewCategory(CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = $categoryRepository->findAll();
        return $this->render('category/view_category.html.twig', [
            'categories' => $category,
        ]);
    }
    #[Route('/deleteCategory/{id}', name: 'delete_category')]
    public function deleteCategory($id, CategoryRepository $categoryRepository, ManagerRegistry $managerRegistry)
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = $categoryRepository->find($id);
        if ($category == null) {
            $this->addFlash('Error', 'Post not found !');

        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($category);
            $manager->flush();
            $this->addFlash('Success', 'Post oject has been chanced !');
        }
        return $this->redirectToRoute('view_category');
    }
    #[Route('/editCategory/{id}', name: 'edit_category')]
    public function categoryEdit($id, Request $request, ManagerRegistry $managerRegistry,  CategoryRepository $categoryRepository)
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = $categoryRepository->find($id);
        if ($category == null) {
            $this->addFlash('Error', 'Category not found !');
            return $this->redirectToRoute('view_category');
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('Success', 'Edit succeed !');
            return $this->redirectToRoute('view_category');
        }
        return $this->renderForm('category/edit_category.html.twig', [
                'categoryForm' => $form
        ]);
    }

}