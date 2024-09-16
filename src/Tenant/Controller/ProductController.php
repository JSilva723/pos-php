<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\Product;
use Tenant\Form\ProductCreateType;
use Tenant\Form\ProductEditType;
use Tenant\Repository\ProductPriceListRepository;
use Tenant\Repository\ProductRepository;
use Tenant\Services\ValidateParams;

use function base64_encode;
use function file_get_contents;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidateParams $validateParams,
    ) {}

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->get('q', '');
        $query = $this->productRepository->findByQ($q);
        $pageNeumber = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate($query, $pageNeumber, $limit);

        return $this->render('product/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request, ProductPriceListRepository $productPriceListRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductCreateType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isFractionable = $form->get('isFractionable')->getData();

            if ($isFractionable) {
                $unitOfMeasure = $form->get('unitOfMeasure')->getData();
                $product->setUnitOfMeasureForSale($unitOfMeasure);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $priceList = $form->get('priceList')->getData();
            $price = $form->get('price')->getData();

            $productPriceListRepository->create($product->getId(), $priceList->getId(), (float) $price);

            return $this->redirectToRoute('tenant_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $productId = $this->validateParams->validatedInt($request->get('id'), 'product');
            $product = $this->entityManager->getRepository(Product::class)->find($productId);

            if (!$product) {
                throw new Exception('Product list not found');
            }

            return $this->render('product/show.html.twig', [
                'product' => $product,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_product_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function edit(Request $request): Response
    {
        try {
            $productId = $this->validateParams->validatedInt($request->get('id'), 'product');
            $product = $this->entityManager->getRepository(Product::class)->find($productId);

            if (!$product) {
                throw new Exception('Product list not found');
            }

            $form = $this->createForm(ProductEditType::class, $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('tenant_product_show', [
                    'id' => $productId,
                ], Response::HTTP_SEE_OTHER);
            }

            return $this->render('product/edit.html.twig', [
                'product' => $product,
                'form' => $form,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            $route = $productId ? 'tenant_product_show' : 'tenant_product_index';
            $params = $productId ? ['id' => $productId] : [];

            return $this->redirectToRoute($route, $params, Response::HTTP_SEE_OTHER);
        }
    }

    public function delete(Request $request): Response
    {
        try {
            $productId = $this->validateParams->validatedInt($request->get('id'), 'product');

            if ($this->isCsrfTokenValid('delete' . $productId, $request->getPayload()->getString('_token'))) {
                $this->productRepository->disable($productId);
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_product_index', [], Response::HTTP_SEE_OTHER);
    }

    public function upload(Request $request): Response
    {
        try {
            $file = $request->files->get('img');

            if (!$file) {
                throw new Exception('File not uploaded');
            }

            $imgData = file_get_contents($file->getPathname());

            if (!$imgData) {
                throw new Exception('File not content');
            }

            $imgBase64 = base64_encode($imgData);

            $productId = $request->get('id');
            $product = $this->entityManager->getRepository(Product::class)->find($productId);

            if (!$product) {
                throw new Exception('Product not found');
            }

            $product->setImg($imgBase64);
            $product->setMimeType($file->getMimeType());

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('tenant_product_show', [
                'id' => $productId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            $route = isset($productId) ? 'tenant_product_show' : 'tenant_product_index';
            $params = isset($productId) ? ['id' => $productId] : [];

            return $this->redirectToRoute($route, $params, Response::HTTP_SEE_OTHER);
        }
    }
}
