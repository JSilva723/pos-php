<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\ProductPriceList;
use Tenant\Repository\ProductPriceListRepository;

use function is_numeric;

class ProductPriceListController extends AbstractController
{
    public function create(
        Request $request,
        ProductPriceListRepository $productPriceListRepository,
    ): Response {
        try {
            $priceListId = $this->getValidatedId($request->get('lid'), 'price list');
            $productId = $this->getValidatedId($request->get('pid'), 'product');
            $price = $this->getValidatedPrice($request->get('price'));

            $productPriceListRepository->create($productId, $priceListId, $price);

            $this->addFlash('success', 'porduct updated');

            return $this->redirectToRoute('tenant_price_list_show', ['id' => $priceListId]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_price_list_show', ['id' => $priceListId]);
        }
    }

    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $productPriceListId = $request->get('pplid');
            $priceListId = $request->get('lid');
            $price = $this->getValidatedPrice($request->get('price'));

            $productPriceList = $entityManager->getRepository(ProductPriceList::class)->find($productPriceListId);

            if (!$productPriceList) {
                throw new Exception('Product price not found');
            }
            $productPriceList->setPrice($price);

            $entityManager->persist($productPriceList);
            $entityManager->flush();

            $this->addFlash('success', 'porduct updated');

            return $this->redirectToRoute('tenant_price_list_show', ['id' => $priceListId]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_price_list_show', ['id' => $priceListId]);
        }
    }

    private function getValidatedId(string $id, string $type): int
    {
        if (!$id || !is_numeric($id)) {
            throw new Exception("{$type} ID is required and must be a number");
        }

        return (int) $id;
    }

    private function getValidatedPrice(string $price): float
    {
        if (!$price) {
            throw new Exception('Price is required');
        }

        if (!is_numeric($price)) {
            throw new Exception('Price must be a number');
        }

        return (float) $price;
    }
}
