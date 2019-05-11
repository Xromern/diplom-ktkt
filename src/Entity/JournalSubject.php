<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Service;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalSubjectRepository")
 */
class JournalSubject
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
     * Many subject have one teacher. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalTeacher", inversedBy="subjects")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="JournalStudent", mappedBy="subjects")
     */
    private $students;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alis_en;

    public function getId(): ?int
    {
        return $this->id;
    }

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
        $this->alis_en = Service\Helper::createAlias($name);
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

    /**
     * @return mixed
     */
    public function getAlisEn()
    {
        return $this->alis_en;
    }

    /**
     * @param mixed $alis_en
     */
    private function setAlisEn($alis_en): void
    {
        $this->alis_en = $alis_en;
    }

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }
}
