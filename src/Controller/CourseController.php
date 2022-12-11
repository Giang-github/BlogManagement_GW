<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseController extends AbstractController
{
    #[Route('/insertCourse', name: 'insert_course')]
    public function insertCourse(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $course = new Course;
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('Success', 'Add course succeed !');
            return $this->redirectToRoute('view_course');
        }
        return $this->renderForm(
            'course/insert_course.html.twig',
            [
                'courseForm' => $form
            ]
        );
    }
    #[Route('/viewCourse', name: 'view_course')]
    public function viewCourse(CourseRepository $courseRepository): Response
    {
        $course = $courseRepository->findAll();
        return $this->render('course/view_course.html.twig', [
            'courses' => $course,
        ]);
    }
    #[Route('/deletecourse/{id}', name: 'course_delete')]
    public function deleteCourse($id, CourseRepository $courseRepository, ManagerRegistry $managerRegistry)
    {
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
