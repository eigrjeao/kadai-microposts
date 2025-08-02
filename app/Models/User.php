<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

        /**
     * このユーザーに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers','favorites']);
    }

    /**
     * このユーザーがフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザーをフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    /**
 * このユーザーがお気に入りに追加した投稿。（Micropostモデルとの多対多の関係）
 */
    public function favorites()
    {
    return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }

    /**
 * この投稿をお気に入りに追加したユーザー。（Userモデルとの多対多の関係）
 */
    public function favorite_users()
    {
    return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }


    /**
     * $userIdで指定されたユーザーをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザーをアンフォローする。
     *
     * @param  int $usereId
     * @return bool
     */
    public function unfollow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int $userId
     * @return bool
     */
    public function is_following(int $userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    /**
     * 指定された Micropost をお気に入りに追加する
     */
    public function favorite($micropostId)
    {
        $exist = $this->is_favoriting($micropostId);
        $its_me = $this->id === Micropost::find($micropostId)?->user_id;

        if ($exist) {
            return false;
        } else {
            $this->favorites()->attach($micropostId);
            return true;
        }
    }

    /**
     * 指定された Micropost をお気に入りから削除する
     */
    public function unfavorite($micropostId)
    {
        $exist = $this->is_favoriting($micropostId);
        $its_me = $this->id === Micropost::find($micropostId)?->user_id;

        if ($exist) {
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 指定された Micropost をこのユーザーがお気に入りに追加しているか確認
     */
    public function is_favoriting($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    public function is_favorite($micropostId)
    {
    return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
