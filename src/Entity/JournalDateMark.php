<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalDateMarkRepository")
 */
class JournalDateMark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * One date has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalMark", mappedBy="date")
     */
    private $marks;

    /**
     * Many date have one typeMark. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalTypeMark", inversedBy="dates")
     * @ORM\JoinColumn(name="type_mark_id", referencedColumnName="id")
     */
    private $typeMark;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
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

    /**
     * @return mixed
     */
    public function getTypeMark()
    {
        return $this->typeMark;
    }

    /**
     * @param mixed $typeMark
     */
    public function setTypeMark($typeMark): void
    {
        $this->typeMark = $typeMark;
    }


}