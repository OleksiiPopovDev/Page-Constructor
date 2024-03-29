<?php

namespace App\Ship\Parents\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @package App\Containers\Dashboard\Configuration\Models
 * @method static Builder query()
 * @property integer     $id
 * @property integer     $menu_id
 * @property integer     $content_id
 * @property integer     $order
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 *
 * @property-read string $link
 * @property-read string $value
 * @property-read string $short_name
 */
interface ConfigurationMenuItemInterface
{
}