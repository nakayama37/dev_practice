<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/
    /**
     * イベント参加者を取得
     * @param  $id イベントID
     * @return $reservation
     */
    public function getEventParticipant($id)
    {
        $reservation = self::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        return $reservation;

    }
    /**
     * 予約済みイベントを取得
     * @param  $eventId イベントID
     * @return $isReserved
     */
    public function getIsReserved($eventId)
    {
        $isReserved = self::where('user_id', '=', Auth::id())
            ->where('event_id', '=', $eventId)
            ->where('canceled_at', '=', null)
            ->latest()
            ->first();

            return $isReserved;

    }
    /**
     * 参加者数を集計
     * @param  $event_id
     * @return $participantCount
     */
    public function participantCount($event_id)
    {
        $participantCount = self::where('event_id', $event_id)->sum('number_of_people');
        return $participantCount;
    }

    /**
     * イベント参加
     * @param  $user_id, $event_id
     * @return void
     */
    public function joinEvent($user_id, $request)
    {

        DB::beginTransaction();
        try {

            // すでに同じイベントに参加しているか確認
            $exists = self::where('user_id', $user_id)
                ->where('event_id', $request->event_id)
                ->whereNull('canceled_at')
                ->exists();

            if ($exists) {

                return false; // すでに参加している場合

            } else {
                
                self::create([
                    'event_id' => $request->event_id,
                    'user_id' => $user_id,
                    'number_of_people' => $request->quantity,
                    
                ]);

            }


            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント参加登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return true;
    }
    /**
     * イベントキャンセル
     * @param  $reservation
     * @return true
     */
    public function cancel($reservation)
    {

        DB::beginTransaction();
        try {

            $reservation->canceled_at = Carbon::now()->format('Y-m-d H:i:s');
            $reservation->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベントキャンセル時にエラーが発生しました。エラー内容は下記です。登録内容:", $reservation);
            \Log::error($e);
        }

        return true;
    }

}
