<?php

namespace App\Controller;

use App\Document\Complementary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\InfosPersoLOL;
use App\Document\Criterias;
use App\Document\PlayerAlgo;
use Doctrine\ODM\MongoDB\DocumentRepository;

class GetUsersLolController extends AbstractController
{
    /**
     * @Route("/get/users/lol", name="get_users_lol")

     */
    public function index(DocumentManager $dm): Response
    {
        $queryInfosPersoLol = $dm->getRepository(InfosPersoLOL::class)->findBy([]);
        $queryCriteria = $dm->getRepository(Criterias::class)->findBy([]);
        $queryPlayerAlgo = $dm->getRepository(PlayerAlgo::class)->findBy([]);
        $queryMe = $dm->getRepository(InfosPersoLOL::class)->findBy(['pseudo' => 'azerty']);
        $queryComplementary = $dm->getRepository(Complementary::class)->findBy([]);

        
        echo '<pre>';
    //    dd($queryPlayerAlgo);
        echo '</pre>';
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GetUsersLolController.php',
            'result' => $this->predictifChoiceGamer('SOLO', 101, 'MID', $queryMe, $queryInfosPersoLol, false, [], [], $dm, $queryPlayerAlgo, $queryComplementary),
        ]);
    }

    
    public function predictifChoiceGamer($role, $champion, $lane, $arrayMyProfil, $arrayProfil, $isStats, $arrayComplementaire, $arrayResult, $dm, $queryPlayerAlgo, $queryComplementary){
       
         $timeStampResult = [];
         $arrayNonComplementaire = [];
         $relance = false;
         $resultHighScore = false;
         $arrayImportant = [];
         $arrayHightScore = [];
         $arrayCompo = [];
         $timeStamp = '';
         $data = $queryComplementary ? $queryComplementary : $arrayProfil;
 
         for ($i = 0; $i < count($arrayMyProfil); $i++) {
             $arrayTimeStamp = [$arrayMyProfil[$i]->{'timeStamp'}];
             array_push($timeStampResult, $arrayTimeStamp[0]);
         }
 
         if ($timeStampResult) $timeStamp = array_sum($timeStampResult) / count($timeStampResult);
         if ($timeStamp) array_push($arrayCompo, ["pseudo" => "azerty", "timeStamp" => $timeStamp, "role" => $role, "lane" => $lane, "champion" => $champion]);
 
         for ($p = 0; $p < count($data); $p++) {
             if ( $queryPlayerAlgo ? $data[$p]->{'pseudo'} === $queryPlayerAlgo[0]->{'pseudo'} : $data[$p]->{'pseudo'} === 'azerty') {
                array_push($arrayNonComplementaire, $data[$p]);
             } else {
             if ($data[$p]->{'role'} === $role) {
                 $data[$p]->{'score'} = 0;
                 array_push($arrayNonComplementaire, $data[$p]);
             } else {
                 if ($data[$p]->{'lane'} === $lane) {
                     $data[$p]->{'score'} = 0;
                     array_push($arrayNonComplementaire, $data[$p]);
                 } else {
                     if ($data[$p]->{'champion'} === $champion) {
                         $data[$p]->{'score'} = 0;
                         array_push($arrayNonComplementaire, $data[$p]);
                     } else { 
                         if ($data[$p]->{'score'} < 100) {
                        $testtime = date('m/d/Y H:i:s', substr($timeStamp, 0, 10));
                        $dateDepartTimestamp = strtotime($testtime);
                        $dateMin = date('H:i:s', strtotime('-'. 30 .'minute', $dateDepartTimestamp));
                        $dateMax = date('H:i:s', strtotime('+'. 30 .'minute', $dateDepartTimestamp));
                        $timeStampPlayers = date('H:i:s', substr($data[$p]->{'timeStamp'}, 0, 10));
                        if ($dateMin < $timeStampPlayers && $timeStampPlayers < $dateMax) {
                            $data[$p]->{'score'} = $data[$p]->{'score'} + 30;
                            array_push($arrayComplementaire, $data[$p]);
                         } else {
                            $dateMin = date('H:i:s', strtotime('-'. 60 .'minute', $dateDepartTimestamp));
                            $dateMax = date('H:i:s', strtotime('+'. 60 .'minute', $dateDepartTimestamp));
                            $timeStampPlayers = date('H:i:s', substr($data[$p]->{'timeStamp'}, 0, 10));
                            if ($dateMin < $timeStampPlayers && $timeStampPlayers < $dateMax) {
                                $data[$p]->{'score'} = $data[$p]->{'score'} + 20;
                                array_push($arrayComplementaire, $data[$p]);
                            } else {
                                array_push($arrayNonComplementaire, $data[$p]);
                            }
                        }
                     } else {
                         array_push($arrayComplementaire, $data[$p]);
                     }
                   }
                 }
             }
            }
         }
         
         if ($isStats) {
              // return $this->kda($arrayComplementaire);
            } else {
               if (count($arrayComplementaire) > 1) {
                   for ($v = 0; $v < count($arrayComplementaire); $v++) {
                    if ($arrayComplementaire[$v]->{'score'} >= 100) {
                         $resultHighScore = true;
                         array_push($arrayHightScore, $arrayComplementaire[$v]);
                      } else {
                         $relance = true;
                         array_push($arrayImportant, $arrayComplementaire[$v]);
                      }
                   }
                   } else {
                       if (!$arrayResult) {
                            array_push($arrayResult, $arrayComplementaire);
                       }
               }
            }
            if ($relance && !$arrayResult) {
                return $this->predictifChoiceGamer($role, $champion, $lane, $arrayMyProfil, $arrayImportant, [], [], $arrayResult, false, $dm, $queryPlayerAlgo, $queryComplementary);
            }
            if ($resultHighScore && !$arrayResult) {
                $high = array_rand($arrayHightScore, 1);
                array_push($arrayResult, $arrayHightScore[$high]);                
            }
            echo '<pre>';
            var_dump($arrayComplementaire);
            echo '</pre>';
            if ($arrayComplementaire) {
                $dm->createQueryBuilder(Complementary::class)
                ->remove()
                ->getQuery()
                ->execute();
                for ($c = 0; $c < count($arrayComplementaire); $c++) {
                    $pseudo = $arrayComplementaire[$c]->{'pseudo'};
                    $gameId = $arrayComplementaire[$c]->{'gameId'};
                    $laneResult = $arrayComplementaire[$c]->{'lane'};
                    $roleResult = $arrayComplementaire[$c]->{'role'};
                    $championResult = $arrayComplementaire[$c]->{'champion'};
                    $season = $arrayComplementaire[$c]->{'season'};
                    $score = $arrayComplementaire[$c]->{'score'};
                    $timeStampRes = $arrayComplementaire[$c]->{'timeStamp'};

                        $array = new Complementary();
                        $array->setGameId($gameId);
                        $array->setPseudo($pseudo);
                        $array->setRole($roleResult);
                        $array->setLane($laneResult);
                        $array->setChampion($championResult);
                        $array->setSeason($season);
                        $array->setTimeStamp($timeStampRes);
                        $array->setScore($score);
                        
                        $dm->persist($array);
                }
                $dm->persist($array); 
            }
            echo '<pre>';
            var_dump($arrayComplementaire);
            echo '</pre>';
         if ($arrayResult) {
            $dm->createQueryBuilder(PlayerAlgo::class)
                ->remove()
                ->getQuery()
                ->execute();
             $pseudo = $arrayResult[0]->{'pseudo'};
             $array = new PlayerAlgo();
             $array->setPseudo($pseudo);
             $dm->persist($array);
         }
         $dm->flush();
         return $arrayResult;
     }
}