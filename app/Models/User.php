<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Illuminate\Support\Facades\Password;

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

    /**
     * roles
     *
     * @var array{
     *  MANAGER: string,
     *  USER: string
     * }
     */
    private const ROLE = [
        'MANAGER' => '5',
        'USER'  => '9',
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
            $user->role = self::ROLE['MANAGER'];
            $user->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $user);
            \Log::error($e);
        }

        return;
    }

    /**
     * 既存ユーザーの取得
     * 
     * @param  $line_id
     * @return $email
     */
    public function getExistingUserByLineLogin($line_id, $email)
    {
        $existingUser = User::where('email', $email)
            ->orWhere('line_id', $line_id)
            ->first();

        return $existingUser;
    }

    /**
     * Lineログインからユーザー登録
     * @param  $user,$email,$line_id,$password
     * @return void
     */
    public function storeByLineLogin($user, $email, $line_id, $password)
    {

        DB::beginTransaction();
        try {
            $newUser = User::create([
                'name' => $user->name ?? $user->nickname,
                'email' => $email,
                'line_id' => $line_id,
                'password' => $password,
                'role' => self::ROLE['USER'],

            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $user);
            \Log::error($e);
        }

        return $newUser;
    }
}
