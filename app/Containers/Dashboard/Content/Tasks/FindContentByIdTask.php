<?php

namespace App\Containers\Dashboard\Content\Tasks;

use App\Containers\Constructor\Page\Data\Dto\PageDto;
use App\Containers\Constructor\Page\Data\Dto\PageFieldDto;
use App\Containers\Constructor\Page\Data\Repositories\PageRepositoryInterface;
use App\Containers\Constructor\Page\Models\PageFieldInterface;
use App\Containers\Constructor\Page\Models\PageInterface;
use App\Containers\Dashboard\Content\Data\Dto\ContentDto;
use App\Containers\Dashboard\Content\Data\Dto\ContentValueDto;
use App\Containers\Dashboard\Content\Data\Repositories\ContentRepositoryInterface;
use App\Containers\Dashboard\Content\Models\ContentInterface;
use App\Containers\Dashboard\Content\Models\ContentValueInterface;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Exception;
use Illuminate\Support\Collection;


class FindContentByIdTask extends Task implements FindContentByIdTaskInterface
{
    public function __construct(
        private ContentRepositoryInterface $contentRepository,
        private PageRepositoryInterface    $pageRepository
    )
    {
    }

    /**
     * @param int $id
     * @return \App\Containers\Dashboard\Content\Data\Dto\ContentDto
     * @throws \App\Ship\Exceptions\NotFoundException
     */
    public function run(int $id): ContentDto
    {
        try {
            /**
             * @var ContentInterface $content
             */
            $content = $this->contentRepository->find($id);
            /**
             * @var PageInterface $page
             */
            $page = $this->pageRepository->find($content->page_id);

            $values = $this->buildContentValuesDto($content->values);
            $page   = $this->buildPageDto($page);

            return (new ContentDto())
                ->setId($content->id)
                ->setPageId($content->page_id)
                ->setActive($content->active)
                ->setValues($values->toArray())
                ->setPage($page)
                ->setCreateAt($content->created_at)
                ->setUpdateAt($content->updated_at);

        } catch (Exception) {
            throw new NotFoundException();
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $values
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function buildContentValuesDto(Collection $values): Collection
    {
        return $values->map(static function (ContentValueInterface $contentValue) {
            return (new ContentValueDto())
                ->setId($contentValue->id)
                ->setContentId($contentValue->content_id)
                ->setLanguageId($contentValue->language_id)
                ->setPageFieldId($contentValue->page_field_id)
                ->setValue($contentValue->value)
                ->setCreateAt($contentValue->created_at)
                ->setUpdateAt($contentValue->updated_at);
        });
    }

    /**
     * @param \App\Containers\Constructor\Page\Models\PageInterface $page
     * @return \App\Containers\Constructor\Page\Data\Dto\PageDto
     */
    private function buildPageDto(PageInterface $page): PageDto
    {
        $fields = $this->buildPageFieldsDto($page->fields);

        return (new PageDto())
            ->setId($page->id)
            ->setActive($page->active)
            ->setName($page->name)
            ->setFields($fields->toArray())
            ->setParentPageId($page->parent_page_id)
            ->setType($page->type)
            ->setCreateAt($page->created_at)
            ->setUpdateAt($page->updated_at);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $pageFields
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function buildPageFieldsDto(Collection $pageFields): Collection
    {
        return $pageFields->map(static function (PageFieldInterface $pageField) {
            return (new PageFieldDto())
                ->setId($pageField->id)
                ->setType($pageField->type)
                ->setName($pageField->name)
                ->setActive($pageField->active)
                ->setValues($pageField->values)
                ->setMask($pageField->mask)
                ->setPageId($pageField->page_id)
                ->setPlaceholder($pageField->placeholder)
                ->setCreateAt($pageField->created_at)
                ->setUpdateAt($pageField->updated_at);
        });
    }
}