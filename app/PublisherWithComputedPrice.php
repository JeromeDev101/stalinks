<?php

namespace App;

use App\Models\Publisher;

class PublisherWithComputedPrice extends Publisher
{
    protected $appends = ['computed_price'];
}
