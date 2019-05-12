<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalCodeRepository")
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


}
