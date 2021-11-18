<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @MongoDB\Document(collection="user")
     */

    class User
    {
        /**
         * @MongoDB\Id
         */
        public $id;

        /**
         * @MongoDB\Field(type="string")
         */
        public $role;

        /**
         * @MongoDB\Field(type="string")
         */
        public $lane;

        /**
         * @MongoDB\Field(type="string")
         */
        public $isStats;

        public function setRole(string $role): void
        {
            $this->role = $role;
        }

        public function setLane(string $lane): void
        {
            $this->role = $lane;
        }

        public function setIsStats(string $isStats): void
        {
            $this->isStats = $isStats;
        }

        public function setId(string $id): void
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getRole()
        {
            return $this->role;
        }

        public function getLane()
        {
            return $this->lane;
        }

        public function getIsStats()
        {
            return $this->isStats;
        }

    }
