<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @MongoDB\Document(collection="lane")
     */

    class Lane
    {
        /**
         * @MongoDB\Id
         */
        public $id;

        /**
         * @MongoDB\Field(type="string")
         */
        public $lane;

        /**
         * @MongoDB\Field(type="float")
         */
        public $count;

        public function setLane(string $lane): void
        {
            $this->lane = $lane;
        }

        public function setCount(string $count): void
        {
            $this->role = $count;
        }

        public function setId(string $id): void
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getLane()
        {
            return $this->lane;
        }

        public function getCount()
        {
            return $this->count;
        }

    }
