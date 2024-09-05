<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\SaleOrderLine;
use Tenant\Repository\SaleOrderLineRepository;

use function is_numeric;

class SaleOrderLineController extends AbstractController
{
    public function addLine(Request $request, SaleOrderLineRepository $saleOrderLineRepository): Response
    {
        try {
            $saleOrderId = $this->getValidatedInt($request->get('id'), 'sale order');
            $productId = $this->getValidatedInt($request->get('pid'), 'product');
            $quantity = $this->getValidatedInt($request->get('quantity'), 'quantity');
            $price = $this->getValidatedFloat($request->get('price'), 'price');

            $saleOrderLineRepository->add($productId, $saleOrderId, $quantity, $price);

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrderId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_sale_order_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function removeLine(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $saleOrderId = $this->getValidatedInt($request->get('id'), 'sale order');
            $saleOrderLineId = $this->getValidatedInt($request->get('solid'), 'product');
            $saleOrderLine = $entityManager->getRepository(SaleOrderLine::class)->find($saleOrderLineId);

            if (!$saleOrderLine) {
                throw new Exception('Order line not found');
            }

            $entityManager->remove($saleOrderLine);
            $entityManager->flush();

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrderId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_sale_order_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    private function getValidatedInt(string $value, string $type): int
    {
        if (!$value || !is_numeric($value)) {
            throw new Exception("{$type} is required and must be a number");
        }

        return (int) $value;
    }

    private function getValidatedFloat(string $value, string $type): float
    {
        if (!$value || !is_numeric($value)) {
            throw new Exception("{$type} is required and must be a number");
        }

        return (float) $value;
    }
}
