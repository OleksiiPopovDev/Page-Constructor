<?php

namespace App\Containers\Constructor\Template\Tasks\Theme;

use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Dto\ThemeDto;
use App\Ship\Parents\Models\ThemeInterface;
use App\Ship\Parents\Repositories\ThemeRepositoryInterface;
use App\Ship\Parents\Tasks\Task;
use Exception;
use Illuminate\Support\Facades\Storage;

class CreateThemeTask extends Task implements CreateThemeTaskInterface
{
    public function __construct(
        private readonly ThemeRepositoryInterface $repository
    )
    {
    }

    /**
     * @throws \App\Ship\Exceptions\CreateResourceFailedException
     */
    public function run(ThemeDto $data): ThemeDto
    {
        try {
            $directoryName = $this->createThemeTemplateDirectories($data->getName());
            $data->setDirectory($directoryName);

            /**
             * @var ThemeInterface $theme
             */
            $theme = $this->repository->create($data->toArray());

            return (new ThemeDto())
                ->setId($theme->id)
                ->setName($theme->name)
                ->setActive($theme->active)
                ->setCreateAt($theme->created_at)
                ->setUpdateAt($theme->updated_at);

        } catch (Exception) {
            throw new CreateResourceFailedException();
        }
    }

    /**
     * @param string $themeName
     * @return string
     */
    private function createThemeTemplateDirectories(string $themeName): string
    {
        $directoryName = preg_replace('/[^A-Za-z\d\-]/', '', ucwords(strtolower($themeName)));
        $directoryName = lcfirst(str_replace(' ', '', $directoryName));

        if (Storage::disk('template')->exists($directoryName) === true) {
            return $this->createThemeTemplateDirectories($themeName . '-copy');
        }

        Storage::disk('template')->makeDirectory($directoryName . '/' . config('constructor-template.folderName.css'));
        Storage::disk('template')->makeDirectory($directoryName . '/' . config('constructor-template.folderName.js'));
        Storage::disk('template')->makeDirectory($directoryName . '/' . config('constructor-template.folderName.view'));

        Storage::disk('template')->put('.gitignore', "*\n\r!templates/\n\r!.gitignore");

        return $directoryName;
    }
}

