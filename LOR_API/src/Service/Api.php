<?php

namespace App\Service;

use App\Document\InfosPersoLOL;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Response;

class Api
{ 
    public function getApi($test, $dm)
    {
        $arraySumonnerName = [ 'azerty', 'Phyrro', 'Myrendir', 'Cynath17', 'druxys', 'asymptomatik', 'nikoflk', 'runerodBaguette', 'Bananiolette', 'Alphinax', 'Batounu', 'Colonel popi', 'Dr Ratch', 'Doraiiden'];
        for ($i = 0; $i < (count($arraySumonnerName)); $i++) {
            $url = file_get_contents("http://51.255.160.47:8181/euw1/passerelle/getHistoryMatchList/" . $arraySumonnerName[$i]);
            $json = json_decode($url, true);
           
            foreach ($json["matches"] as $data)
            {

                if (isset($data)) {
                    if (array_key_exists("gameId", $data)) {
                        $gameId = $data["gameId"];
                    }

                        $pseudo = $arraySumonnerName[$i];

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

                    if (array_key_exists("timestamp", $data)) {
                        $timeStamp = $data["timestamp"];
                    }

                        $score = 50;

                     $repository = $dm->getRepository(InfosPersoLOL::class);
                     $array = $repository->findBy(['gameId' => $gameId]);

                    if ($array) {
                        // $array->setGameId($gameId);
                        // $array->setPseudo($pseudo);
                        // $array->setRole($role);
                        // $array->setLane($lane);
                        // $array->setChampion($champion);
                        // $array->setSeason($season);
                        // $array->setTimeStamp($timeStamp);

                        // $dm->persist($array);
                    } else {
                        $array = new InfosPersoLOL();

                        $array->setGameId($gameId);
                        $array->setPseudo($pseudo);
                        $array->setRole($role);
                        $array->setLane($lane);
                        $array->setChampion($champion);
                        $array->setSeason($season);
                        $array->setTimeStamp($timeStamp);
                        $array->setScore($score);
                        
                        $dm->persist($array);
                    }
                }
            }
             $dm->flush();
        }  
    }
}