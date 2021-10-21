<?php

namespace App\Service;

use App\Document\InfosPersoLOL;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Response;

class Api
{
    public function getApi($test, $dm)
        {
            $url = file_get_contents("http://51.255.160.47:8181/euw1/passerelle/getHistoryMatchList/azerty");
            $json = json_decode($url, true);
            
            foreach ($json["matches"] as $data)
            {

                if (isset($data)) {
                    echo '<pre>';
                    var_dump($data);
                    echo '</pre>';
                    if (array_key_exists("gameId", $data)) {
                        $gameId = $data["gameId"];
                    }

                    // if (array_key_exists("pseudo", $data)) {
                    //     $pseudo = $data["pseudo"];
                    // }

                    if (array_key_exists("role", $data)) {
                        $role = $data["role"];
                    }

                    if (array_key_exists("lane", $data)) {
                        $lane = $data["lane"];
                    }

                    if (array_key_exists("champion", $data)) {
                        $champion = $data["champion"];
                    }

                    if (array_key_exists("season", $data)) {
                        $season = $data["season"];
                    }

                    // if (array_key_exists("timeStamp", $data)) {
                    //     $timeStamp = $data["timeStamp"];
                    // }
                    
                    // $array = $dm->getRepository(InfosPersoLOL::class)->find($gameId);
                    // var_dump($array);
                    $array = new InfosPersoLOL();

                    $array->setGameId($gameId);
                    // $array->setPseudo($pseudo);
                    $array->setRole($role);
                    $array->setLane($lane);
                    $array->setChampion($champion);
                    $array->setSeason($season);
                    // $array->setTimeStamp($timeStamp);

                    $dm->persist($array);
                    $dm->flush();
                }
            }
    }
}