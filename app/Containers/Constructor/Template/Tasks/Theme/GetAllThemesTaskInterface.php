<?php

namespace App\Containers\Constructor\Template\Tasks\Theme;

use Illuminate\Support\Collection;

interface GetAllThemesTaskInterface
{
    public function run(): Collection;
}