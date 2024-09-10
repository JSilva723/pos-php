<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Repository\ProductPriceListRepository;
use Tenant\Services\ValidateParams;

class ProductPriceListController extends AbstractController
{
    public function __construct(
        private readonly ValidateParams $validateParams,
        private readonly ProductPriceListRepository $productPriceListRepository,
    ) {}

    public function create(Request $request): Response
    {
        try {
            $priceListId = $this->validateParams->validatedInt($request->get('lid'), 'price list');
            $productId = $this->validateParams->validatedInt($request->get('pid'), 'product');
            $price = $this->validateParams->validatedFloat($request->get('price'), 'price');

            $this->productPriceListRepository->create($productId, $priceListId, $price);

            $this->addFlash('success', 'porduct updated');
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_price_list_show', ['id' => $priceListId]);
    }

    public function update(Request $request): Response
    {
        try {
            $productPriceListId = $this->validateParams->validatedInt($request->get('pplid'), 'product price list');
            $priceListId = $this->validateParams->validatedInt($request->get('lid'), 'price list');
            $price = $this->validateParams->validatedFloat($request->get('price'), 'price');

            $this->productPriceListRepository->update($price, $productPriceListId);

            $this->addFlash('success', 'porduct updated');
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_price_list_show', ['id' => $priceListId]);
    }
}
