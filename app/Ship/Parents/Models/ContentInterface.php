<?php

namespace App\Ship\Parents\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @package App\Containers\Dashboard\Content\Models
 * @method static Builder query()
 * @property integer                                       $id
 * @property integer                                       $page_id
 * @property boolean                                       $active
 * @property Carbon                                        $created_at
 * @property Carbon                                        $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection $values
 * @property-read \App\Ship\Parents\Models\PageInterface   $page
 */
interface ContentInterface
{
}