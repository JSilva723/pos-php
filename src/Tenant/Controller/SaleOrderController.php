<?php

declare(strict_types=1);

namespace Tenant\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\SaleOrder;
use Tenant\Entity\User;
use Tenant\Repository\SaleOrderLineRepository;
use Tenant\Repository\SaleOrderRepository;

class SaleOrderController extends AbstractController
{
    public function index(
        Request $request,
        SaleOrderRepository $saleOrderRepository,
        PaginatorInterface $paginator,
    ): Response {
        $status = $request->get('status', '');
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

    public function new(EntityManagerInterface $entityManager): Response
    {
        try {
            $user = $entityManager->getRepository(User::class)->find($this->getUser());

            if (!$user) {
                throw new Exception('User not found');
            }

            $saleOrder = new SaleOrder();
            $saleOrder->setStatus(SaleOrder::STATUS_OPEN);
            $saleOrder->setUser($user);
            $saleOrder->setDate(new DateTime());
            $entityManager->persist($saleOrder);
            $entityManager->flush();

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrder->getId(),
                'saleOrder' => $saleOrder,
            ], Response::HTTP_SEE_OTHER);
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
        $products = $saleOrderRepository->getProductsWhitPrice();
        $orderLines = $saleOrderLineRepository->getLinesById($saleOrder->getId());

        return $this->render('sale-order/show.html.twig', [
            'saleOrder' => $saleOrder,
            'products' => $products,
            'orderLines' => $orderLines,
        ]);
    }
}
