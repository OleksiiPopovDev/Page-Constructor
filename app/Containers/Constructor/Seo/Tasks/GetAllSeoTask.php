<?php

namespace App\Containers\Constructor\Seo\Tasks;

use App\Ship\Parents\Dto\LanguageDto;
use App\Ship\Parents\Dto\PageDto;
use App\Ship\Parents\Dto\PageFieldDto;
use App\Ship\Parents\Dto\SeoDto;
use App\Ship\Parents\Dto\SeoLinkDto;
use App\Ship\Parents\Models\SeoInterface;
use App\Ship\Parents\Models\SeoLinkInterface;
use App\Ship\Parents\Repositories\SeoRepositoryInterface;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Collection;

class GetAllSeoTask extends Task implements GetAllSeoTaskInterface
{
    public function __construct(
        private readonly SeoRepositoryInterface $repository
    )
    {
    }

    public function run(): Collection
    {
        return $this->repository->all()->collect()->map(static function (SeoInterface $seo) {

            $page    = $seo->page;
            $pageDto = (new PageDto())
                ->setId($page->id)
                ->setName($page->name)
                ->setType($page->type)
                ->setActive($page->active)
                ->setCreateAt($page->created_at)
                ->setUpdateAt($page->updated_at);

            $field    = $seo->pageField;
            $fieldDto = (new PageFieldDto())
                ->setId($field->id)
                ->setPageId($field->page_id)
                ->setType($field->type)
                ->setName($field->name)
                ->setPlaceholder($field->placeholder)
                ->setMask($field->mask)
                ->setValues($field->values)
                ->setActive($field->active)
                ->setCreateAt($field->created_at)
                ->setUpdateAt($field->updated_at);

            $language    = $seo->language;
            $languageDto = (new LanguageDto())
                ->setId($language->id)
                ->setName($language->name)
                ->setShortName($language->short_name)
                ->setIsActive($language->active)
                ->setCreateAt($language->created_at)
                ->setUpdateAt($language->updated_at);

            $links = $seo->links->map(static function (SeoLinkInterface $seoLink) {
                return (new SeoLinkDto())
                    ->setId($seoLink->id)
                    ->setSeoId($seoLink->seo_id)
                    ->setContentId($seoLink->content_id)
                    ->setLink($seoLink->link)
                    ->setCreateAt($seoLink->created_at)
                    ->setUpdateAt($seoLink->updated_at);
            })->toArray();

            return (new SeoDto())
                ->setId($seo->id)
                ->setPageId($seo->page_id)
                ->setPageFieldId($seo->page_field_id)
                ->setLanguageId($seo->language_id)
                ->setCaseType($seo->case_type)
                ->setStatic($seo->static)
                ->setActive($seo->active)
                ->setPage($pageDto)
                ->setField($fieldDto)
                ->setLanguage($languageDto)
                ->setLinks($links)
                ->setCreateAt($seo->created_at)
                ->setUpdateAt($seo->updated_at);
        });
    }
}
