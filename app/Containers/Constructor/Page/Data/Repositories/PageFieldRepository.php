<?php

namespace App\Containers\Constructor\Page\Data\Repositories;

use App\Containers\Constructor\Page\Models\PageField;
use App\Ship\Parents\Repositories\Repository;

class PageFieldRepository extends Repository implements PageFieldRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        'name'   => 'like',
        'type' => '=',
        'placeholder' => '=',
        'mask'   => 'like',
        'values'   => 'like',
        'action' => '=',
    ];


    public function model(): string
    {
        return PageField::class;
    }
}