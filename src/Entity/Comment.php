<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments" )
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments" )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 15,
     *      max = 500,
     *      minMessage = "Ваш коментар повинен бути мінімум {{ limit }} символів",
     *      maxMessage = "Ваш коментар повинен бути максимум  {{ limit }} символів"
     * )
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $update_at;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $created_at;


    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;
    }


    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * @param $text
     * @throws \Exception
     */
    public function setText($text): void
    {
        $this->text = $text;
        $this->update_at =  new \DateTime("now");

    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param $update_at
     */
    public function setUpdateAt($update_at): void
    {
        $this->update_at = $update_at;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * Comment constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->created_at = new \DateTime("now");
        $this->update_at =  new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function __toString(){
        return $this->text;
    }
}
