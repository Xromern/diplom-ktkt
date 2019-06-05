<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalCodeRepository")
 * @UniqueEntity("$keyP")
 */
class JournalCode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $keyP;

    /**
     * @ORM\OneToOne(targetEntity="JournalStudent", mappedBy="code")
     */
    private $student;

    /**
     * @ORM\OneToOne(targetEntity="JournalTeacher", mappedBy="code")
     */
    private $teacher;

    /**
     * One group has One curator.
     * @ORM\OneToOne(targetEntity="User", inversedBy="code")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_use;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->keyP;
    }

    /**
     * @param mixed $keyP
     */
    public function setKeyP($keyP): void
    {
        $this->keyP= $keyP;
    }

    /**
     * @return mixed
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param mixed $student
     */
    public function setStudent($student): void
    {
        $this->student = $student;
    }

    /**
     * @return mixed
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     */
    public function setTeacher($teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return mixed
     */
    public function getDateUse()
    {
        return $this->date_use;
    }

    /**
     * @param mixed $date_use
     */
    public function setDateUse($date_use): void
    {
        $this->date_use = $date_use;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
    
    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function __toString()
    {
        return "";
    }

}
