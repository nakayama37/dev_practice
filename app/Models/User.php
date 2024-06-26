<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'line_id',
        'password',
        'role',
    ];

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
/*
|--------------------------------------------------------------------------
| relations
|--------------------------------------------------------------------------
|
|
*/
    /**
     * Relation App\Models\Event
     * @return belongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'participants')->withPivot('id', 'number_of_people', 'is_checked_in', 'checked_in_at', 'canceled_at');
    }
    /**
     * Relation App\Models\Event
     * @return belongsToMany
     */
    public function likedEvents()
    {
        return $this->belongsToMany(Event::class, 'likes');
    }
    /**
     * Relation App\Models\Comment
     * @return hasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/
    /**
     * managerに更新
     * @param  $user
     * @return void
     */
    public function joinManager($user)
    {

        DB::beginTransaction();
        try {
            $user->role = 5;
            $user->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $user);
            \Log::error($e);
        }

        return;
    }
}
