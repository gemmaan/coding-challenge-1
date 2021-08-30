<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Rs\JsonLines\JsonLines;
use App\Services\JSONLinesReader;
use App\Services\OrderHelper;
use App\Services\CSVHelper;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {   
        $jsonReader = new JSONLinesReader();
        $ordersArray = $jsonReader -> readJSONLines('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl');

        $orderHelper = new OrderHelper();
        $orderObjectArray = $orderHelper -> generateOrders($ordersArray);

        return $this->render('home/index.html.twig',[
            'orders' => $orderObjectArray
        ]);
    }

    /**
     * @Route("/export", name="export")
     * @return Response
     */
    public function export() {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="out.csv"');

        $jsonReader = new JSONLinesReader();
        $ordersArray = $jsonReader -> readJSONLines('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl');

        $orderHelper = new OrderHelper();
        $orderObjectArray = $orderHelper -> generateOrders($ordersArray);

        $csvHelper = new CSVHelper();
        $exportableArray = $csvHelper -> generateExportableOrder($orderObjectArray);
           
        $fp = fopen('php://output', 'w');
          
        foreach ($exportableArray as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        $response = new Response();
        return $response;
    }
}
