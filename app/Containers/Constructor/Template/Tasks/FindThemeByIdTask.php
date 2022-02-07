<?php

namespace App\Containers\Constructor\Template\Tasks;

use App\Containers\Constructor\Page\Data\Dto\PageDto;
use App\Containers\Constructor\Template\Data\Dto\TemplateDto;
use App\Containers\Constructor\Template\Data\Dto\ThemeDto;
use App\Containers\Constructor\Template\Data\Repositories\ThemeRepositoryInterface;
use App\Containers\Constructor\Template\Models\TemplateInterface;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class FindThemeByIdTask extends Task implements FindThemeByIdTaskInterface
{
    public function __construct(private ThemeRepositoryInterface $repository)
    {
    }

    /**
     * @param int $id
     * @return \App\Containers\Constructor\Template\Data\Dto\ThemeDto
     * @throws \App\Ship\Exceptions\NotFoundException
     */
    public function run(int $id): ThemeDto
    {
        try {
            /**
             * @var \App\Containers\Constructor\Template\Models\ThemeInterface $theme
             */
            $theme = $this->repository->find($id);

            $templates = $theme->templates->collect()->map(static function (TemplateInterface $template) {
                $pageDto = null;

                if ($template->type === 'page') {
                    $page    = $template->page;

                    if ($page !== null) {
                        $pageDto = (new PageDto())
                            ->setId($page->id)
                            ->setType($page->type)
                            ->setName($page->name)
                            ->setActive($page->active)
                            ->setCreateAt($page->created_at)
                            ->setUpdateAt($page->updated_at);
                    }
                }

                return (new TemplateDto())
                    ->setId($template->id)
                    ->setType($template->type)
                    ->setPage($pageDto)
                    ->setCreateAt($template->created_at)
                    ->setUpdateAt($template->updated_at);
            })->toArray();

            return (new ThemeDto())
                ->setId($theme->id)
                ->setName($theme->name)
                ->setActive($theme->active)
                ->setTemplates($templates)
                ->setCreateAt($theme->created_at)
                ->setUpdateAt($theme->updated_at);
        } catch (Exception) {
            throw new NotFoundException();
        }
    }
}
