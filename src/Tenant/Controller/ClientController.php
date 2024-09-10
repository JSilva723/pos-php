<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tenant\Entity\Client;
use Tenant\Form\ClientType;
use Tenant\Repository\ClientRepository;
use Tenant\Services\ValidateParams;

class ClientController extends AbstractController
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidateParams $validateParams,
    ) {}

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->get('q', '');
        $query = $this->clientRepository->findByQ($q);
        $pageNumber = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate($query, $pageNumber, $limit);

        return $this->render('client/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('tenant_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $cientId = $this->validateParams->validatedInt($request->get('id'), 'client');
            $client = $this->entityManager->getRepository(Client::class)->find($cientId);

            if (!$client) {
                throw new Exception('Client not found');
            }

            return $this->render('client/show.html.twig', [
                'client' => $client,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_client_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function edit(Request $request): Response
    {
        try {
            $cientId = $this->validateParams->validatedInt($request->get('id'), 'client');
            $client = $this->entityManager->getRepository(Client::class)->find($cientId);

            if (!$client) {
                throw new Exception('Client not found');
            }

            $form = $this->createForm(ClientType::class, $client);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('tenant_client_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('client/edit.html.twig', [
                'client' => $client,
                'form' => $form,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('tenant_client_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function delete(Request $request): Response
    {
        try {
            $cientId = $this->validateParams->validatedInt($request->get('id'), 'client');

            if ($this->isCsrfTokenValid('delete' . $cientId, $request->getPayload()->getString('_token'))) {
                $this->clientRepository->disable($cientId);
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('tenant_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
