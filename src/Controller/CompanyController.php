<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/companies')]
final class CompanyController extends AbstractController
{
    #[Route('', name: 'app_company_index')]
    public function index(CompanyRepository $companyRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findByOwner($user),
        ]);
    }

    #[Route('/new', name: 'app_company_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $company = new Company();
        $company->setOwner($user);

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('app_company_index');
        }

        return $this->render('company/form.html.twig', [
            'form' => $form,
            'title' => 'Nova empresa',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_edit', requirements: ['id' => '\d+'])]
    public function edit(Company $company, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($company->getOwner()?->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_company_index');
        }

        return $this->render('company/form.html.twig', [
            'form' => $form,
            'title' => 'Editar empresa',
        ]);
    }
}
