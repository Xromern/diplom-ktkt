<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalFormControlRepository")
 */
class JournalFormControl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * One group has many students. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalFormControlMark", mappedBy="formControl")
     */
    private $formControlMark;

    /**
     * Many date have one typeMark. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JournalTypeFormControl", inversedBy="formControl")
     * @ORM\JoinColumn(name="type_form_control_id", referencedColumnName="id")
     */
    private $typeFormControl;


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
    public function getFormControlMark()
    {
        return $this->formControlMark;
    }

    /**
     * @param mixed $formControlMark
     */
    public function setFormControlMark($formControlMark): void
    {
        $this->formControlMark = $formControlMark;
    }

    /**
     * @return mixed
     */
    public function getTypeFormControl()
    {
        return $this->typeFormControl;
    }

    /**
     * @param mixed $typeFormControl
     */
    public function setTypeFormControl($typeFormControl): void
    {
        $this->typeFormControl = $typeFormControl;
    }


}
