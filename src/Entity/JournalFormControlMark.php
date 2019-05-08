<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalFormControlMarkRepository")
 */
class JournalFormControlMark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $mark;

    /**
     * Many marks have one student. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalStudent", inversedBy="formControlMarks")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * Many marks have one typeMark. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalFormControl", inversedBy="formControlMarks")
     * @ORM\JoinColumn(name="form_control_id", referencedColumnName="id")
     */
    private $formControl;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param mixed $mark
     */
    public function setMark($mark): void
    {
        $this->mark = $mark;
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
    public function getFormControl()
    {
        return $this->formControl;
    }

    /**
     * @param mixed $formControl
     */
    public function setFormControl($formControl): void
    {
        $this->formControl = $formControl;
    }


}
