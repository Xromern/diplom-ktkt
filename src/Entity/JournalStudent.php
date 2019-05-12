<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalStudentRepository")
 */
class JournalStudent
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
     * Many students have one group. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalGroup", inversedBy="students")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="JournalSubject", inversedBy="students")
     * @ORM\JoinTable(name="subject_students")
     */
    private $subjects;

    /**
     * One student has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalDateMark", mappedBy="student")
     */
    private $marks;

    /**
     * One student has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalFormControlMark", mappedBy="student")
     */
    private $formControlMarks;

    /**
     * One group has One curator.
     * @ORM\OneToOne(targetEntity="JournalCode", inversedBy="student")
     * @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     */
    private $code;

    //private $user;

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
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): void
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param mixed $subjects
     */
    public function setSubjects($subjects): void
    {
        $this->subjects[] = $subjects;
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

    /**
     * @return mixed
     */
    public function getFormControlMarks()
    {
        return $this->formControlMarks;
    }

    /**
     * @param mixed $formControlMarks
     */
    public function setFormControlMarks($formControlMarks): void
    {
        $this->formControlMarks = $formControlMarks;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
    }

}
