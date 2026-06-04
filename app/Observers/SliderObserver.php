<?php

namespace App\Observers;

use App\Models\Slider;
use Illuminate\Support\Facades\Cache;

class SliderObserver
{
    public function saved(Slider $slider): void
    {
        Cache::forget('home_data');
    }
    public function deleted(Slider $slider): void
    {
        Cache::forget('home_data');
    }
}
