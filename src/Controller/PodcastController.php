<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Podcast;
use App\Entity\User;
use App\Form\PodcastType;
use App\Repository\BlogRepository;
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

class PodcastController extends AbstractController
{
    #[Route('/addPodcast', name: 'add_podcast')]
    public function addPodcast(Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $podcast = new Podcast;
        $form = $this->createForm(PodcastType::class, $podcast);
        $form->handleRequest($request);
           //kiểm tra xem người dùng có muốn upload ảnh mới hay không
            //nếu có thì thực hiện code upload ảnh
            //nếu không thì bỏ qua
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                         //B1: lấy ra ảnh vừa upload

                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('podcast_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                         //B6: set dữ liệu của image vào object book

                $podcast->setImage($newFilename);
            }
            $brochureFile1 = $form->get('audio')->getData();
            if ($brochureFile1) {
                $originalFilename1 = pathinfo($brochureFile1->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename1 = $slugger->slug($originalFilename1);
                $newFilename1 = $safeFilename1 . '-' . uniqid() . '.' . $brochureFile1->guessExtension();
                try {
                    $brochureFile1->move(
                        $this->getParameter('podcast_audio'),
                        $newFilename1
                    );
                } catch (FileException $e) {
                }
                $podcast->setAudio($newFilename1);
            }
                     //lưu dữ liệu của book vào DB
        //dùng Manager để lưu object vào DB

            $manager = $managerRegistry->getManager();
            $manager->persist($podcast);
            $manager->flush();
                    //gửi thông báo về view bằng addFlash

            $this->addFlash('Success', 'Add succeed !');
                  //redirect về trang book store

            return $this->redirectToRoute('view_podcast');
        }
        return $this->renderForm('podcast/add_podcast.html.twig', [
            'podcastForm' => $form
        ]);
    }

    #[Route('/editPodcast/{id}', name: 'edit_podcast')]
    public function blogEdit($id, Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger, PodcastRepository $podcastRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $podcast = $podcastRepository->find($id);
        if ($podcast == null) {
            $this->addFlash('Error', 'Podcast not found !');
            return $this->redirectToRoute('view_podcast');
        }
        $form = $this->createForm(PodcastType::class, $podcast);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('podcast_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $podcast->setImage($newFilename);
            }
            $brochureFile1 = $form->get('audio')->getData();
            if ($brochureFile1) {
                $originalFilename1 = pathinfo($brochureFile1->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename1 = $slugger->slug($originalFilename1);
                $newFilename1 = $safeFilename1 . '-' . uniqid() . '.' . $brochureFile1->guessExtension();
                try {
                    $brochureFile1->move(
                        $this->getParameter('podcast_audio'),
                        $newFilename1
                    );
                } catch (FileException $e) {
                }
                $podcast->setAudio($newFilename1);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($podcast);
            $manager->flush();
            $this->addFlash('Success', 'Edit succeed !');
            return $this->redirectToRoute('view_podcast');
        }
        return $this->renderForm('podcast/edit_podcast.html.twig', [
            'podcastForm' => $form
        ]);
    }

    #[Route('/viewPodcast', name: 'view_podcast')]
    public function viewPodcast(PodcastRepository $podcastRepository): Response
    {
        $podcast = $podcastRepository->findAll();
        return $this->render('podcast/view_podcast.html.twig', [
            'podcasts' => $podcast,
        ]);
    }
    #[Route('/deletepodcast/{id}', name: 'podcast_delete')]
    public function deletePodcast($id, PodcastRepository $podcastRepository, ManagerRegistry $managerRegistry)
    {
        $podcast = $podcastRepository->find($id);
        if ($podcast == null) {
            $this->addFlash('Error', 'Post not found !');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($podcast);
            $manager->flush();
            $this->addFlash('Success', 'Post oject has been chanced !');
        }
        return $this->redirectToRoute('view_podcast');
    }

    #[Route('/searchpodcastadmin', name: 'search_podcastadmin')]
    public function searchPodcast(Request $request, PodcastRepository $podcastRepository)
    {
        $title = $request->get('keyword');
        $podcast = $podcastRepository->searchPodcast($title);
        if ($podcast == null) {
            $this->addFlash('Error', 'Podcast not found !');
        }
        else{
            $this->addFlash('Success', 'Podcast oject has been chanced !');
        }
        return $this->render(
            'podcast/view_podcast.html.twig',
            [
                'podcasts' => $podcast
            ]
        );
    }
   
}
