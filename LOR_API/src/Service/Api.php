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
                    var_dump($data);
                    if (array_key_exists("game_id", $data)) {
                        $gameId = $data["gameId"];
                    }

                    if (array_key_exists("username", $data)) {
                        $gameId = $data["username"];
                    }

                    if (array_key_exists("role", $data)) {
                        $gameId = $data["role"];
                    }

                    if (array_key_exists("lane", $data)) {
                        $gameId = $data["lane"];
                    }
                    
                    // $array = $dm->getRepository(InfosPersoLOL::class)->find([
                    //     "gameId => $gameId"
                    // ]);
                    // var_dump($array);
                }
            }
    }
}