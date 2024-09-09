<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\SaleOrder;
use Tenant\Entity\User;
use Tenant\Form\SaleOrderCloseType;
use Tenant\Form\SaleOrderOpenType;
use Tenant\Repository\SaleOrderLineRepository;
use Tenant\Repository\SaleOrderRepository;

use function count;
use function is_numeric;

class SaleOrderController extends AbstractController
{
    public function index(
        Request $request,
        SaleOrderRepository $saleOrderRepository,
        PaginatorInterface $paginator,
    ): Response {
        $status = $request->get('status', SaleOrder::STATUS_OPEN);
        $query = $saleOrderRepository->findByStatus($status);

        $pagination = $paginator->paginate(
            $query, // query NOT result
            $request->query->getInt('page', 1), // page number
            10, // limit per page
        );

        return $this->render('sale-order/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $user = $entityManager->getRepository(User::class)->find($this->getUser());

            if (!$user) {
                throw new Exception('User not found');
            }

            $saleOrder = new SaleOrder();
            $saleOrder->setUser($user);

            $form = $this->createForm(SaleOrderOpenType::class, $saleOrder);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($saleOrder);
                $entityManager->flush();

                return $this->redirectToRoute('tenant_sale_order_show', [
                    'id' => $saleOrder->getId(),
                    'saleOrder' => $saleOrder,
                ], Response::HTTP_SEE_OTHER);
            }

            return $this->render('sale-order/new.html.twig', [
                'saleOrder' => $saleOrder,
                'form' => $form,
            ]);
        } catch (Exception) {
            // TODO: handle this execption
            return $this->redirectToRoute('tenant_sale_order_index');
        }
    }

    public function show(
        SaleOrder $saleOrder,
        SaleOrderRepository $saleOrderRepository,
        SaleOrderLineRepository $saleOrderLineRepository,
    ): Response {
        $form = $this->createForm(SaleOrderCloseType::class, $saleOrder, [
            'action' => $this->generateUrl('tenant_sale_order_close', ['soid' => $saleOrder->getId()]),
            'method' => 'POST',
        ]);

        $products = $saleOrderRepository->getProductsWhitPrice($saleOrder->getPriceList()->getId());
        $orderLines = $saleOrderLineRepository->getLinesById($saleOrder->getId());

        $total = 0;

        for ($i = 0; $i < count($orderLines); $i++) {
            $subTotal = $orderLines[$i]['quantity'] * (int) $orderLines[$i]['price'];
            $total += $subTotal;
            $orderLines[$i]['subTotal'] = $subTotal;
        }

        return $this->render('sale-order/show.html.twig', [
            'saleOrder' => $saleOrder,
            'products' => $products,
            'orderLines' => $orderLines,
            'form' => $form,
            'total' => $total,
        ]);
    }

    public function close(
        Request $request,
        SaleOrderRepository $saleOrderRepository,
    ): Response {
        try {
            $saleOrderId = $this->getValidatedInt($request->get('soid'), 'sale order');
            $paymentId = $this->getValidatedInt($request->get('sale_order_close')['payment'], 'payment');

            $saleOrderRepository->closeOrder($saleOrderId, $paymentId);

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrderId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception) {
            // TODO: handle this execption
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
}
