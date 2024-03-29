<?php

namespace App\Containers\Constructor\Template\Tasks\Template;

use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Dto\LanguageDto;
use App\Ship\Parents\Dto\PageDto;
use App\Ship\Parents\Dto\TemplateDto;
use App\Ship\Parents\Dto\ThemeDto;
use App\Ship\Parents\Models\TemplateInterface;
use App\Ship\Parents\Repositories\LanguageRepositoryInterface;
use App\Ship\Parents\Repositories\PageRepositoryInterface;
use App\Ship\Parents\Repositories\TemplateRepositoryInterface;
use App\Ship\Parents\Repositories\ThemeRepositoryInterface;
use App\Ship\Parents\Tasks\Task;
use Exception;
use Illuminate\Support\Facades\Storage;

class CreateTemplateTask extends Task implements CreateTemplateTaskInterface
{
    public function __construct(
        private readonly ThemeRepositoryInterface    $themeRepository,
        private readonly TemplateRepositoryInterface $templateRepository,
        private readonly LanguageRepositoryInterface $languageRepository,
        private readonly PageRepositoryInterface     $pageRepository,
    )
    {
    }

    /**
     * @param \App\Ship\Parents\Dto\TemplateDto $data
     * @return int
     * @throws \App\Ship\Exceptions\CreateResourceFailedException
     */
    public function run(TemplateDto $data): int
    {
        try {
            if ($data->getType() === TemplateInterface::PAGE_TYPE && $data->getPage()?->getName() === null) {
                $pageDto = $this->getPageDto($data->getPage()?->getId() ?? $data->getPageId());
                $data
                    ->setPage($pageDto)
                    ->setPageId($pageDto->getId())
                    ->setChildPageId($pageDto->getChildPage()?->getId());
            }

            if ($data->getName() === null) {
                $name = match ($data->getType()) {
                    default => $data->getPage()?->getName(),
                    TemplateInterface::MENU_TYPE, TemplateInterface::BASE_TYPE => ucfirst($data->getType()),
                    TemplateInterface::CSS_TYPE, TemplateInterface::JS_TYPE => strtoupper($data->getType())
                };
                $data->setName($name);
            }

            if ($data->getTheme()?->getDirectory() === null) {
                $themDto = $this->getThemeDto($data->getTheme()?->getId() ?? $data->getThemeId());
                $data->setTheme($themDto);
            }

            if ($data->getLanguage()?->getId() !== null && $data->getLanguage()?->getShortName() === null) {
                $languageDto = $this->getLanguageDto($data->getLanguage()?->getId());
                $data->setLanguage($languageDto);
            }

            $languageShortName = $data->getLanguage()?->getShortName();
            $themeDirectory    = $data->getTheme()?->getDirectory();
            $fileName          = $data->getName() . ($languageShortName ? '-' . $languageShortName : '');

            $files = collect([
                'common'  => $this->createTemplateFile($data->getType(), $fileName . '-common', $themeDirectory),
                'element' => null,
                'preview' => null,
            ]);

            if ($data->getChildPageId() !== null || $data->getChildPage()?->getId() !== null) {
                $element = $this->createTemplateFile($data->getType(), $fileName . '-element', $themeDirectory);
                $preview = $this->createTemplateFile($data->getType(), $fileName . '-preview', $themeDirectory);
                $files->put('element', $element);
                $files->put('preview', $preview);
            }

            if (in_array($data->getType(), [TemplateInterface::MENU_TYPE, TemplateInterface::WIDGET_TYPE], true)) {
                $element = $this->createTemplateFile($data->getType(), $fileName . '-element', $themeDirectory);
                $files->put('element', $element);
            }

            $insert = [
                'type'               => $data->getType(),
                'name'               => $data->getName(),
                'theme_id'           => $data->getTheme()?->getId(),
                'page_id'            => in_array($data->getType(), [TemplateInterface::PAGE_TYPE, TemplateInterface::WIDGET_TYPE], true) ? $data->getPage()?->getId() : null,
                'child_page_id'      => $data->getType() === TemplateInterface::PAGE_TYPE ? $data->getPage()?->getChildPage()?->getId() : null,
                'language_id'        => $data->getLanguage()?->getId(),
                'parent_template_id' => $data->getTemplate()?->getId(),
                'common_filepath'    => $files->get('common'),
                'element_filepath'   => $files->get('element'),
                'preview_filepath'   => $files->get('preview'),
            ];

            /**
             * @var \App\Ship\Parents\Models\TemplateInterface $template
             */
            $template = $this->templateRepository->create($insert);

            return $template->id;

        } catch (Exception) {
            throw new CreateResourceFailedException();
        }
    }

    /**
     * @param int $pageId
     * @return \App\Ship\Parents\Dto\PageDto
     */
    private function getPageDto(int $pageId): PageDto
    {
        /**
         * @var \App\Ship\Parents\Models\PageInterface $page
         */
        $page = $this->pageRepository->find($pageId);

        $pageDto = (new PageDto())
            ->setId($page->id)
            ->setName($page->name)
            ->setType($page->type)
            ->setParentPageId($page->parent_page_id)
            ->setActive($page->active)
            ->setCreateAt($page->created_at)
            ->setUpdateAt($page->updated_at);

        if ($childPage = $page->child_page) {
            $childPageDto = (new PageDto())
                ->setId($childPage->id)
                ->setName($childPage->name)
                ->setType($childPage->type)
                ->setParentPageId($childPage->parent_page_id)
                ->setActive($childPage->active)
                ->setCreateAt($childPage->created_at)
                ->setUpdateAt($childPage->updated_at);

            $pageDto->setChildPage($childPageDto);
        }

        return $pageDto;
    }

    /**
     * @param int $themeId
     * @return \App\Ship\Parents\Dto\ThemeDto
     */
    private function getThemeDto(int $themeId): ThemeDto
    {
        /**
         * @var \App\Ship\Parents\Models\ThemeInterface $theme
         */
        $theme = $this->themeRepository->find($themeId);

        return (new ThemeDto())
            ->setId($theme->id)
            ->setName($theme->name)
            ->setDirectory($theme->directory)
            ->setActive($theme->active)
            ->setCreateAt($theme->created_at)
            ->setUpdateAt($theme->updated_at);
    }

    /**
     * @param int|null $languageId
     * @return \App\Ship\Parents\Dto\LanguageDto
     */
    private function getLanguageDto(?int $languageId): LanguageDto
    {
        /**
         * @var \App\Ship\Parents\Models\LanguageInterface $language
         */
        $language = $this->languageRepository->find($languageId);

        return (new LanguageDto())
            ->setId($language->id)
            ->setName($language->name)
            ->setShortName($language->short_name)
            ->setIsActive($language->active)
            ->setCreateAt($language->created_at)
            ->setUpdateAt($language->updated_at);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $themeDirectory
     * @return string
     */
    private function createTemplateFile(string $type, string $name, string $themeDirectory): string
    {
        [$folder, $fileType] = match ($type) {
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

        $name = preg_replace('/[^A-Za-z\d\-]/', '', ucwords(strtolower($name)));
        $name = lcfirst(str_replace(' ', '', $name));

        $path = implode('/', [
            $themeDirectory,
            $folder,
            $name . $fileType,
        ]);

        if (Storage::disk('template')->exists($path)) {
            return $this->createTemplateFile($type, $name . '-copy', $themeDirectory);
        }

        Storage::disk('template')->put($path, '');

        return $name;
    }
}

