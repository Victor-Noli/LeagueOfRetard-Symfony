<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @MongoDB\Document(collection="role")
     */

    class Role
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
         * @MongoDB\Field(type="float")
         */
        public $count;

        public function setRole(string $role): void
        {
            $this->role = $role;
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

        public function getRole()
        {
            return $this->role;
        }

        public function getCount()
        {
            return $this->count;
        }

    }
