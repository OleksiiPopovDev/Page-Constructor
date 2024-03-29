<?php

namespace App\Ship\Parents\Repositories;

use App\Ship\Parents\Models\ConfigurationMenuInterface;
use App\Ship\Parents\Models\ConfigurationMenuItemInterface;
use App\Ship\Parents\Models\ContentInterface;
use App\Ship\Parents\Models\ContentValueInterface;
use App\Ship\Parents\Models\LanguageInterface;
use App\Ship\Parents\Models\PageInterface;
use App\Ship\Parents\Models\SeoInterface;
use App\Ship\Parents\Models\SeoLinkInterface;
use App\Ship\Parents\Models\TemplateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ConfigurationMenuRepository extends Repository implements ConfigurationMenuRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id'          => '=',
        'name'        => 'like',
        'active'      => '=',
        'template_id' => '=',
    ];

    public function model(): string
    {
        return ConfigurationMenuInterface::class;
    }

    /**
     * @param int                                  $languageId
     * @param int                                  $themeId
     * @param array|\Illuminate\Support\Collection $menuIds
     * @return \Illuminate\Database\Eloquent\Collection|array
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getLinkDataOfMenuItems(int $languageId, int $themeId, array|\Illuminate\Support\Collection $menuIds): Collection|array
    {
        return $this->makeModel()::query()
            ->select('cm.id', 'cm.template_id', 'cmi.content_id', 'sl.link', 's.active', 'cv.value', 'l.short_name')
            ->from(app(ConfigurationMenuInterface::class)->getTable(), 'cm')
            ->leftJoin(app(ConfigurationMenuItemInterface::class)->getTable() . ' AS cmi', 'cm.id', '=', 'cmi.menu_id')
            ->leftJoin(app(ContentInterface::class)->getTable() . ' AS c', 'c.id', '=', 'cmi.content_id')
            ->leftJoin(app(ContentValueInterface::class)->getTable() . ' AS cv', 'cv.content_id', '=', 'c.id')
            ->leftJoin(app(TemplateInterface::class)->getTable() . ' AS t', 't.id', '=', 'cm.template_id')
            ->rightJoin(app(SeoLinkInterface::class)->getTable() . ' AS sl', 'cmi.content_id', '=', 'sl.content_id')
            ->rightJoin(app(SeoInterface::class)->getTable() . ' AS s', 's.id', '=', 'sl.seo_id')
            ->leftJoin(app(LanguageInterface::class)->getTable() . ' AS l', 'l.id', '=', 'cv.language_id')
            ->where('cm.active', true)
            ->where('c.active', true)
            ->where('cv.language_id', $languageId)
            ->where(static function (Builder $query) {
                $query
                    ->where('t.language_id', DB::raw('cv.language_id'))
                    ->orWhere('t.language_id', null);
            })
            ->whereIn('t.id', collect($menuIds)->toArray())
            ->where('s.language_id', DB::raw('cv.language_id'))
            ->where('t.theme_id', $themeId)
            ->where('cv.page_field_id', DB::raw('s.page_field_id'))
            ->get();
    }

    /**
     * @param int $languageId
     * @return \Illuminate\Database\Eloquent\Collection|array
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getPossibleMenuItems(int $languageId): Collection|array
    {
        return $this->makeModel()::query()
            ->select('c.id', 'p.name', 'cv.value', 'sl.link')
            ->from(app(ContentInterface::class)->getTable(), 'c')
            ->rightJoin(app(ContentValueInterface::class)->getTable() . ' AS cv', 'c.id', '=', 'cv.content_id')
            ->rightJoin(app(PageInterface::class)->getTable() . ' AS p', 'p.id', '=', 'c.page_id')
            ->rightJoin(app(SeoLinkInterface::class)->getTable() . ' AS sl', 'c.id', '=', 'sl.content_id')
            ->rightJoin(app(SeoInterface::class)->getTable() . ' AS s', 's.id', '=', 'sl.seo_id')
            ->where('c.active', true)
            ->where('c.parent_content_id', null)
            ->where('cv.language_id', $languageId)
            ->where('s.language_id', DB::raw('cv.language_id'))
            ->where('cv.page_field_id', DB::raw('s.page_field_id'))
            ->get();
    }
}
