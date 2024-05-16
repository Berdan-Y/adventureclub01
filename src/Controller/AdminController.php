<?php

namespace App\Controller;

use App\Entity\Story;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $em): Response
    {
        $stories = $em->getRepository(Story::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'stories' => $stories,
        ]);
    }

    #[Route('/admin/medewerkers', name: 'app_admin_employees')]
    public function showEmployees(EntityManagerInterface $em, Request $request): Response
    {
        $employees = $em->getRepository(User::class)->findBy(['roles' => array('["ROLE_DOCENT"]')]);

        return $this->render('admin/employees.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/admin/medewerkers/toevoegen', name: 'app_admin_add_employees')]
    public function addEmployees(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $message = 'Docent toevoegen';
        $employee = new User();

        $form = $this->createForm(UserType::class, $employee);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $employee->setRoles(['ROLE_DOCENT']);

            $employee->setPassword(
                $userPasswordHasher->hashPassword(
                    $employee,
                    $form->get('password')->getData()
                )
            );

            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'Docent succesvol toegevoegd');

            return $this->redirectToRoute('app_admin_employees');
        }

        return $this->render('forms/handleForms.html.twig', [
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/admin/medewerkers/delete/{id}', name: 'app_admin_delete_employees')]
    public function deleteEmployees(EntityManagerInterface $em, int $id): Response
    {
        $employee = $em->getRepository(User::class)->find($id);

        $em->remove($employee);
        $em->flush();

        $this->addFlash('success', 'Succesvol medewerker verwijderd');

        return $this->redirectToRoute('app_admin_employees');
    }

    #[Route('/admin/delete/{id}', name: 'app_admin_delete')]
    public function deleteStory(EntityManagerInterface $em, int $id): Response
    {
        $story = $em->getRepository(Story::class)->find($id);

        $em->remove($story);
        $em->flush();

        $this->addFlash('success', 'Verhaal succesvol verwijderd');

        return $this->redirectToRoute('app_admin');
    }
}
