<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;

class Comment extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',            // イベントID
        'user_id',            // ユーザーID
        'content',            // コメント
    ];

    // アクセサをJSONに含める
    protected $appends = ['formatted_created_at'];

    /**
     * Relation App\Models\Event
     * @return belongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    /**
     * Relation App\Models\Event
     * @return belongsToMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // アクセサの定義
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

  

    /**
     * コメント作成
     * @param  $request, $eventId
     * @return void
     */
    public function createComment($request, $eventId)
    {

        DB::beginTransaction();
        try {

            self::create([
                'event_id' => $eventId,
                'user_id' => Auth::id(),
                'content' => $request->content,
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("コメント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }
}
