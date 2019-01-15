<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserDislikeRepository")
 */
class UserDislike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dislikes;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userDislikes")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="userDislikes")
     */
    private $article;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return bool
     */
    public function getDislikes(): ?bool
    {
        return $this->dislikes;
    }
    /**
     * @param bool $dislikes
     * @return UserDislike
     */
    public function setDislikes(?bool $dislikes): self
    {
        $this->dislikes = $dislikes;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getUser()
{
    return $this->user;
}
    /**
     * @param mixed $user
     * @return UserDislike
     */
    public function setUser($user): self
{
    $this->user = $user;
    return $this;
}
    /**
     * @return mixed
     */
    public function getArticle()
{
    return $this->article;
}
    /**
     * @param mixed $article
     * @return UserDislike
     */
    public function setArticle($article): self
{
    $this->article = $article;
    return $this;
}
    public function __toString()
{
    return $this->dislikes;
}
}