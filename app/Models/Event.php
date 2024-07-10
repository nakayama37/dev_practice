<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Like;
use App\Models\Location;
use App\Models\Ticket;


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
        'is_public',          // 公開・非公開
        'is_paid',            // 有料・無料
    ];

    /**
     * The error  messages.
     *
     * @var array<string,string>
     */
    private const ERRORS = [
        'STORE' => [
            'MESSAGE' => 'イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:'
        ],
        'UPDATE' => [
            'MESSAGE' => 'イベント更新時にエラーが発生しました。エラー内容は下記です。更新内容:'
        ]
    ];

/*
|--------------------------------------------------------------------------
| relations
|--------------------------------------------------------------------------
|
|
*/

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
     * Relation App\Models\User
     * @return belongsToMany
     */
    public function userLikes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    /**
     * Relation App\Models\Comment
     * @return hasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * Relation App\Models\Location
     * @return hasOne
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }
    /**
     * Relation App\Models\Location
     * @return hasOne
     */
    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }

        /*
|--------------------------------------------------------------------------
| formatter
|--------------------------------------------------------------------------
|
|
*/
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
 /*
|--------------------------------------------------------------------------
| getter
|--------------------------------------------------------------------------
|
|
*/
    /**
     * ログインユーザーがこのイベントに参加しているかを判断する仮想属性を定義
     * @param  void
     * @return $is_user_participating
     */
    public function getIsUserParticipatingAttribute()
    {
        $userId = Auth::id();
        return $this->users()->where('user_id', $userId)->exists();
    }
    /**
     * フォーマットされた価格を取得
     * @param  void
     * @return $is_user_participating
     */
    public function getPriceAttribute($value)
    {
        // 例: 1234.56 を 1234 に変換する
        return (int) $value;
    }
    /**
     * フォーマットされた価格を取得
     * @param  void
     * @return $is_user_participating
     */
    public function getFormattedPriceAttribute()
    {
        // 価格を数値として取得
        $price = $this->attributes['price'];

        // フォーマットに使用するロケールと通貨を設定
        $formattedPrice = number_format($price);

        // 価格を通貨形式にフォーマットして返す
        return $formattedPrice;
    }
    /*
|--------------------------------------------------------------------------
| setter
|--------------------------------------------------------------------------
|
|
*/
    /**
     * 価格が入力されてなければ０で保存
     * @param  $request
     * @return 
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value ?? 0;
    }

/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/
    
    /**
     * イベント一覧取得
     * @param  $user_id
     * @return $events
     */
    public function getEvents($user_id)
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
        ->where('user_id', $user_id)
        ->whereDate('start_at', '>=',  $today)
        ->orderBy('start_at', 'asc')
        ->paginate(10);

        return $events;
    }
    /**
     * イベントランキング取得
     * @param  void
     * @return $events
     */
    public function getEventRankings()
    {
        $now = Carbon::now();
        $today = Carbon::today();

        $startOfMonth = $now->startOfMonth()->toDateString();
        $endOfMonth = $now->endOfMonth()->toDateString();

        $participants = DB::table('participants')
        ->select('event_id', DB::raw('sum(number_of_people) as total_participants'))
        ->whereNull('canceled_at')
        ->groupBy('event_id');

        $likes = DB::table('likes')
        ->select('event_id', DB::raw('count(*) as like_count'))
        ->groupBy('event_id');

        $comments = DB::table('comments')
        ->select('event_id', DB::raw('count(*) as comment_count'))
        ->groupBy('event_id');

        $events = self::with(['users', 'location'])
        ->whereBetween('start_at', [$startOfMonth, $endOfMonth])
            ->where('is_public', true)
            ->whereDate('start_at', '>=', $today)
            ->leftJoinSub($participants, 'participants', function ($join) {
                $join->on('events.id', '=', 'participants.event_id');
            })
            ->leftJoinSub($likes, 'likes', function ($join) {
                $join->on('events.id', '=', 'likes.event_id');
            })
            ->leftJoinSub($comments, 'comments', function ($join) {
                $join->on('events.id', '=', 'comments.event_id');
            })
            ->select('events.*', 'participants.total_participants', 'likes.like_count', 'comments.comment_count')
            ->get()
            ->map(function ($event) {
                $event->total_participants = $event->total_participants ?? 0;
                $event->like_count = $event->like_count ?? 0;
                $event->comment_count = $event->comment_count ?? 0;
                return $event;
            })
            ->sortByDesc('total_participants')
            ->take(10); // トップ10のイベントを取得

        return $events;

    }
    /**
     * 過去イベント一覧取得
     * @param  $user_id
     * @return $events
     */
    public function getPastEvents($user_id)
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
        ->where('user_id', $user_id)
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
     * 検索画面のイベント一覧取得
     * @param  void
     * @return $event
     */
    public function getSearchEvents()
    {

        // サブクエリを作成
        $likes = DB::table('likes')
        ->select('event_id', DB::raw('count(*) as like_count'))
        ->groupBy('event_id');

        $comments = DB::table('comments')
        ->select('event_id', DB::raw('count(*) as comment_count'))
        ->groupBy('event_id');

        // イベントを取得
        $events = Event::with(['categories', 'location'])
        ->leftJoinSub($likes, 'likes', function ($join) {
            $join->on('events.id', '=', 'likes.event_id');
        })
            ->leftJoinSub($comments, 'comments', function ($join) {
                $join->on('events.id', '=', 'comments.event_id');
            })
            ->where('is_public', true)
            ->where('start_at', '>=', now())
            ->orderBy('start_at', 'asc')
            ->select('events.*', 'likes.like_count', 'comments.comment_count') 
            ->paginate(21);


        return $events;
    }

    /**
     * イベント検索取得
     * @param  $request
     * @return $events
     */
    public function search($request)
    {
        $query = Event::query();

        // キーワード検索
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('content', 'like', '%' . $request->keyword . '%');
            });
        }

        // カテゴリ検索
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // イベント日付検索
        if ($request->filled('event_date')) {
            $query->whereDate('start_at', $request->event_date);
        }

        // 公開中のみ取得
        $query->where('is_public', true);

        // 今日以降のイベントのみ取得
        $query->where('start_at', '>=', now());

        // サブクエリを作成
        $likes = DB::table('likes')
        ->select('event_id', DB::raw('count(*) as like_count'))
        ->groupBy('event_id');

        $comments = DB::table('comments')
        ->select('event_id', DB::raw('count(*) as comment_count'))
        ->groupBy('event_id');

        // イベントを取得
        $events = $query->with('categories', 'location')
        ->leftJoinSub($likes, 'likes', function ($join) {
            $join->on('events.id', '=', 'likes.event_id');
        })
        ->leftJoinSub($comments, 'comments', function ($join) {
            $join->on('events.id', '=', 'comments.event_id');
        })
        ->select('events.*', 'likes.like_count', 'comments.comment_count') 
        ->get();

        $events->transform(function ($event) {
            $event->event_date = $event->eventDate;
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
                            'price' => $request['price'],
                            'image' => $fileNameToStore,
                            'is_public' => $request['is_public'],
                            'is_paid' => $request['price'] > 0 ? true : false,
                        ]); 

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error(self::ERRORS['MESSAGE']['STORE'], $request);
            \Log::error($e);

        }

        return $event;

    }
    /**
     * イベント編集
     * @param  $request, $event, $userId, $startDate, $endDate
     * @return void
     */
    public function updateEvent($request, $event, $user_id, $startDate, $endDate, $fileNameToStore)
    {

        DB::beginTransaction();
        try {
                $event->user_id = $user_id;
                $event->title = $request['title'];
                $event->content = $request['content'];
                $event->start_at = $startDate;
                $event->end_at = $endDate;
                $event->max_people = $request['max_people'];
                $event->price = $request['price'];
                $event->image = $fileNameToStore;
                $event->is_public = $request['is_public'];
                $event->is_paid = $request['price'] > 0 ? true : false;
                $event->save();

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error(self::ERRORS['MESSAGE']['UPDATE'], $request);
            \Log::error($e);

        }

        return;

    }


}
