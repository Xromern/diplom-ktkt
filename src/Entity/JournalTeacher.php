<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\JournalTeacherRepository")
 */
class JournalTeacher
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * One Teacher has One Group.
     * @ORM\OneToOne(targetEntity="JournalGroup", mappedBy="curator")
     */
    private $group;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="JournalSubject", inversedBy="secondaryTeachers")
     * @ORM\JoinTable(name="subject_secondary_teachers")
     */
    private $subjects;

    /**
     * One date has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalSubject", mappedBy="mainTeacher")
     */
    private $mainSubjects;

    /**
     * One group has One curator.
     * @ORM\OneToOne(targetEntity="JournalCode", inversedBy="teacher")
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

    /**
     * @return mixed
     */
    public function getMainSubjects()
    {
        return $this->mainSubjects;
    }

    /**
     * @param mixed $mainSubjects
     */
    public function setMainSubjects($mainSubjects): void
    {
        $this->mainSubjects[] = $mainSubjects;
    }

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->mainSubjects = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
}
