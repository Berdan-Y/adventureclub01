<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\UserLesson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $lessons = $em->getRepository(UserLesson::class)->findBy(['teacher' => $user]);

        return $this->render('employee/index.html.twig', [
            'lessons' => $lessons,
        ]);
    }
}
