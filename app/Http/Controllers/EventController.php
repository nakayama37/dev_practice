<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\User;
use App\Models\Event;
use App\Models\Location;
use App\Models\Category;
use App\Models\Participant;
use App\Services\EventService;
use App\Services\ImageService;



class EventController extends Controller
{

    /**
     * セッションメッセージ
     *
     * @var array<string,string>
     */
    private const MESSAGES = [
        'SUCCESS' => [
            'STORE_EVENT' => 'イベントを登録しました',
            'UPDATE_EVENT' => 'イベントを更新しました'
        ],
        'ERROR' => [
            'OVER_CAPACITY' => '定員オーバーです 登録できませんでした'
        ]
    ];

    /*
|--------------------------------------------------------------------------
| ゲスト権限のコントローラー
|--------------------------------------------------------------------------
| welcome
| searchIndex
| search
|
*/
    /**
     * Display a listing of the resource.
     */
    public function welcome()
    {
        $categoryModel = new Category();
        $eventModel = new Event();

        $categories = $categoryModel->getEvents();

        $events = $eventModel->getEventRankings();

       
        return view('welcome', compact('categories', 'events'));
    }

    /**
     * イベント検索画面
     * 
     * @return view
     */
    public function searchIndex()
    {
        $categories = Category::all();
        $eventModel = new Event();
        $events = $eventModel->getSearchEvents();
       
        return view('search.index', compact('categories', 'events'));
    }
    /**
     * イベント検索画面
     * 
     * @return view
     */
    public function search(Request $request)
    {

        $eventModel = new Event();
        $events = $eventModel->search($request);
       
        return response()->json($events);
        
    }

    /*
|--------------------------------------------------------------------------
| 利用者のみ権限のコントローラー
|--------------------------------------------------------------------------
| createByOnlyUser
| storeByOnlyUser
|
*/

    /**
     * イベント作成画面
     * 
     * @return view
     */
    public function createByOnlyUser()
    {
        $categories = Category::all();
        return view('user.events.create', compact('categories'));
    }


