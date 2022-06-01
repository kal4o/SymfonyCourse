<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/custom/{urlParam?}", name="custom")
     * @param Request $request
     * @return Response
     */
    public function custom(Request $request) {
        $urlParam = dump($request->get('urlParam'));
        return $this->render('main/custom.html.twig', ['urlParam' =>  $urlParam]);
    }
}
