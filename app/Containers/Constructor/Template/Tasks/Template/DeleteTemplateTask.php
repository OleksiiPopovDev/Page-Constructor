<?php

namespace App\Containers\Constructor\Template\Tasks\Template;

use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Parents\Models\TemplateInterface;
use App\Ship\Parents\Repositories\TemplateRepositoryInterface;
use App\Ship\Parents\Repositories\ThemeRepositoryInterface;
use App\Ship\Parents\Tasks\Task;
use Exception;
use Illuminate\Support\Facades\Storage;

class DeleteTemplateTask extends Task implements DeleteTemplateTaskInterface
{
    public function __construct(
        private readonly ThemeRepositoryInterface    $themeRepository,
        private readonly TemplateRepositoryInterface $templateRepository
    )
    {
    }

    /**
     * @param int $id
     * @return void
     * @throws \App\Ship\Exceptions\DeleteResourceFailedException
     */
    public function run(int $id): void
    {
        try {
            /**
             * @var \App\Ship\Parents\Models\TemplateInterface $template
             */
            $template = $this->templateRepository->find($id);
            $theme    = $this->themeRepository->find($template->theme_id);

            $this->templateRepository->delete($template->id);

            [$folder, $type] = match ($template->type) {
                TemplateInterface::CSS_TYPE => [
                    config('constructor-template.folderName.css'),
                    config('constructor-template.fileType.css'),
                ],
                TemplateInterface::JS_TYPE => [
                    config('constructor-template.folderName.js'),
                    config('constructor-template.fileType.js'),
                ],
                default => [
                    config('constructor-template.folderName.view'),
                    config('constructor-template.fileType.view'),
                ],
            };

            $path = implode('/', [$theme->directory, $folder]);

            Storage::disk('template')->delete($path . '/' . $template->common_filepath . $type);
            Storage::disk('template')->delete($path . '/' . $template->element_filepath . $type);
            Storage::disk('template')->delete($path . '/' . $template->preview_filepath . $type);

        } catch (Exception) {
            throw new DeleteResourceFailedException();
        }
    }
}
