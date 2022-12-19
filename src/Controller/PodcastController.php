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
            $manager = $managerRegistry->getManager();
            $manager->persist($podcast);
            $manager->flush();

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
                $podcast->setImage($newFilename1);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($podcast);
            $manager->flush();
            $this->addFlash('Success', 'Add succeed !');
            return $this->redirectToRoute('view_podcast');
        }
        return $this->renderForm('podcast/add_podcast.html.twig', [
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
}
