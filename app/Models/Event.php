<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;


class Event extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',            // ユーザーID
        'title',              // イベント名
        'content',            // イベント詳細
        'start_at',           // 開始日時
        'end_at',             // 終了日時
        'max_people',         // 定員
        'price',              // イベント価格
        'image',              // イベント画像パス
        'is_public',               // 公開・非公開
        'is_paid',               // 有料・無料
    ];

    /**
     * Relation App\Models\DvsEbook
     * @return belongsTo
     */
    // public function dvsEbookFiles()
    // {
    //     return $this->belongsTo(DvsEbook::class, 'ebook_id');
    // }

    public function __construct()
    {  
        // 本日の日付を取得
        $this->today = Carbon::today();

    }

    /**
     * イベント日付のフォーマット
     * @param  void
     * @return $eventDate
     */
    protected function eventDate(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->start_at)->format('Y年m月d日')
        );
    }

    /**
     * イベント開始時間のフォーマット
     * @param  void
     * @return $startTime
     */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->start_at)->format('H時i分')
        );
    }

    /**
     * イベント終了時間のフォーマット
     * @param  void
     * @return $startTime
     */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->end_at)->format('H時i分')
        );
    }

    /**
     * イベント一覧取得
     * @param  void
     * @return $events
     */
    public function getEvents()
    {

        $events = Event::
        whereDate('start_at', '>=',  $this->today)
        ->orderBy('start_at', 'asc')
        ->paginate(10);

        return $events;
    }
    /**
     * 過去イベント一覧取得
     * @param  void
     * @return $events
     */
    public function getPastEvents()
    {
        
        $events = Event::
        whereDate('start_at', '<',  $this->today)
        ->orderBy('start_at', 'desc')
        ->paginate(10);

        return $events;

    }

    /**
     * イベント作成
     * @param  $request, $userId, $startDate, $endDate
     * @return void
     */
    public function createEvent($request, $user_id, $startDate, $endDate)
    {

        DB::beginTransaction();
        try {

            Event::create([
                'user_id' => $user_id,
                'title' => $request['title'],
                'content' => $request['content'],
                'start_at' => $startDate,
                'end_at' => $endDate,
                'max_people' => $request['max_people'],
                'is_public' => $request['is_public'],
            ]); 

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);

        }

        return;

    }
    /**
     * イベント編集
     * @param  $request, $event, $userId, $startDate, $endDate
     * @return void
     */
    public function updateEvent($request, $event, $user_id, $startDate, $endDate)
    {

        DB::beginTransaction();
        try {
                $event->user_id = $user_id;
                $event->title = $request['title'];
                $event->content = $request['content'];
                $event->start_at = $startDate;
                $event->end_at = $endDate;
                $event->max_people = $request['max_people'];
                $event->is_public = $request['is_public'];
                $event->save();

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);

        }

        return;

    }


}
