<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Rank
 *
 * @method static Builder|Rank newModelQuery()
 * @method static Builder|Rank newQuery()
 * @method static Builder|Rank query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $is_staff
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Rank whereCreatedAt($value)
 * @method static Builder|Rank whereId($value)
 * @method static Builder|Rank whereIsStaff($value)
 * @method static Builder|Rank whereName($value)
 * @method static Builder|Rank whereSortOrder($value)
 * @method static Builder|Rank whereUpdatedAt($value)
 */
class Rank extends Model
{
    protected $guarded = [];

    public function users(){
        return $this->hasMany(SteamUser::class);
    }

    public static function findOrCreate($rank_name){
        $rank = Rank::whereName($rank_name)->first();
        if($rank == null){
            $rank = Rank::create(['name' => $rank_name, 'friendly_name' => ucwords($rank_name)]);
        }
        return $rank;
    }
}
