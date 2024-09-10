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
use Tenant\Services\ValidateParams;

use function count;

class SaleOrderController extends AbstractController
{
    public function __construct(
        private readonly SaleOrderRepository $saleOrderRepository,
        private readonly ValidateParams $validateParams,
        private readonly EntityManagerInterface $entityManager,
        private readonly SaleOrderLineRepository $saleOrderLineRepository,
    ) {}

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $status = $request->get('status', SaleOrder::STATUS_OPEN);
        $query = $this->saleOrderRepository->findByStatus($status);
        $pageNeumber = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate($query, $pageNeumber, $limit);

        return $this->render('sale-order/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request): Response
    {
        $saleOrder = new SaleOrder();
        /** @var User */
        $user = $this->getUser();
        $saleOrder->setUser($user);

        $form = $this->createForm(SaleOrderOpenType::class, $saleOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($saleOrder);
            $this->entityManager->flush();

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrder->getId(),
                'saleOrder' => $saleOrder,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale-order/new.html.twig', [
            'saleOrder' => $saleOrder,
            'form' => $form,
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $saleOrderId = $this->validateParams->validatedInt($request->get('id'), 'sale order');
            $saleOrder = $this->entityManager->getRepository(SaleOrder::class)->find($saleOrderId);

            if (!$saleOrder) {
                throw new Exception('Sale order not found');
            }

            $form = $this->createForm(SaleOrderCloseType::class, $saleOrder, [
                'action' => $this->generateUrl('tenant_sale_order_close', ['soid' => $saleOrder->getId()]),
                'method' => 'POST',
            ]);

            $products = $this->saleOrderRepository->getProductsWhitPrice($saleOrder->getPriceList()->getId());
            $orderLines = $this->saleOrderLineRepository->getLinesById($saleOrder->getId());

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
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_sale_order_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function close(Request $request): Response
    {
        try {
            $saleOrderId = $this->validateParams->validatedInt($request->get('soid'), 'sale order');
            $paymentId = $this->validateParams->validatedInt($request->get('sale_order_close')['payment'], 'payment');

            $orderLines = $this->saleOrderLineRepository->getLinesById($saleOrderId);

            $this->entityManager->beginTransaction();

            try {
                $this->saleOrderRepository->updatePayment($saleOrderId, $paymentId);
                $this->saleOrderRepository->updateStock($orderLines);
                $this->entityManager->commit();
            } catch (Exception $e) {
                $this->entityManager->rollback();

                throw $e;
            } finally {
                $this->entityManager->close();
            }

            return $this->redirectToRoute('tenant_sale_order_show', [
                'id' => $saleOrderId,
            ], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_sale_order_show', ['id' => $saleOrderId], Response::HTTP_SEE_OTHER);
    }
}