    /**
     * イベント登録
     * 
     * @param $request
     * @return view
     */
    public function storeByOnlyUser(StoreEventRequest $request)
    {
        // 現在認証しているユーザーのIDを取得
        $user_id = Auth::id();
        $imageFile = $request->file('image');
        $fileNameToStore = null;

        $userModel = new User();
        $eventModel = new Event();
        $locationModel = new Location();

        // 主催者登録
        $user = User::findOrFail($user_id);
        $userModel->joinManager($user);

        // 日付と時間を結合
        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_at']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_at']);

        //画像をストレージへ保存 
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileNameToStore = ImageService::upload($imageFile, 'events');
        }

        // イベントの作成
        $event =  $eventModel->createEvent($request, $user_id, $startDate, $endDate, $fileNameToStore);

        // イベントカテゴリーの作成
        $event->categories()->sync($request['categories']);

        // イベント場所作成
        $locationModel->createEventLocation($event->id, $request);

        // 登録成功のセッション
        session()->flash('status', self::MESSAGES['SUCCESS']['STORE_EVENT']);

        return to_route('events.index');
    }
    /*
|--------------------------------------------------------------------------
| 利用者以上権限のコントローラー
|--------------------------------------------------------------------------
| home
| detail
| join
|
*/
    /**
     * ログイン後ホーム画面
     */
    public function home()
    {
        $categoryModel = new Category();
        $eventModel = new Event();
        
        $categories = $categoryModel->getEvents();

        $events = $eventModel->getEventRankings();


        return view('home', compact('categories', 'events'));
    }

    /**
     * イベント詳細画面
     * 
     * @param $event
     * @return view
     */
    public function detail(Event $event)
    {
        // イベント情報取得
        $event = Event::findOrFail($event->id);

        $eventModel = new Event();
        $participantModel = new Participant();
        // イベント参加人数を集計・算出
        $reservedPeople = $eventModel->getReservedPeople($event->id);
        // 予約が入っている場合、定員 ー 参加人数
        if (!is_null($reservedPeople)) {
            $reservablePeople = $event->max_people - $reservedPeople->number_of_people;
        } else {
            // 予約が入っていない場合、定員
            $reservablePeople = $event->max_people;
        }

        // イベント参加者数を集計
        $isReserved = $participantModel->getIsReserved($event->id);

        // イベントに対する全てのいいね数を取得
        $likeCount = $event->likes()->count();
        $liked = $event->likes()->where('user_id', Auth::id())->exists();

        // アクセサでフォーマットされた日付を取得
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        // イベント参加者数を集計
        $participantCount = $participantModel->participantCount($event->id);

        return view('reservations.show', compact('event', 'eventDate', 'startTime', 'endTime', 'participantCount', 'reservablePeople', 'isReserved', 'likeCount', 'liked'));
    }

    /**
     * イベント登録
     * 
     * @param $request
     * @return view
     */
    public function join(StoreParticipantRequest $request)
    {
        // 変数宣言
        $reservablePeople = 0;
        // 現在認証しているユーザーのIDを取得
        $user = Auth::user();

        // イベント情報取得
        $event = Event::findOrFail($request['event_id']);
        $eventModel = new Event();
        // イベント参加人数を集計・算出
        $reservedPeople = $eventModel->getReservedPeople($request['event_id']);
        // イベント参加者がいる場合
        if (!is_null($reservedPeople)) {
            // イベント参加可能人数
            $reservablePeople = $event->max_people - $reservedPeople->number_of_people;
            // イベントが参加可能且つ、リクエスト参加人数が参加可能人数以下の場合、登録
            if ($reservablePeople > 0 && $reservablePeople >= $request['number_of_people']) {
                // イベント参加登録
                EventService::join($user, $request, $event);
                
            } else {
                // 定員オーバーの場合、登録しない
                session()->flash('status', self::MESSAGES['ERROR']['OVER_CAPACITY']);

                return to_route('home');
            }
        } else {
            // イベント参加者がいない場合
            // イベント参加登録
            EventService::join($user, $request, $event);
            
        }

        return to_route('home');
    }

    /*
|--------------------------------------------------------------------------
| 管理者以上権限のコントローラー
|--------------------------------------------------------------------------
|
| index
| create
| store
| show
| edit
| update
| past
|
*/
    /**
     * イベント管理画面一覧
     * 
     * @return view
     */
    public function index()
    {
        $user_id = Auth::id();
        $eventModel = new Event();
        // 全てのイベント取得
        $events = $eventModel->getEvents($user_id);

        return view('manager.events.index', compact('events'));
    }

    /**
     * イベント作成画面
     * 
     * @return view
     */
    public function create()
    {
        $categories = Category::all();
        return view('manager.events.create', compact('categories'));
    }

    /**
     * イベント登録
     * 
     * @param $request
     * @return view
     */
    public function store(StoreEventRequest $request)
    {
    
        $user_id = Auth::id();
        $imageFile = $request->file('image');
        $fileNameToStore = null;

        $eventModel = new Event();
        $locationModel = new Location();

        // 日付と時間を結合
        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_at']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_at']);

        //画像をストレージへ保存 
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileNameToStore = ImageService::upload($imageFile, 'events');
        }

        // イベントの作成
        $event =  $eventModel->createEvent($request, $user_id, $startDate, $endDate, $fileNameToStore);

        // イベントカテゴリーの作成
        $event->categories()->sync($request['categories']);

        // イベント場所作成
        $locationModel->createEventLocation($event->id, $request);

        // 登録成功のセッション
        session()->flash('status', self::MESSAGES['SUCCESS']['STORE_EVENT']);

        return to_route('events.index');
    }

    /**
     * イベント詳細画面
     * 
     * @param $event
     * @return view
     */
    public function show(Event $event)
    {
        // イベント情報取得
        $event = Event::with(['location', 'categories'])->findOrFail($event->id);

        $users = $event->users;

        $participants = [];

        foreach ($users as $user) {
            $participant = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_at' => $user->pivot->canceled_at
            ];

            array_push($participants, $participant);
        }

        // アクセサでフォーマットされた日付を取得
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.show', compact('event', 'users', 'participants', 'eventDate', 'startTime', 'endTime'));
    }

    /**
     * イベント編集画面
     * 
     * @param $event
     * @return view
     */
    public function edit(Event $event)
    {
        // イベント情報取得
        $event = Event::with(['location', 'categories'])->findOrFail($event->id);
        $categories = Category::all();

        // アクセサでフォーマットされた日付を取得
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit', compact('event', 'eventDate', 'startTime', 'endTime', 'categories'));
    }

    /**
     * イベント編集
     * 
     * @param $request
     * @return view
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        
        $eventModel = new Event();
        $locationModel = new Location();

        // 現在認証しているユーザーのIDを取得
        $user_id = Auth::id();

        $imageFile = $request->file('image');
        $fileNameToStore = null;
        // 日付と時間を結合
        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_at']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_at']);

        // 画像の編集
        if (!is_null($imageFile) && $imageFile->isValid()) {
    
            $fileNameToStore = ImageService::update($imageFile, 'events', $event->image);
        }
        
        // イベントの編集
        $eventModel->updateEvent($request, $event, $user_id, $startDate, $endDate, $fileNameToStore);
        
        // カテゴリ編集
        $event->categories()->sync($request['categories']);
        
        // 場所編集
        $locationModel->updateEventLocation($event, $request);

        // 登録成功のセッション
        session()->flash('status', self::MESSAGES['SUCCESS']['UPDATE_EVENT']);

        return to_route('events.index');
    }

    /**
     * 過去イベント一覧
     * 
     * @return view
     */
    public function past()
    {
        // 現在認証しているユーザーのIDを取得
        $user_id = Auth::id();

        $eventModel = new Event();
        // 過去のイベント取得
        $events = $eventModel->getPastEvents($user_id);

        return view('manager.events.past', compact('events'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
