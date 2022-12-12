<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\BlogRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[IsGranted("ROLE_ADMIN","ROLE_USER")]

class BlogController extends AbstractController
{
    
    #[Route('/editPost/{id}', name: 'edit_post')]
    public function blogEdit($id, Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger, BlogRepository $blogRepository)
    {
        $post = $blogRepository->find($id);
        if ($post == null) {
            $this->addFlash('Error', 'Post not found !');
            return $this->redirectToRoute('view_post');
        }
        $form = $this->createForm(PostType::class, $post);
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
                        $this->getParameter('post_image'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                $post->setImage($newFilename);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($post);
            $manager->flush();
            $this->addFlash('Success', 'Edit succeed !');
            return $this->redirectToRoute('view_post');
        }
        return $this->renderForm('blog/modify_blog.html.twig', [
                'blogForm' => $form
        ]);
    }

    #[Route('/addPost', name: 'insert_post')]
    public function add(Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger)
    {
        $post = new Blog;
        $form = $this->createForm(PostType::class, $post);
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
                        $this->getParameter('post_image'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                $post->setImage($newFilename);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($post);
            $manager->flush();
            $this->addFlash('Success', 'Add succeed !');
            return $this->redirectToRoute('view_post');
        }
        return $this->renderForm('blog/insert_blog.html.twig', [
                'blogForm' => $form
        ]);
    }

    #[Route('/viewPost', name: 'view_post')]
    public function ViewPost( BlogRepository $blogRepository  ): Response
    {
         $post = $blogRepository->findAll();
        return $this->render('blog/view_blog.html.twig', [
             'posts' => $post,
        ]);
    }


    #[Route('/delete/{id}', name: 'post_delete')]
    public function deletePost($id, BlogRepository $blogRepository, ManagerRegistry $managerRegistry)
    {
        $post = $blogRepository->find($id);
        if ($post == null) {
            $this->addFlash('Error', 'Post not found !');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($post);
            $manager->flush();
            $this->addFlash('Success', 'Post oject has been chanced !');
        }
        return $this->redirectToRoute('view_post');
    }


    #[Route('/asc', name: 'sort_post_name_asc')]
    public function sortNameAsc(blogRepository $blogRepository)
    {
        $post = $blogRepository->sortBlogNameAsc();
        return $this->render(
            'blog/view_blog.html.twig',
            [
                'posts' => $post
            ]
        );
    }


    #[Route('/desc', name: 'sort_post_name_desc')]
    public function sortNameDesc(BlogRepository $blogRepository)
    {
        $post = $blogRepository->sortpostNameDesc();
        return $this->render(
            'blog/view_blog.html.twig',
            [
                'posts' => $post
            ]
        );
    }

    #[Route('/search', name: 'search_post')]
    public function searchPost(Request $request, BlogRepository $blogRepository)
    {
        $title = $request->get('keyword');
        $post = $blogRepository->searchPost($title);
        if ($post == null) {
            $this->addFlash('Error', 'Post not found !');
        }
        else{
            $this->addFlash('Success', 'Post oject has been chanced !');
        }
        return $this->render(
            'blog/view_blog.html.twig',
            [
                'posts' => $post
            ]
        );
    }
    #[Route('/detailBlog/{id}', name: 'blog_detail_admin')]
  public function blogDetail ($id, BlogRepository $blogRepository) {
    $blog = $blogRepository->find($id);
    if ($blog == null) {
        $this->addFlash('Error', 'Invalid Blog ID !');
        return $this->redirectToRoute('view_post');
    }
    return $this->render('blog/detailBlog.html.twig',
        [
            'blog' => $blog
        ]);
  }


}
