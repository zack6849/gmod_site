<?php

namespace App;

use App\Jobs\GetOnlineUsers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use SteamCondenser\Community\SteamId;
use SteamCondenser\Exceptions\SteamCondenserException;
use SteamCondenser\Servers\SteamPlayer;

/**
 * App\SteamUser
 *
 * @method static Builder|SteamUser newModelQuery()
 * @method static Builder|SteamUser newQuery()
 * @method static Builder|SteamUser query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $steamid
 * @property string|null $avatar_url
 * @property string|null $username
 * @property string|null $last_seen_at
 * @property string|null $last_ingame_at
 * @property string|null $last_seen_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SteamUser whereAvatarUrl($value)
 * @method static Builder|SteamUser whereCreatedAt($value)
 * @method static Builder|SteamUser whereId($value)
 * @method static Builder|SteamUser whereLastIngameAt($value)
 * @method static Builder|SteamUser whereLastSeenAddress($value)
 * @method static Builder|SteamUser whereLastSeenAt($value)
 * @method static Builder|SteamUser whereSteamid($value)
 * @method static Builder|SteamUser whereUpdatedAt($value)
 * @method static Builder|SteamUser whereUsername($value)
 */
class SteamUser extends Model
{

    protected $guarded = [];
    private $profile;

    public function rank(){
        return $this->belongsTo(Rank::class);
    }

    public function isStaff(){
        if($this->rank == null){
            return false;
        }
        return $this->rank->is_staff;
    }

    public static function findOrCreate($steam_id){
        $steam_user = SteamUser::whereSteamid($steam_id)->first();
        //if we failed to find a user, create him.
        if($steam_user == null){
            SteamId::clearCache();
            try{
                $profile = SteamId::getFromSteamId($steam_id);
            }catch (SteamCondenserException $exception){
                return null;
            }
            $community_id = SteamId::convertSteamIdToCommunityId($steam_id);
            $steam_id3 = SteamId::convertCommunityIdToSteamId3($community_id);
            $attributes = [
                'steamid' => $steam_id,
                'steamid3' => $steam_id3,
                'steamid64' => $community_id,
                'avatar_url' => $profile->getIconAvatarUrl(),
                'name' => $profile->getNickname(),
            ];
            $new_user = SteamUser::create($attributes);
            $new_user->updateStatistics($profile);
            return $new_user;
        }
        return $steam_user;
    }

    public function updateStatistics(SteamId $profile){
        $steam_id = $this->steamid;
        $community_id = SteamId::convertSteamIdToCommunityId($steam_id);
        $steam_id3 = SteamId::convertCommunityIdToSteamId3($community_id);
        $attributes = [
            'name' => $profile->getNickname(),
            'avatar_url' => $profile->getIconAvatarUrl(),
            'is_online' => false,
            'is_connected' => false,
            'steamid3' => $steam_id3,
            'steamid64' => $community_id,
        ];
        if($profile->isOnline()){
            $attributes['last_online_at'] = now();
            $attributes['is_online'] = true;
        }

        if($this->isInGame()){
            $attributes['last_connected_at'] = now();
            $attributes['is_connected'] = true;
            $attributes['last_address'] = $this->getConnectedPlayer()->getIpAddress();
        }
        $this->update($attributes);
    }

    public function getConnectedPlayer(){
        if(!Cache::has('online_players')){
            GetOnlineUsers::dispatchNow();
        }
        $players_online = Cache::get('online_players');
        /** @var SteamPlayer $player */
        foreach ($players_online as $player){
            if($player->getSteamId() == $this->steamid){
                return $player;
            }
        }
        return null;
    }

    public function isInGame(){
        return $this->getConnectedPlayer() !== null;
    }

    public function getProfile()
    {
        SteamId::clearCache();
        return SteamId::getFromSteamId($this->steamid);
    }
}
