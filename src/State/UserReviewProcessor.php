<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Review;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserReviewProcessor implements ProcessorInterface
{
    public function __construct(private readonly Security $security, private readonly BookRepository $bookRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Review
    {
        $user = $this->security->getUser();

        $data->setReviewer($user);
        $data->setBook($this->bookRepository->find($uriVariables['bookId']));

        $this->entityManager->persist($data);

        return $data;
    }
}
