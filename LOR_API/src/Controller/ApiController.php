<?php

namespace App\Controller;

use App\Service\MatchApi;
use App\Service\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/update", name="api-update")
     * @param DocumentManager $dm
     * @return Response
     */
    public function update(DocumentManager $dm): Response
    {
         $api = new Api();
        $test = $this->getDoctrine()->getManager();
        $p = $api->getApi($test, $dm);
        
        $apiMatch = new MatchApi();
        $test2 = $this->getDoctrine()->getManager();
        $i = $apiMatch->getMatchApi($test2, $dm);
        return new Response(
            'Creted product id'
        );
    }
}