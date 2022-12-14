<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class CourseController extends AbstractController
{
   
    #[Route('/viewCourse', name: 'view_course')]
    public function viewCourse(CourseRepository $courseRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $course = $courseRepository->findAll();
        return $this->render('course/view_course.html.twig', [
            'courses' => $course,
        ]);
    }

    #[Route('/addCourse', name: 'add_course')]
    public function add(Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger)
    {   
         $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $course = new Course;
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('course_image'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                $course->setImage($newFilename);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', 'Add succeed !');
            return $this->redirectToRoute('view_course');
        }
        return $this->renderForm('course/add_course.html.twig', [
                'courseForm' => $form
        ]);
    }

    #[Route('/editCourse/{id}', name: 'edit_course')]
    public function edit($id, Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger, CourseRepository $courseRepository)
    {   
         $this->denyAccessUnlessGranted('ROLE_ADMIN');
         $course = $courseRepository->find($id);
         if ($course == null) {
            $this->addFlash('Error', 'Course not found !');
            return $this->redirectToRoute('view_course');
        }
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('course_image'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                $course->setImage($newFilename);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', 'Edit succeed !');
            return $this->redirectToRoute('view_course');
        }
        return $this->renderForm('course/edit_course.html.twig', [
                'courseForm' => $form
        ]);
    }

    #[Route('/deletecourse/{id}', name: 'delete_course')]
    public function deleteCourse($id, CourseRepository $courseRepository, ManagerRegistry $managerRegistry)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $course = $courseRepository->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Post not found !');

        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash('Success', 'Post oject has been chanced !');
        }
        return $this->redirectToRoute('view_course');
    }
}
