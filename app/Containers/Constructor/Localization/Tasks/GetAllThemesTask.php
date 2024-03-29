<?php

namespace App\Containers\Constructor\Localization\Tasks;

use App\Ship\Parents\Dto\ThemeDto;
use App\Ship\Parents\Models\ThemeInterface;
use App\Ship\Parents\Repositories\ThemeRepositoryInterface;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetAllThemesTask extends Task implements GetAllThemesTaskInterface
{
    public function __construct(private readonly ThemeRepositoryInterface $repository)
    {
    }

    public function run(): Collection
    {
        return $this->repository
            ->findByField('active', true)->collect()
            ->map(static function (ThemeInterface $theme) {
                return (new ThemeDto())
                    ->setId($theme->id)
                    ->setName($theme->name)
                    ->setActive($theme->active)
                    ->setDirectory($theme->directory)
                    ->setCreateAt($theme->created_at)
                    ->setUpdateAt($theme->updated_at);
            });
    }
}
