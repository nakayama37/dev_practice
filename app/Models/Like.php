<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Event;

class Like extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'likes';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',            // イベントID
        'user_id',            // ユーザーID
    ];
/*
|--------------------------------------------------------------------------
| relations
|--------------------------------------------------------------------------
|
|
*/
    /**
     * Relation App\Models\User
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Relation App\Models\Event
     * @return belongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/
    /**
     * ユーザーのいいねイベント一覧取得
     * @param  $user
     * @return $likedEvents
     */
    public function getLikedEvents($user)
    {
        $likedEvents = $user->likedEvents()
            ->with('categories')
            ->get();

        $likedEvents->each(function ($event) {
            $event->total_participants = $event->users->sum('pivot.number_of_people');
            $event->is_user_participating = $event->is_user_participating;
        });
        return $likedEvents;
    }

}
