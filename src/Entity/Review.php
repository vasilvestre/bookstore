<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\ReviewRepository;
use App\State\UserReviewProcessor;
use Doctrine\ORM\Mapping as ORM;

#[Get]
#[GetCollection]
#[GetCollection(
    uriTemplate: '/books/{bookId}/reviews',
    uriVariables: [
        'bookId' => new Link(
            fromProperty: 'reviews', fromClass: Book::class
        ),
    ]
)]
#[Post(
    uriTemplate: '/books/{bookId}/reviews',
    uriVariables: [
        'bookId' => new Link(
            fromProperty: 'reviews', fromClass: Book::class
        ),
    ],
    security: 'is_granted("ROLE_USER")',
    processor: UserReviewProcessor::class,
)]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'reviews')]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reviewer = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getReviewer(): ?User
    {
        return $this->reviewer;
    }

    public function setReviewer(?User $reviewer): self
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
