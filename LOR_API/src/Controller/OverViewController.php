<?php

namespace App\Controller;

use App\Document\InfosPersoLOL;
use App\Document\Lane;
use App\Document\Role;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverViewController extends AbstractController
{
    /**
     * @Route("/over/view", name="over_view")
     */
    public function index(DocumentManager $dm): Response
    {
        $role = $dm->getRepository(Role::class)->findBy([]);
        $lane = $dm->getRepository(Lane::class)->findBy([]);
        $query = $dm->getRepository(InfosPersoLOL::class)->findBy(['pseudo' => 'azerty']);

        for($i = 0; $i < count($role); $i++){
            for($v = 0; $v < count($query); $v++){
                 if ($role[$i]->{'role'} === $query[$v]->{'role'}){
                    $role[$i]->{'count'} = $role[$i]->{'count'} + 1;
                 }
            }
        }

        for($i = 0; $i < count($lane); $i++){
            for($v = 0; $v < count($query); $v++){
                 if ($lane[$i]->{'lane'} === $query[$v]->{'lane'}){
                    $lane[$i]->{'count'} = $lane[$i]->{'count'} + 1;
                 }
            }
        }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OverViewController.php',
            'role' => $role,
            'role' => $lane,
            'countParty' => count($query),
        ]);
    }
}
