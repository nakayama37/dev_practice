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
}
