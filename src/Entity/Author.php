<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[Get]
#[GetCollection]
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'authors')]
    private Collection $books;

    #[ORM\OneToOne(mappedBy: 'author', cascade: ['persist', 'remove'])]
    private ?User $account = null;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): self
    {
        // unset the owning side of the relation if necessary
        if (null === $account && null !== $this->account) {
            $this->account->setAuthor(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $account && $account->getAuthor() !== $this) {
            $account->setAuthor($this);
        }

        $this->account = $account;

        return $this;
    }
}
