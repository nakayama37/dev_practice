<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Like;


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
     * Relation App\Models\User
     * @return belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'participants')->withPivot('id','number_of_people', 'is_checked_in', 'checked_in_at', 'canceled_at' );
    }
    
    /**
     * Relation App\Models\Category
     * @return belongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_categories');
    }

    /**
     * Relation App\Models\Like
     * @return hasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
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
        $today = Carbon::today();

        $participants = DB::table('participants')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_at')
        ->groupBy('event_id');

        $events = self::
        leftJoinSub($participants, 'participants', function ($join) {
            $join->on('events.id', '=', 'participants.event_id');
        })
        ->whereDate('start_at', '>=',  $today)
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
        $today = Carbon::today();

        $participants = DB::table('participants')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_at')
        ->groupBy('event_id');

        $events = self::
        leftJoinSub($participants, 'participants', function ($join) {
            $join->on('events.id', '=', 'participants.event_id');
        })
        ->whereDate('start_at', '<',  $today)
        ->orderBy('start_at', 'desc')
        ->paginate(10);

        return $events;

    }

    /**
     * 予約可能な人数取得
     * @param  $eventId
     * @return $reservedPeople
     */
    public function getReservedPeople($eventId)
    {

        $reservedPeople = DB::table('participants')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_at')
        ->groupBy('event_id')
        ->having('event_id', $eventId)
        ->first();


        return $reservedPeople;
    }

    /**
     * イベント検索取得
     * @param  $categoryId
     * @return $events
     */
    public function search($categoryId)
    {
        $query = Event::query();

        // 公開中かつ本日以降のイベントを取得
        $query->where('is_public', true);
        // To do 本日以降の日付のイベントを作った後にコメントイン
            // ->where('start_at', '>=', Carbon::today());

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $events = $query->with('categories')->get();

        $events->transform(function ($event) {
            $event->route = route('reservations.detail', ['event' => $event->id]);
            return $event;
        });

        return $events;
    }
    /**
     * イベント作成
     * @param  $request, $userId, $startDate, $endDate, $fileNameToStore
     * @return $eventId
     */
    public function createEvent($request, $user_id, $startDate, $endDate, $fileNameToStore)
    {

        DB::beginTransaction();
        try {

              $event = self::create([
                            'user_id' => $user_id,
                            'title' => $request['title'],
                            'content' => $request['content'],
                            'start_at' => $startDate,
                            'end_at' => $endDate,
                            'max_people' => $request['max_people'],
                            'image' => $fileNameToStore,
                            'is_public' => $request['is_public'],
                        ]); 

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);

        }

        return $event->id;

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
