<?php

namespace App\Containers\Constructor\Template\Actions;

use App\Containers\Constructor\Template\Data\Dto\ThemeDto;

interface FindThemeByIdActionInterface
{
    public function run(int $id): ThemeDto;
}