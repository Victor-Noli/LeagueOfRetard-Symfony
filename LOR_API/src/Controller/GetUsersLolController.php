<?php

namespace App\Controller;

use App\Document\Complementary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\InfosPersoLOL;
use App\Document\Criterias;
use App\Document\Lane;
use App\Document\PlayerAlgo;
use App\Document\Role;
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
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GetUsersLolController.php',
            'result' => $this->predictifChoiceGamer('SOLO', 101, 'MID', $queryMe, $queryInfosPersoLol, true, [], [], $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria),
        ]);
    }
    
    public function predictifChoiceGamer($role, $champion, $lane, $arrayMyProfil, $arrayProfil, $isStats, $arrayComplementaire, $arrayResult, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria){
       
         $timeStampResult = [];
         $arrayNonComplementaire = [];
         $arrayImportant = [];
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

              return $this->kda($arrayComplementaire, $role, $champion, $lane, $arrayMyProfil, $arrayImportant, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria);
            } else {
                return $this->toResult($arrayComplementaire, $arrayResult, $role, $champion, $lane, $arrayMyProfil, $arrayImportant, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria);
             
            }
     }

     public function toResult($arrayComplementaire, $arrayResult, $role, $champion, $lane, $arrayMyProfil, $arrayImportant, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria) {
         
        $relance = false;
        $resultHighScore = false;
        $arrayHightScore = [];
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
                if (!$arrayResult && $arrayComplementaire) {
                     array_push($arrayResult, $arrayComplementaire);
                }
        }
        if ($relance && !$arrayResult) {
            return $this->predictifChoiceGamer($role, $champion, $lane, $arrayMyProfil, $arrayImportant, [], [], $arrayResult, false, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria);
        }
        if ($resultHighScore && !$arrayResult) {
            $high = array_rand($arrayHightScore, 1);
            array_push($arrayResult, $arrayHightScore[$high]);                
        }
        
     return $this->result($arrayComplementaire, $dm, $arrayResult);
     }

     public function result($arrayComplementaire, $dm, $arrayResult) {
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
     if ($arrayResult) {
        $dm->createQueryBuilder(PlayerAlgo::class)
            ->remove()
            ->getQuery()
            ->execute();
         $pseudo = $arrayResult[0]->{'pseudo'};
         $array = new PlayerAlgo();
         $array->setPseudo($pseudo);
         $dm->persist($array);
     } else {
        $dm->createQueryBuilder(Complementary::class)
        ->remove()
        ->getQuery()
        ->execute();
        $dm->createQueryBuilder(PlayerAlgo::class)
            ->remove()
            ->getQuery()
            ->execute();
     }
     $dm->flush();
     return $arrayResult;
     }

     public function kda($arrayComplementaire, $role, $champion, $lane, $arrayMyProfil, $arrayImportant, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria) {

        $arrayGoodCriteria = [];
        $arrayResultKda = [];
        $arrayVeryHigh = [];
        $arrayHigh = [];
        $arrayMedium = [];
        $arrayLow = [];
        $newArrayComplementaire = [];
        for ($i = 0; $i < count($arrayComplementaire); $i++) {
            for ($v = 0; $v < count($queryCriteria); $v++) {
                if (ucfirst($arrayComplementaire[$i]->{'pseudo'}) === ucfirst($queryCriteria[$v]->{'pseudo'})) {
                    array_push($arrayGoodCriteria, $queryCriteria[$v]);
                }
            }
        }
        for ($p = 0; $p < count($arrayGoodCriteria); $p++) {
            $kda = ($arrayGoodCriteria[$p]->{'kill'} + $arrayGoodCriteria[$p]->{'assist'}) / $arrayGoodCriteria[$p]->{'death'};
            switch ($kda) {
                case ($kda >= 5):
                    $arrayGoodCriteria[$p]->{'kda'} = $kda;
                    array_push($arrayVeryHigh, $arrayGoodCriteria[$p]);
                    break;
                case ($kda >= 3 && $kda < 5):
                    $arrayGoodCriteria[$p]->{'kda'} = $kda;
                    array_push($arrayHigh, $arrayGoodCriteria[$p]);
                    break;
                case ($kda >= 1 && $kda < 3):
                    $arrayGoodCriteria[$p]->{'kda'} = $kda;
                    array_push($arrayMedium, $arrayGoodCriteria[$p]);
                    break;
                case ($kda < 1):
                    $arrayGoodCriteria[$p]->{'kda'} = $kda;
                    array_push($arrayLow, $arrayGoodCriteria[$p]);
                    break;
            }
            
        }

        switch (true) {
            case ($arrayVeryHigh):
                $array = array_rand($arrayVeryHigh, 1);
                array_push($arrayResultKda, $arrayVeryHigh[$array]); 
                break;
            case ($arrayHigh):
                $array = array_rand($arrayHigh, 1);
                array_push($arrayResultKda, $arrayHigh[$array]); 
                break;
            case ($arrayMedium):
                $array = array_rand($arrayMedium, 1);
                array_push($arrayResultKda, $arrayMedium[$array]); 
                break;
            case ($arrayLow):
                $array = array_rand($arrayLow, 1);
                array_push($arrayResultKda, $arrayLow[$array]); 
                break;
        }
        if ($arrayResultKda) {
        for ($a = 0; $a < count($arrayComplementaire); $a++) {
            if (ucfirst($arrayComplementaire[$a]->{'pseudo'}) !== ucfirst($arrayResultKda[0]->{'pseudo'})) {
                array_push($newArrayComplementaire, $arrayComplementaire[$a]);
            }
        }
        
       return $this->result($newArrayComplementaire, $dm, $arrayResultKda);
     } else {
        return $this->toResult($arrayComplementaire, $arrayResultKda, $role, $champion, $lane, $arrayMyProfil, $arrayImportant, $dm, $queryPlayerAlgo, $queryComplementary, $queryCriteria);
     }
    }

    /**
     * @Route("/get/user/overView/{user}", name="get_users_overView", methods={"GET","HEAD"})

     */
    public function overView(DocumentManager $dm, $user) {
        if ($user) {
        $role = $dm->getRepository(Role::class)->findBy([]);
        $lane = $dm->getRepository(Lane::class)->findBy([]);
        $query = $dm->getRepository(InfosPersoLOL::class)->findBy(['pseudo' => $user]);
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
    } else {
        $role = '';
        $lane = '';
        $query = [];
    }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GetUsersLolController.php',
            'role' => $role,
            'lane' => $lane,
            'countParty' => count($query),
        ]);
    }
}