<?php

namespace App\Controller;
use App\Form\PostType;

use App\Entity\Blog;
use App\Entity\User;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

use App\Repository\CourseRepository;
use App\Repository\PodcastRepository;
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
// #[IsGranted("ROLE_USER")]
class WebController extends AbstractController
{
    #[Route('/homepage', name: 'homepage')]
    public function homepage(CategoryRepository $categoryRepository): Response
    {   
		$this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
     $category = $categoryRepository->findAll();

		/** @var User $user */
		$user = $this->getUser();
		return match ($user->isVerified()) {
			true => $this->render("WebUser/homepage.html.twig",
            [
                'categories' => $category,
            ]),
			false => $this->render("WebUser/please-verify-email.html.twig"),
           
		};
    }
     
    #[Route('/signup', name: 'signup')]
    public function signup(): Response
    {
        return $this->render('WebUser/signup.html.twig');
    }
     
    // #[Route('/homepage', name: 'homepage')]
    // public function homepage(): Response
    // {
    //     return $this->render('WebUser/homepage.html.twig');
    // }
    #[Route('/blog', name: 'blog')]
    public function blog(BlogRepository $blogRepository,CategoryRepository $categoryRepository): Response
    {
        $post = $blogRepository->findAll();
        $category = $categoryRepository->findAll();
       return $this->render('WebUser/blog.html.twig', [
            'posts' => $post,
            'categories' => $category
            
       ]);
    }

    #[Route('/blog_detail/{id}', name: 'blog_detail')]
    public function blog_detail($id, BlogRepository $blogRepository, CategoryRepository $categoryRepository): Response
    {
        $blog = $blogRepository->find($id);
        $category = $categoryRepository->findAll();
        if ($blog == null) {
            $this->addFlash('Error', 'Invalid Blog ID !');
            return $this->redirectToRoute('view_post');
        }
        return $this->render('WebUser/blog_detail.html.twig',
            [
                'blog' => $blog,
                'categories' => $category
            ]);
    }


    #[Route('/category_detail/{id}', name: 'category_detail')]
    public function category_detail($id, BlogRepository $blogRepository, CategoryRepository $categoryRepository): Response
    {
        $post = $blogRepository->findAll();
        $category = $categoryRepository->findAll();
        $category_id = $categoryRepository->find($id);

        if ($category_id == null) {
            $this->addFlash('Error', 'Invalid Blog ID !');
            return $this->redirectToRoute('view_post');
        }
          return $this->render('WebUser/category_detail.html.twig', [
              'categories' => $category,
            'posts' => $post,
              'category' => $category_id
         ]);

    }
    // #[Route('/blog_title', name: 'blog_title')]
    // public function blog_title(CategoryRepository $categoryRepository): Response
    // {
        
    //     $category = $categoryRepository->findAll();
    //    return $this->render('WebUser/blog_title.html.twig', [
    //         'categories' => $category,
    //    ]);
    // }

    #[Route('/podcast', name: 'podcast')]
    public function podcast( PodcastRepository $podcastRepository,  CategoryRepository $categoryRepository): Response
    {

        $podcast = $podcastRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('WebUser/podcast.html.twig', [
            'podcasts' => $podcast,
            'categories' => $category
        ]);
    }


    #[Route('/podcast_detail/{id}', name: 'podcast_detail')]
    public function podcast_detail($id, PodcastRepository $podcastRepository,  CategoryRepository $categoryRepository): Response
    {
        $podcast = $podcastRepository->find($id);
        $category = $categoryRepository->findAll();
        if ($podcast == null) {
            $this->addFlash('Error', 'Invalid Blog ID !');
            return $this->redirectToRoute('view_post');
        }
        return $this->render('WebUser/podcast_detail.html.twig',
            [
                'podcast' => $podcast,
                'categories' => $category
            ]);
    }
    // #[Route('/podcast_detail_byCourse/{id}', name: 'podcast_detail_byCourse')]
    // public function podcast_detail_byCourse($id,CourseRepository $courseRepository, PodcastRepository $podcastRepository,  CategoryRepository $categoryRepository): Response
    // {
    //     $podcast = $podcastRepository->find($id);
    //     $category = $categoryRepository->findAll();
    //     $course = $courseRepository->find($id);
    //     if ($podcast == null) {
    //         $this->addFlash('Error', 'Invalid Blog ID !');
    //         return $this->redirectToRoute('course_detail');
    //     }
    //     return $this->render('WebUser/podcast_detail_byCourse.html.twig',
    //         [   
    //             'course' => $course,
    //             'podcast' => $podcast,
    //             'categories' => $category
    //         ]);
    // }
    #[Route('/courses', name: 'courses')]
    public function courses(CourseRepository $courseRepository,  CategoryRepository $categoryRepository): Response
    {
        $course = $courseRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('WebUser/courses.html.twig', [
            'courses' => $course,
            'categories' => $category
        ]);
    }  


    #[Route('/course_detail/{id}', name: 'course_detail')]
    public function course_detail($id, CourseRepository $courseRepository, CategoryRepository $categoryRepository): Response
    {
        $course = $courseRepository->find($id);
        $category = $categoryRepository->findAll();
        if ($course == null) {
            $this->addFlash('Error', 'Invalid Blog ID !');
            return $this->redirectToRoute('view_post');
        }
        return $this->render('WebUser/course_detail.html.twig',
            [
                'course' => $course,
                'categories' => $category
            ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('WebUser/profile.html.twig');
    }


    #[Route('/public_profile', name: 'public_profile')]
    public function public_profile(): Response

    {
        return $this->render('WebUser/public_profile.html.twig');
    }
}
