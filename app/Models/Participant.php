<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'participants';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',           // イベントID
        'user_id',            // ユーザID
        'number_of_people',   // 参加人数
        'is_checked_in',      // チェックインフラグ
        'checked_in_at',      // チェックイン時間
        'canceled_at',        // キャンセル日
    ];


}
