<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Story;
use App\Entity\UserLesson;
use App\Form\LessonType;
use App\Form\StoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MemberController extends AbstractController
{
    #[Route('/member', name: 'app_member')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $stories = $em->getRepository(Story::class)->findBy(['user' => $user]);

        return $this->render('member/index.html.twig', [
            'stories' => $stories,
        ]);
    }

    #[Route('/member/insert', name: 'app_member_insert')]
    public function addStory(EntityManagerInterface $em, Request $request): Response
    {
        $message = 'Verhaal toevoegen';
        $user = $this->getUser();
        $story = new Story();

        $form = $this->createForm(StoryType::class, $story);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $story->setUser($user);

            $em->persist($story);
            $em->flush();

            $this->addFlash('success', 'Verhaal succesvol toegevoegd');

            return $this->redirectToRoute('app_member');
        }

        return $this->render('forms/handleForms.html.twig', [
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/member/update/{id}', name: 'app_member_update')]
    public function updateStory(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $message = 'Verhaal aanpassen';
        $story = $em->getRepository(Story::class)->find($id);

        $form = $this->createForm(StoryType::class, $story);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($story);
            $em->flush();

            $this->addFlash('success', 'Verhaal succesvol aangepast');

            return $this->redirectToRoute('app_member');
        }

        return $this->render('forms/handleForms.html.twig', [
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/member/lessons', name: 'app_member_lessons')]
    public function showLessons(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $lessons = $em->getRepository(UserLesson::class)->findBy(['student' => $user]);

        return $this->render('member/lessons.html.twig', [
            'lessons' => $lessons,
        ]);
    }

    #[Route('/member/lessons/insert', name: 'app_member_lesson_insert')]
    public function addLessons(EntityManagerInterface $em): Response
    {
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);

        return $this->render('member/lessons.html.twig', [
            'lessons' => $lesson,
        ]);
    }
}
