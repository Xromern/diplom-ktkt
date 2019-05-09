<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalGroupRepository")
 */
class JournalGroup
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * One group has One curator.
     * @ORM\OneToOne(targetEntity="JournalTeacher", inversedBy="group")
     * @ORM\JoinColumn(name="curator_id", referencedColumnName="id")
     */
    private $curator;

    /**
     * Many group have one specialty. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalSpecialty", inversedBy="groups")
     * @ORM\JoinColumn(name="specialty_id", referencedColumnName="id")
     */
    private $specialty;

    /**
     * One group has many students. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalStudent", mappedBy="group")
     */
    private $students;

    /**
     * One group has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalMark", mappedBy="group")
     */
    private $marks;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCurator()
    {
        return $this->curator;
    }

    /**
     * @param mixed $curator
     */
    public function setCurator($curator): void
    {
        $this->curator = $curator;
    }

    /**
     * @return mixed
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * @param mixed $specialty
     */
    public function setSpecialty($specialty): void
    {
        $this->specialty = $specialty;
    }

    /**
     * @return mixed
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param mixed $students
     */
    public function setStudents($students): void
    {
        $this->students[] = $students;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * @param mixed $marks
     */
    public function setMarks($marks): void
    {
        $this->marks = $marks;
    }

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }
}
