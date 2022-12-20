<?php

namespace App\Controller;
use App\Form\PostType;
use App\Entity\Blog;
use App\Entity\User;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\PodcastRepository;
use App\Repository\UserRepository;
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
    #[Route('/blog', name: 'blog')]
    public function blog(UserRepository $userRepository, BlogRepository $blogRepository,CategoryRepository $categoryRepository): Response
    {
        $blog = $blogRepository->findAll();
        $user = $userRepository->findAll();      
        // $admin = $userRepository->findAll();
        $category = $categoryRepository->findAll();
       return $this->render('WebUser/blog.html.twig', [
            'blogs' => $blog,
            'categories' => $category,
            'users' => $user,
            // 'admins' => $admin
       ]);
    }

    #[Route('/blog_detail/{id}', name: 'blog_detail')]
    public function blog_detail($id, BlogRepository $blogRepository, CategoryRepository $categoryRepository): Response
    {
        $blog = $blogRepository->find($id);
        $category = $categoryRepository->findAll();
        if ($blog == null) {
            $this->addFlash('Error', 'Invalid Blog ID !');
            return $this->redirectToRoute('WebUser/blog.html.twig');
        }
        return $this->render('WebUser/blog_detail.html.twig',
            [
                'blog' => $blog,
                'categories' => $category
            ]);
    }
    #[Route('/searchblogwebuser', name: 'search_bloguser')]
    public function searchBlog(Request $request, BlogRepository $blogRepository)
    {
        $title = $request->get('keyword');
        $blog = $blogRepository->searchBlog($title);
        if ($blog == null) {
            $this->addFlash('Error', 'Blog not found !');
        }
        else{
            $this->addFlash('Success', 'Blog oject has been chanced !');
        }
        return $this->render(
            'WebUser/blog.html.twig',
            [
                'blogs' => $blog
            ]
        );
    }

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
            return $this->redirectToRoute('WebUser/podcast.html.twig');
        }
        return $this->render('WebUser/podcast_detail.html.twig',
            [
                'podcast' => $podcast,
                'categories' => $category
            ]);
    }
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
            return $this->redirectToRoute('WebUser/course.html.twig');
        }
        return $this->render('WebUser/course_detail.html.twig',
            [
                'course' => $course,
                'categories' => $category
            ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();

        return $this->render('WebUser/profile.html.twig',
        [
            'categories' => $category
        ]);
    }

    #[Route('/public_profile', name: 'public_profile')]
    public function public_profile(CategoryRepository $categoryRepository): Response

    {
        $category = $categoryRepository->findAll();

        return $this->render('WebUser/public_profile.html.twig',
        [
            'categories' => $category
        ]);
    }
}
