<?php

namespace App\Containers\Constructor\Seo\Models;

use App\Containers\Constructor\Language\Models\Language;
use App\Containers\Constructor\Language\Models\LanguageInterface;
use App\Containers\Constructor\Page\Models\Page;
use App\Containers\Constructor\Page\Models\PageField;
use App\Containers\Constructor\Page\Models\PageFieldInterface;
use App\Containers\Constructor\Page\Models\PageInterface;
use App\Ship\Parents\Models\Model;

class Seo extends Model implements SeoInterface
{
    protected $table = 'seo';

    protected $fillable = [
        'page_id',
        'page_field_id',
        'language_id',
        'case_type',
        'static',
        'active',
    ];

    protected $casts = [
        'page_id'       => 'integer',
        'page_field_id' => 'integer',
        'language_id'   => 'integer',
        'case_type'     => 'string',
        'static'        => 'boolean',
        'active'        => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'SeoLinks';

    /**
     * @return \Illuminate\Database\Eloquent\Model|\App\Containers\Constructor\Page\Models\PageInterface
     */
    public function getPageAttribute(): \Illuminate\Database\Eloquent\Model|PageInterface
    {
        return $this->hasOne(Page::class, 'id', 'page_id')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\App\Containers\Constructor\Page\Models\PageInterface
     */
    public function getPageFieldAttribute(): \Illuminate\Database\Eloquent\Model|PageFieldInterface
    {
        return $this->hasOne(PageField::class, 'id', 'page_field_id')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\App\Containers\Constructor\Language\Models\LanguageInterface
     */
    public function getLanguageAttribute(): \Illuminate\Database\Eloquent\Model|LanguageInterface
    {
        return $this->hasOne(Language::class, 'id', 'language_id')->first();
    }
}
