<?php

namespace App\Controller;

use App\Service\DataLoaderService;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app.home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/test", name="app.test")
     */
    public function test(DataLoaderService $dataLoaderService): Response
    {
        $dataLoaderService->loadFiles();
        // return $this->json(['response' => 200]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // /**
    //  * @Route("/test", name="app.test")
    //  */
    // public function test(TestService $testService): Response
    // {
    //     $testService->testExtractor();
    //     // return $this->json(['response' => 200]);
    //     return $this->render('home/index.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }
}
