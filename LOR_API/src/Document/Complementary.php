<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @MongoDB\Document(collection="complementary")
     */

    class Complementary
    {
        /**
         * @MongoDB\Id
         */
        public $id;

        /**
         * @MongoDB\Field(type="string")
         */
        public $pseudo;

        /**
         * @MongoDB\Field(type="string")
         */
        public $gameId;

        /**
         * @MongoDB\Field(type="string")
         */
        public $role;

        /**
         * @MongoDB\Field(type="string")
         */
        public $champion;

        /**
         * @MongoDB\Field(type="string")
         */
        public $lane;

        /**
         * @MongoDB\Field(type="string")
         */
        public $season;

        /**
         * @MongoDB\Field(type="string")
         */
        public $timeStamp;

        /**
         * @MongoDB\Field(type="string")
         */
        public $score;

        public function setPseudo(string $pseudo): void
        {
            $this->pseudo = $pseudo;
        }

        public function setId(string $id): void
        {
            $this->id = $id;
        }

        public function setGameId(string $gameId): void
        {
            $this->gameId = $gameId;
        }

        public function setRole(string $role): void
        {
            $this->role = $role;
        }

        public function setChampion(string $champion): void
        {
            $this->champion = $champion;
        }

        public function setLane(string $lane): void
        {
            $this->lane = $lane;
        }

        public function setSeason(string $season): void
        {
            $this->season = $season;
        }

        public function setTimeStamp(string $timeStamp): void
        {
            $this->timeStamp = $timeStamp;
        }

        public function setScore(string $score): void
        {
            $this->score = $score;
        }

        public function getPseudo()
        {
            return $this->pseudo;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getGameId()
        {
            return $this->gameId;
        }

        public function getRole()
        {
            return $this->role;
        }

        public function getChampion()
        {
            return $this->champion;
        }

        public function getLane()
        {
            return $this->lane;
        }

        public function getSeason()
        {
            return $this->season;
        }

        public function getTimeStamp()
        {
            return $this->timeStamp;
        }

        public function getScore()
        {
            return $this->score;
        }

    }
