<?php

namespace App\Controller;

use App\Document\InfosPersoLOL;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfosMatchUserController extends AbstractController
{
    /**
     * @Route("/infos/match/user/azerty", name="infos_match_user")
     */
    public function index(DocumentManager $dm): Response
    {
        $queryMe = $dm->getRepository(InfosPersoLOL::class)->findBy(['pseudo' => 'azerty']);
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/InfosMatchUserController.php',
            'infosMatchUser' => $queryMe,
        ]);
    }
}
