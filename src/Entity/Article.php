<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="article")
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
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $shortText;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $longText;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLike", mappedBy="article", cascade={"remove"})
     */
    private $userLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserDislike", mappedBy="article", cascade={"remove"})
     */
    private $userDislikes;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="article", cascade={"persist"})
     * @ORM\JoinTable(name="article_tag")
     */
    private $tags;

    /**
     * @var Comments[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="article", cascade={"persist"}, cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Image()
     */
    private $image;

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
    
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortText(): ?string
    {
        return $this->shortText;
    }

    public function setShortText(string $shortText): self
    {
        $this->shortText = $shortText;

        return $this;
    }

    public function getLongText(): ?string
    {
        return $this->longText;
    }

    public function setLongText(string $longText): self
    {
        $this->longText = $longText;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(?User $author)
    {
        $this->author = $author;
        return $this;
    }

    public function addTag(?Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getTags()
    {
        return $this->tags;
    }

    /**
    * @return ArrayCollection|UserLike[]
    */
    public function getUserLikes()
    {
        return $this->userLikes;
    }
    public function addUserLike(UserLike $userLike): self
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes[] = $userLike;
            $userLike->setArticle($this);
        }
        return $this;
    }
    public function removeUserLike(UserLike $userLike): self
    {
        if ($this->userLikes->contains($userLike)) {
            $this->userLikes->removeElement($userLike);
            // set the owning side to null (unless already changed)
            if ($userLike->getArticle() === $this) {
                $userLike->setArticle(null);
            }
        }
        return $this;
    }

    /**
     * @return ArrayCollection|UserDislike[]
     */
    public function getUserDislikes()
    {
        return $this->userDislikes;
    }
    public function addUserDislike(UserLike $userDislike): self
    {
        if (!$this->userDislikes->contains($userDislike)) {
            $this->userDislikes[] = $userDislike;
            $userDislike->setArticle($this);
        }
        return $this;
    }
    public function removeUserDislike(UserDislike $userDislike): self
    {
        if ($this->userDislikes->contains($userDislike)) {
            $this->userDislikes->removeElement($userDislike);
            // set the owning side to null (unless already changed)
            if ($userDislike->getArticle() === $this) {
                $userDislike->setArticle(null);
            }
        }
        return $this;
    }

    public function getComment(): Collection
    {
        return $this->comments;
    }
    public function addComment(?Comments $comment): void
    {
        $comment->setArticle($this);
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }
    public function removeComment(Comments $comment): void
    {
        $comment->setArticle(null);
        $this->comments->removeElement($comment);
    }

}
