<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Event;


class Ticket extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',           // イベントID
        'price',              // 価格
        'quantity',           // チケット数
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
     * チケット作成
     * @param  $eventId, $request
     * @return void
     */
    public function createTicket($eventId, $request)
    {
        DB::beginTransaction();
        try {

            self::create([
                'event_id' => $eventId,
                'price' => $request['price'],
                'quantity' => $request['max_people']
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("チケット登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }


    /**
     * チケット編集
     * @param  $request, $event
     * @return void
     */
    public function updateTicket($event, $request)
    {

        DB::beginTransaction();
        try {
            $event->ticket->update([
                'price' => $request['price'],
                'quantity' => $request['max_people'],
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベントチケット更新時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }
}
