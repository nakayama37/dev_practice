<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class EventCategory extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'event_categories';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',            // イベントID
        'category_id'            // カテゴリーID
    ];
/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/
    /**
     * イベントカテゴリー作成
     * @param  $eventID, $eventCategoryId
     * @return void
     */
    public function createEventCategory($eventId, $eventCategoryId)
    {

        DB::beginTransaction();
        try {

            self::create([
                'event_id' => $eventId,
                'category_id' => $eventCategoryId,
                
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $eventId, $eventCategoryId);
            \Log::error($e);
        }

        return;
    }
}
