<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="background")
 */
class Background
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Image()
     */
    private $image;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $text;

    public function __construct()
    {
        $this->text = 'Enjoy my blog';
        $this->image = '77892475a745caa3a2fe21494cd534a9.jpeg';
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
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
}