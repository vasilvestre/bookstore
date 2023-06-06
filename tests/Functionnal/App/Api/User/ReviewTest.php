<?php

namespace App\Tests\Functionnal\App\Api\User;

use App\Factory\BookFactory;
use App\Factory\UserFactory;
use App\Tests\AbstractTest;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReviewTest extends AbstractTest
{
    use ResetDatabase;
    use Factories;

    public function testUserLeaveAReview(): void
    {
        $book = BookFactory::createOne();
        $user = UserFactory::createOne();
        $token = $this->getToken(['email' => $user->getEmail(), 'password' => $user->getPassword()]);
        static::createClientWithCredentials($token)->request('POST', sprintf('/books/%s/reviews', $book->getId()), [
            'json' => [
                'content' => 'Lorem ipsum dolor sit amet',
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/reviews/1']);
    }
}
