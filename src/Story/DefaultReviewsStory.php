<?php

namespace App\Story;

use App\Factory\BookFactory;
use App\Factory\ReviewFactory;
use Zenstruck\Foundry\Story;

final class DefaultReviewsStory extends Story
{
    public function build(): void
    {
        ReviewFactory::createMany(50);
        ReviewFactory::createMany(10, [
            'book' => DefaultBookStory::get(DefaultBookStory::THE_FAST_TRACK),
        ]);
    }
}
