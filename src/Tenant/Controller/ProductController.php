<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\Product;
use Tenant\Form\ProductType;
use Tenant\Repository\ProductRepository;

use function base64_encode;
use function file_get_contents;

class ProductController extends AbstractController
{
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $q = $request->get('q', '');
        $query = $productRepository->findByQ($q);

        $pagination = $paginator->paginate(
            $query, // query NOT result
            $request->query->getInt('page', 1), // page number
            10, // limit per page
        );

        return $this->render('product/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('tenant_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tenant_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $productRepository->disable($product->getId());
        }

        return $this->redirectToRoute('tenant_product_index', [], Response::HTTP_SEE_OTHER);
    }

    public function upload(Request $request, EntityManagerInterface $entityManager): Response
    {
        $file = $request->files->get('img');

        if (!$file) {
            return new Response('File not uploaded', 400);
        }

        $imgData = file_get_contents($file->getPathname());

        if (!$imgData) {
            return new Response('File not content', 400);
        }

        $imgBase64 = base64_encode($imgData);

        $id = $request->get('id');
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return new Response('Product not found', 400);
        }

        $product->setImg($imgBase64);
        $product->setMimeType($file->getMimeType());

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('tenant_product_show', ['id' => $id]);
    }
}
