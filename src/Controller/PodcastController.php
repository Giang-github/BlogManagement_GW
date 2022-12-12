<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Form\PodcastType;
use App\Repository\PodcastRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted("ROLE_ADMIN","ROLE_USER")]

class PodcastController extends AbstractController
{
    #[Route('/insertPodcast', name: 'insert_podcast')]
    public function insertPodcast(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $podcast = new Podcast;
        $form = $this->createForm(PodcastType::class, $podcast);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($podcast);
            $manager->flush();
            $this->addFlash('Success', 'Add podcast succeed !');
            return $this->redirectToRoute('view_podcast');
        }
        return $this->renderForm(
            'podcast/insert_podcast.html.twig',
            [
                'podcastForm' => $form
            ]
        );
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
}
