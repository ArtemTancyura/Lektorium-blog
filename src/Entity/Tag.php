<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $text;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="tags")
     */
    private $article;

    public function getId()
    {
        return $this->id;
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }
    public function __toString(): string
    {
        return $this->text;
    }
}
