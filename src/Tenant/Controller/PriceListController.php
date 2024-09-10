<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\PriceList;
use Tenant\Form\PriceListType;
use Tenant\Repository\PriceListRepository;
use Tenant\Repository\ProductPriceListRepository;
use Tenant\Services\ValidateParams;

class PriceListController extends AbstractController
{
    public function __construct(
        private readonly PriceListRepository $priceListRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidateParams $validateParams,
        private readonly PaginatorInterface $paginator,
    ) {}

    public function index(Request $request): Response
    {
        $q = $request->get('q', '');
        $query = $this->priceListRepository->findByQ($q);
        $pageNeumber = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $this->paginator->paginate($query, $pageNeumber, $limit);

        return $this->render('price-list/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request): Response
    {
        $priceList = new PriceList();
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($priceList);
            $this->entityManager->flush();

            return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('price-list/new.html.twig', [
            'form' => $form,
        ]);
    }

    public function show(Request $request, ProductPriceListRepository $productPriceListRepository): Response
    {
        try {
            $priceListId = $this->validateParams->validatedInt($request->get('id'), 'price list');
            $priceList = $this->entityManager->getRepository(PriceList::class)->find($priceListId);

            if (!$priceList) {
                throw new Exception('Price list not found');
            }

            $q = $request->get('q', '');
            $query = $productPriceListRepository->findByQ($q, $priceList->getId());
            $pageNumber = $request->query->getInt('page', 1);
            $limit = 10;

            $pagination = $this->paginator->paginate($query, $pageNumber, $limit);

            return $this->render('price-list/show.html.twig', [
                'pagination' => $pagination,
                'priceList' => $priceList,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function edit(Request $request): Response
    {
        try {
            $priceListId = $this->validateParams->validatedInt($request->get('id'), 'price list');
            $priceList = $this->entityManager->getRepository(PriceList::class)->find($priceListId);

            if (!$priceList) {
                throw new Exception('Price list not found');
            }

            $form = $this->createForm(PriceListType::class, $priceList);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('price-list/edit.html.twig', [
                'priceList' => $priceList,
                'form' => $form,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function delete(Request $request): Response
    {
        try {
            $priceListId = $this->validateParams->validatedInt($request->get('id'), 'price list');

            if ($this->isCsrfTokenValid('delete' . $priceListId, $request->getPayload()->getString('_token'))) {
                $this->priceListRepository->disable($priceListId);
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
