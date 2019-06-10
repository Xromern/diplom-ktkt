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
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * One date has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalMark", mappedBy="dateMark")
     */
    private $marks;

    /**
     * Many mark have one group. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalSubject", inversedBy="dateMarks")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;

    /**
     * Many date have one typeMark. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalTypeMark", inversedBy="dates")
     * @ORM\JoinColumn(name="type_mark_id", referencedColumnName="id")
     */
    private $typeMark;

    /**
     * @ORM\Column(type="integer")
     */
    private $page;


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

        if( $this->date==null){
            $this->typeMark = null;
        }
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
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
     * @param mixed $typeMark
     */
    public function setTypeMark($typeMark): void
    {
        $this->typeMark = $typeMark;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }



}
