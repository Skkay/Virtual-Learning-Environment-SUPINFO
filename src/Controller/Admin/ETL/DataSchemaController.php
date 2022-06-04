<?php

namespace App\Controller\Admin\ETL;

use App\Entity\DataSchema;
use App\Form\DataSchemaType;
use App\Repository\DataSchemaRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    private $em;

    /** @var DataSchemaRepository */
    private $dataSchemaRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->dataSchemaRepository = $this->em->getRepository(DataSchema::class);
    }

    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $dataSchemas = $this->dataSchemaRepository->findAll();

        return $this->render('admin/etl/data_schema/index.html.twig', [
            'data_schemas' => $dataSchemas,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $dataSchema = new DataSchema();

        $form = $this->createForm(DataSchemaType::class, $dataSchema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($dataSchema);
            $this->em->flush();

            return $this->redirectToRoute('app.admin.etl.data_schema.index');
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
    public function edit(Request $request, DataSchema $dataSchema): Response
    {
        $form = $this->createForm(DataSchemaType::class, null, [
            'data_schema' => $dataSchema,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('app.admin.etl.data_schema.index');
        }

        return $this->renderForm('admin/etl/data_schema/edit.html.twig', [
            'data_schema' => $dataSchema,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, DataSchema $dataSchema): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dataSchema->getId(), $request->request->get('_token'))) {
            $this->em->remove($dataSchema);
            $this->em->flush();
        }

        return $this->redirectToRoute('app.admin.etl.data_schema.index');
    }
}
