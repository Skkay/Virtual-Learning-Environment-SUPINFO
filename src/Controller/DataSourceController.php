<?php

namespace App\Controller;

use App\Entity\DataSource;
use App\Form\DataSourceType;
use App\Repository\DataSourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/data-source", name="app.data_source.")
 */
class DataSourceController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(DataSourceRepository $dataSourceRepository): Response
    {
        return $this->render('data_source/index.html.twig', [
            'data_sources' => $dataSourceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dataSource = new DataSource();
        $form = $this->createForm(DataSourceType::class, $dataSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dataSource);
            $entityManager->flush();

            return $this->redirectToRoute('app.data_source.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('data_source/new.html.twig', [
            'data_source' => $dataSource,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(DataSource $dataSource): Response
    {
        return $this->render('data_source/show.html.twig', [
            'data_source' => $dataSource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DataSource $dataSource, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DataSourceType::class, $dataSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.data_source.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('data_source/edit.html.twig', [
            'data_source' => $dataSource,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, DataSource $dataSource, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dataSource->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dataSource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.data_source.index', [], Response::HTTP_SEE_OTHER);
    }
}
