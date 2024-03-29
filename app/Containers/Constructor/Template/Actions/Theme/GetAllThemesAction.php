<?php

namespace App\Containers\Constructor\Template\Actions\Theme;

use App\Containers\Constructor\Template\Tasks\Theme\GetAllThemesTaskInterface;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Collection;

class GetAllThemesAction extends Action implements GetAllThemesActionInterface
{
    public function __construct(
        private readonly GetAllThemesTaskInterface $getAllThemeTask
    )
    {
    }

    public function run(): Collection
    {
        return $this->getAllThemeTask->run();
    }
}
