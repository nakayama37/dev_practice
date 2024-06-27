<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class Location extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',            // イベントID
        'postcode',            // 郵便番号
        'venue',               // 会場
        'prefecture',          // 都道府県
        'city',                // 市町村区
        'street',              // 以降の住所
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
     * @return belongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

/*
|--------------------------------------------------------------------------
| getter
|--------------------------------------------------------------------------
|
|
*/
    public function getFullAddressAttribute()
    {
        return "{$this->postcode} {$this->prefecture} {$this->city} {$this->street}";
    }
/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/
    /**
     * イベント場所情報作成
     * @param  $eventId, $request
     * @return void
     */
    public function createEventLocation($eventId, $request)
    {
        DB::beginTransaction();
        try {

            self::create([
                'event_id' => $eventId,
                'postcode' => $request['postcode'],
                'venue' => $request['venue'],
                'prefecture' => $request['prefecture'],
                'city' => $request['city'],
                'street' => $request['street'],
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント場所登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }

    /**
     * イベント場所情報編集
     * @param  $request, $event
     * @return void
     */
    public function updateEventLocation($event, $request)
    {

        DB::beginTransaction();
        try {
            $event->location->update([
                'postcode' => $request['postcode'],
                'venue' => $request['venue'],
                'prefecture' => $request['prefecture'],
                'city' => $request['city'],
                'street' => $request['street'],
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント場所更新時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }

}
