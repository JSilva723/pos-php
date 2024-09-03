<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\PriceList;
use Tenant\Form\PriceListType;
use Tenant\Repository\PriceListRepository;
use Tenant\Repository\ProductPriceListRepository;

class PriceListController extends AbstractController
{
    public function index(
        Request $request,
        PriceListRepository $priceListRepository,
        PaginatorInterface $paginator,
    ): Response {
        // Search by name
        $q = $request->get('q', '');
        $query = $priceListRepository->findByQ($q);

        $pagination = $paginator->paginate(
            $query, // query NOT result
            $request->query->getInt('page', 1), // page number
            10, // limit per page
        );

        return $this->render('price-list/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $priceList = new PriceList();

        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($priceList);
            $entityManager->flush();

            return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('price-list/new.html.twig', [
            'form' => $form,
        ]);
    }

    public function show(
        PriceList $priceList,
        Request $request,
        ProductPriceListRepository $productPriceListRepository,
        PaginatorInterface $paginator,
    ): Response {
        $q = $request->get('q', '');
        $query = $productPriceListRepository->findByQ($q, $priceList->getId());
        $pagination = $paginator->paginate(
            $query, // query NOT result
            $request->query->getInt('page', 1), // page number
            10, // limit per page
        );

        return $this->render('price-list/show.html.twig', [
            'pagination' => $pagination,
            'priceList' => $priceList,
        ]);
    }

    public function edit(Request $request, PriceList $priceList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PriceListType::class, $priceList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('price-list/edit.html.twig', [
            'priceList' => $priceList,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, PriceList $priceList, PriceListRepository $priceListRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $priceList->getId(), $request->getPayload()->getString('_token'))) {
            $priceListRepository->disable($priceList->getId());
        }

        return $this->redirectToRoute('tenant_price_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
