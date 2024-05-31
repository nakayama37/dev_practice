<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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


}
