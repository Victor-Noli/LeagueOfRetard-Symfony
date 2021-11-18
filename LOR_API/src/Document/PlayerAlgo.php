<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @MongoDB\Document(collection="playerAlgo")
     */

    class PlayerAlgo
    {
        /**
         * @MongoDB\Id
         */
        public $id;

        /**
         * @MongoDB\Field(type="string")
         */
        public $pseudo;

        public function setPseudo(string $pseudo): void
        {
            $this->pseudo = $pseudo;
        }

        public function setId(string $id): void
        {
            $this->id = $id;
        }

        public function getPseudo()
        {
            return $this->pseudo;
        }

        public function getId()
        {
            return $this->id;
        }

    }
