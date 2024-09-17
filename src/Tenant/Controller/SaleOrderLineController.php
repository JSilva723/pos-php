<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Repository\SaleOrderLineRepository;
use Tenant\Services\ValidateParams;

class SaleOrderLineController extends AbstractController
{
    public const UNITS_TRANS = [
        'Gram' => 'Gram',
        'Gramos' => 'Gram',
        'Kilogram' => 'Kilogram',
        'Kilogramo' => 'Kilogram',
    ];

    public function __construct(
        private readonly ValidateParams $validateParams,
        private readonly SaleOrderLineRepository $saleOrderLineRepository,
    ) {}

    public function addLine(Request $request): Response
    {
        try {
            $saleOrderId = $this->validateParams->validatedInt($request->get('soid'), 'sale order');
            $productId = $this->validateParams->validatedInt($request->get('pid'), 'product');
            $quantity = $this->validateParams->validatedFloat($request->get('quantity'), 'quantity');
            $price = $this->validateParams->validatedFloat($request->get('price'), 'price');
            $uom = $request->get('uom');

            $this->saleOrderLineRepository->addLine($productId, $saleOrderId, $quantity, $price, self::UNITS_TRANS[$uom]);

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrderId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            $route = isset($saleOrderId) ? 'tenant_sale_order_show' : 'tenant_sale_order_index';
            $params = isset($saleOrderId) ? ['id' => $saleOrderId] : [];

            return $this->redirectToRoute($route, $params, Response::HTTP_SEE_OTHER);
        }
    }

    public function removeLine(Request $request): Response
    {
        try {
            $saleOrderId = $this->validateParams->validatedInt($request->get('soid'), 'sale order');
            $saleOrderLineId = $this->validateParams->validatedInt($request->get('solid'), 'order line');

            $this->saleOrderLineRepository->removeLine($saleOrderLineId);

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrderId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            $route = isset($saleOrderId) ? 'tenant_sale_order_show' : 'tenant_sale_order_index';
            $params = isset($saleOrderId) ? ['id' => $saleOrderId] : [];

            return $this->redirectToRoute($route, $params, Response::HTTP_SEE_OTHER);
        }
    }
}
