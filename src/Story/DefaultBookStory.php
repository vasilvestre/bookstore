<?php

namespace App\Story;

use App\Factory\BookFactory;
use Zenstruck\Foundry\Story;

final class DefaultBookStory extends Story
{
    public const THE_FAST_TRACK = 'thefasttrack';

    public function build(): void
    {
        $this->addState(
            self::THE_FAST_TRACK,
            BookFactory::createOne(['name' => 'The Fast Track'])
        );
    }
}
