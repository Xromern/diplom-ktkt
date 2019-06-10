<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalMarkRepository")
 */
class JournalMark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $mark;

    /**
     * Many mark have one student. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalStudent", inversedBy="marks")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * Many mark have one date. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalDateMark", inversedBy="marks")
     * @ORM\JoinColumn(name="date_mark_id", referencedColumnName="id")
     */
    private $dateMark;

    /**
     * Many mark have one group. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalSubject", inversedBy="marks")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $missed = 0;

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
    public function getDateMark()
    {
        return $this->dateMark;
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
     * @param mixed $dateMark
     */
    public function setDateMark($dateMark): void
    {
        $this->dateMark = $dateMark;
    }

    /**
     * @return mixed
     */
    public function getMissed()
    {
        return $this->missed;
    }

    /**
     * @param mixed $missed
     */
    public function setMissed($missed): void
    {
        $this->missed = $missed;
    }


    public function __toString()
    {
        return "";
    }
}
