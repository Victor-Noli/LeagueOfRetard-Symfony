<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @MongoDB\Document(collection="criteria")
     */

    class Criterias
    {
        /**
         * @MongoDB\Id
         */
        protected $id;

        /**
         * @MongoDB\Field(type="string")
         */
        protected $pseudo;

        /**
         * @MongoDB\Field(type="string")
         */
        protected $gameId;

        /**
         * @MongoDB\Field(type="string")
         */
        protected $kill;

        /**
         * @MongoDB\Field(type="string")
         */
        protected $death;

        /**
         * @MongoDB\Field(type="string")
         */
        protected $assist;


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

        public function setKill(string $kill): void
        {
            $this->kill = $kill;
        }

        public function setDeath(string $death): void
        {
            $this->death = $death;
        }

        public function setAssist(string $assist): void
        {
            $this->assist = $assist;
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

        public function getKill()
        {
            return $this->kill;
        }

        public function getDeath()
        {
            return $this->death;
        }

        public function getAssist()
        {
            return $this->assist;
        }

    }
