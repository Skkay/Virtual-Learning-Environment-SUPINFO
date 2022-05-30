<?php

namespace App\Controller\Admin\ETL;

use App\Entity\DataSchema;
use App\Form\DataSchemaType;
use App\Repository\DataSchemaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/etl/data_schemas", name="app.admin.etl.data_schema.")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class DataSchemaController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(DataSchemaRepository $dataSchemaRepository): Response
    {
        return $this->render('admin/etl/data_schema/index.html.twig', [
            'data_schemas' => $dataSchemaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dataSchema = new DataSchema();
        $form = $this->createForm(DataSchemaType::class, $dataSchema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dataSchema);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.etl.data_schema.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/etl/data_schema/new.html.twig', [
            'data_schema' => $dataSchema,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(DataSchema $dataSchema): Response
    {
        return $this->render('admin/etl/data_schema/show.html.twig', [
            'data_schema' => $dataSchema,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DataSchema $dataSchema, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DataSchemaType::class, $dataSchema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.etl.data_schema.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/etl/data_schema/edit.html.twig', [
            'data_schema' => $dataSchema,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, DataSchema $dataSchema, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dataSchema->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dataSchema);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.etl.data_schema.index', [], Response::HTTP_SEE_OTHER);
    }
}
