<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        // return $this->json([
            // 'message' => 'Welcome to catch challenge 1'
            // 'path' => 'src/Controller/MainController.php',
        // ]);
        // $response = new Response();

        // $response->setContent('<html><body><h1>Hello world!</h1></body></html>');
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/custom/{name?}", name="custom")
     * @param Request $request
     * @return Response
     */
    public function custom(Request $request) {
        // $response = new Response();
        $name = $request->get('name');

        // $response->setContent('<html><body><h1>Welcome '. $name .' </h1></body></html>');
        // return $response;
        return $this->render('home/custom.html.twig',[
            'name' => $name
        ]);
    }
}
