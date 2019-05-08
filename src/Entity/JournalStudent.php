<?php

namespace App\Entity;

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
     * Many subject have one teacher. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalSubject", inversedBy="students")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;

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
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
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


}
