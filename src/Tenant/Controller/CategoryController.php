<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\Category;
use Tenant\Form\CategoryType;
use Tenant\Repository\CategoryRepository;
use Tenant\Services\ValidateParams;

class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidateParams $validateParams,
    ) {}

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->get('q', '');
        $query = $this->categoryRepository->findByQ($q);
        $pageNumber =  $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate($query, $pageNumber, $limit);

        return $this->render('category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('tenant_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $categoryId = $this->validateParams->validatedInt($request->get('id'), 'category');
            $category = $this->entityManager->getRepository(Category::class)->find($categoryId);

            if (!$category) {
                throw new Exception('Category not found');
            }

            return $this->render('category/show.html.twig', [
                'category' => $category,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_category_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function edit(Request $request): Response
    {
        try {
            $categoryId = $this->validateParams->validatedInt($request->get('id'), 'category');
            $category = $this->entityManager->getRepository(Category::class)->find($categoryId);

            if (!$category) {
                throw new Exception('Category not found');
            }

            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('tenant_category_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('category/edit.html.twig', [
                'category' => $category,
                'form' => $form,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_category_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function delete(Request $request): Response
    {
        try {
            $categoryId = $this->validateParams->validatedInt($request->get('id'), 'category');

            if ($this->isCsrfTokenValid('delete' . $categoryId, $request->getPayload()->getString('_token'))) {
                $this->categoryRepository->disable($categoryId);
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
