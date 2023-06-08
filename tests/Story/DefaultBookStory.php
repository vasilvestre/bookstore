<?php

namespace App\Tests\Story;

use App\Factory\BookFactory;
use Zenstruck\Foundry\Story;

final class DefaultBookStory extends Story
{
    public function build(): void
    {
        BookFactory::createOne();
    }
}
