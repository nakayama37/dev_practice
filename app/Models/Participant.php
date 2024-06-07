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

    /**
     * イベント参加
     * @param  $user_id, $event_id
     * @return void
     */
    public function joinEvent($user_id, $event_id)
    {

        DB::beginTransaction();
        try {

            Participant::create([
                'event_id' => $event_id,
                'user_id' => $user_id,
                
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }

}
