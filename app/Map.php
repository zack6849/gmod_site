<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Map
 *
 * @method static Builder|Map newModelQuery()
 * @method static Builder|Map newQuery()
 * @method static Builder|Map query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $friendly_name
 * @property string $image_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Map whereCreatedAt($value)
 * @method static Builder|Map whereFriendlyName($value)
 * @method static Builder|Map whereId($value)
 * @method static Builder|Map whereImageUrl($value)
 * @method static Builder|Map whereName($value)
 * @method static Builder|Map whereUpdatedAt($value)
 */
class Map extends Model
{
    protected $guarded = [];
}
