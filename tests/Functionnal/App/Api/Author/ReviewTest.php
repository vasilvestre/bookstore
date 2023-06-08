<?php

namespace App\Tests\Functionnal\App\Api\Author;

use App\Factory\AuthorFactory;
use App\Factory\BookFactory;
use App\Factory\ReviewFactory;
use App\Factory\UserFactory;
use App\Story\DefaultBookStory;
use App\Story\DefaultReviewsStory;
use App\Tests\AbstractTest;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReviewTest extends AbstractTest
{
    use ResetDatabase;
    use Factories;

    public function testAuthorGetReviews(): void
    {
        $author = AuthorFactory::new()->create();
        $book = BookFactory::new(['authors' => [$author]]);
        ReviewFactory::createMany(50, [
            'book' => $book,
            'reviewer' => UserFactory::randomOrCreate(),
        ]);
        $token = $this->getToken(['email' => $author->getAccount()->getEmail(), 'password' => 'password']);
        static::createClientWithCredentials($token)->request('GET', '/books/1/reviews');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/books/1/reviews']);
    }

    public function testAuthorGetReviewFind(): void
    {
        BookFactory::new(['isbn' => 193847625, 'authors' => [
            AuthorFactory::new(['lastname' => 'hooper', 'firstname' => 'grace'])],
        ])->create();
        ReviewFactory::createMany(50, [
            'book' => BookFactory::find(['isbn' => 193847625]),
            'reviewer' => UserFactory::randomOrCreate(),
        ]);
        $token = $this->getToken(['email' => AuthorFactory::find(['lastname' => 'hooper'])
            ->getAccount()->getEmail(), 'password' => 'password']);
        static::createClientWithCredentials($token)->request('GET',
            sprintf('/books/%s/reviews', BookFactory::find(['isbn' => 193847625])->getId())
        );
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' =>
            sprintf('/books/%s/reviews', BookFactory::find(['isbn' => 193847625])->getId())]);
    }

    public function testAuthorGetReviewFirst(): void
    {
        BookFactory::new(['authors' => [AuthorFactory::new()]])->create();
        ReviewFactory::createMany(50, [
            'book' => BookFactory::first(),
            'reviewer' => UserFactory::randomOrCreate(),
        ]);
        $token = $this->getToken(['email' => UserFactory::first()->getEmail(), 'password' => 'password']);
        static::createClientWithCredentials($token)->request('GET', sprintf('/books/%s/reviews', BookFactory::first()->getId()));
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => sprintf('/books/%s/reviews', BookFactory::first()->getId())]);
        $this->assertJsonContains(['hydra:totalItems' => 50]);
    }

    public function testAuthorGetReviewsStory(): void
    {
        DefaultReviewsStory::load();
        $token = $this->getToken(['email' => UserFactory::random()->getEmail(), 'password' => 'password']);
        static::createClientWithCredentials($token)->request('GET',
            sprintf(
                '/books/%s/reviews',
                DefaultBookStory::get(DefaultBookStory::THE_FAST_TRACK)->getId()
            )
        );
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => sprintf(
                '/books/%s/reviews',
                DefaultBookStory::get(DefaultBookStory::THE_FAST_TRACK)->getId()
            )
        ]);
    }
}
