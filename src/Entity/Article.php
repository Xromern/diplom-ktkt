<?php

namespace App\Entity;

use AppTestBundle\Entity\FunctionalTests\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @Vich\Uploadable
 */
class Article
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
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     *
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="article", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @ORM\ManyToMany(targetEntity="CategoryArticle", inversedBy="articles")
     * @ORM\JoinTable(name="articles_categories_list")
     */
    private $categories;

    /**
     * One article has many comments. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article", cascade={"persist", "remove" })
     */
    private $comments;

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories( $categories)
    {
         $this->categories[] = $categories;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return mixed
     */
    public function setImage($image)
    {
        $this->image = $image;
        $this->updatedAt = new \DateTime("now");

    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
        $this->updatedAt = new \DateTime("now");

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
        $this->updatedAt = new \DateTime("now");

    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments(Comment $comment): void
    {
        $this->comments[] = $comment;
    }


    public function __construct()
    {

        $this->created_at = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");

        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();

    }

    public function __toString(){
        return $this->title;
    }
}

