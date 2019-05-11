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
     * @Assert\NotBlank
     */
    private $key;

//    /**
//     * @ORM\Column(type="datetime")
//     */
//    private $date_use;

    /**
     * One student has One code.
     * @ORM\OneToOne(targetEntity="JournalStudent", inversedBy="code")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
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
      //  $this->date_use = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));
        $this->student = $student;
    }

//    /**
//     * @return mixed
//     */
//    public function getDateUse()
//    {
//        return $this->date_use;
//    }
//
//    /**
//     * @param mixed $date_use
//     */
//    public function setDateUse($date_use): void
//    {
//        $this->date_use = $date_use;
//    }

    public function __construct()
    {
        $this->date_use = new \DateTime('0000-00-00');
    }

}
