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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="JournalTeacher", mappedBy="subjects")
     */
    private $secondaryTeachers;

    /**
     * @ORM\ManyToOne(targetEntity="JournalTeacher", inversedBy="subjects")
     * @ORM\JoinColumn(name="main_teacher_id", referencedColumnName="id")
     */
    private $mainTeacher;

    /**
     * @ORM\ManyToOne(targetEntity="JournalGroup", inversedBy="subjects")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @ORM\ManyToMany(targetEntity="JournalStudent", mappedBy="subjects")
     */
    private $students;

    /**
     * One date has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalDateMark", mappedBy="subject")
     */
    private $dateMarks;

    /**
     * One date has many marks. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JournalMark", mappedBy="subject")
     */
    private $marks;

    /**
     * @ORM\ManyToOne(targetEntity="JournalTypeFormControl", inversedBy="subjects")
     * @ORM\JoinColumn(name="type_form_control_id", referencedColumnName="id")
     */
    private $typeFormControl;

    /**
     * @ORM\ManyToOne(targetEntity="JournalGradingSystem", inversedBy="subjects")
     * @ORM\JoinColumn(name="grading_system_id", referencedColumnName="id")
     */
    private $gradingSystem;

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
    public function getSecondaryTeachers()
    {
        return $this->secondaryTeachers;
    }

    /**
     * @param mixed $secondaryTeachers
     */
    public function setSecondaryTeachers($secondaryTeachers): void
    {
        $this->secondaryTeachers[] = $secondaryTeachers;
    }

    /**
     * @return mixed
     */
    public function getMainTeacher()
    {
        return $this->mainTeacher;
    }

    /**
     * @param mixed $mainTeacher
     */
    public function setMainTeacher($mainTeacher): void
    {
        $this->mainTeacher = $mainTeacher;
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
    public function getDateMarks()
    {
        return $this->dateMarks;
    }

    /**
     * @param mixed $dateMarks
     */
    public function setDateMarks($dateMarks): void
    {
        foreach ($dateMarks as $item)
        $this->dateMarks[] = $item;
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

    /**
     * @return mixed
     */
    public function getGradingSystem()
    {
        return $this->gradingSystem;
    }

    /**
     * @param mixed $gradingSystem
     */
    public function setGradingSystem($gradingSystem): void
    {
        $this->gradingSystem = $gradingSystem;
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


    public function removeStudent($student)
    {
        //optionally add a check here to see that $group exists before removing it.
       $this->students->removeElement($student);
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->dateMarks= new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->secondaryTeachers = new ArrayCollection();
    }
}
