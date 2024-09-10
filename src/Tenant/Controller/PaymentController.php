<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\Payment;
use Tenant\Form\PaymentType;
use Tenant\Repository\PaymentRepository;
use Tenant\Services\ValidateParams;

class PaymentController extends AbstractController
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidateParams $validateParams,
    ) {}

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->get('q', '');
        $query = $this->paymentRepository->findByQ($q);
        $pageNeumber = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate($query, $pageNeumber, $limit);

        return $this->render('payment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            return $this->redirectToRoute('tenant_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $paymentId = $this->validateParams->validatedInt($request->get('id'), 'payment');
            $payment = $this->entityManager->getRepository(Payment::class)->find($paymentId);

            if (!$payment) {
                throw new Exception('Payment not found');
            }

            return $this->render('payment/show.html.twig', [
                'payment' => $payment,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_payment_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function edit(Request $request): Response
    {
        try {
            $paymentId = $this->validateParams->validatedInt($request->get('id'), 'payment');
            $payment = $this->entityManager->getRepository(Payment::class)->find($paymentId);

            if (!$payment) {
                throw new Exception('Payment not found');
            }

            $form = $this->createForm(PaymentType::class, $payment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('tenant_payment_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('payment/edit.html.twig', [
                'payment' => $payment,
                'form' => $form,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_payment_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function delete(Request $request): Response
    {
        try {
            $paymentId = $this->validateParams->validatedInt($request->get('id'), 'payment');

            if ($this->isCsrfTokenValid('delete' . $paymentId, $request->getPayload()->getString('_token'))) {
                $this->paymentRepository->disable($paymentId);
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
