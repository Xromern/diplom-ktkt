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
     * @ORM\Column(type="string", length=2)
     */
    private $mark;

    /**
     * Many mark have one student. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalStudent", inversedBy="marks")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * Many mark have one group. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalGroup", inversedBy="marks")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * Many mark have one date. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalDateMark", inversedBy="marks")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $dateMark;

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
    public function getDateMark()
    {
        return $this->dateMark;
    }

    /**
     * @param mixed $dateMark
     */
    public function setDateMark($dateMark): void
    {
        $this->dateMark = $dateMark;
    }

}
