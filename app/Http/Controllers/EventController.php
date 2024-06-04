<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    /**
     * イベント管理画面一覧
     * 
     * @return view
     */
    public function index()
    {
        $eventModel = new Event();
        // 全てのイベント取得
        $events = $eventModel->getEvents();

        return view('manager.events.index', compact('events'));
    }

    /**
     * イベント作成画面
     * 
     * @return view
     */
    public function create()
    {
        return view('manager.events.create');

    }

    /**
     * イベント登録
     * 
     * @param $request
     * @return view
     */
    public function store(StoreEventRequest $request)
    {
        // 現在認証しているユーザーのIDを取得
        $user_id = Auth::id();

        // 日付と時間を結合
        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_at']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_at']);

        // イベントの作成
        $eventModel = new Event();
        $eventModel->createEvent($request, $user_id, $startDate, $endDate);

        // 登録成功のセッション
        session()->flash('status', 'イベントを登録しました');

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
        $event = Event::findOrFail($event->id);

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
        $event = Event::findOrFail($event->id);
        
        // アクセサでフォーマットされた日付を取得
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit', compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    /**
     * イベント編集
     * 
     * @param $request
     * @return view
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        // 現在認証しているユーザーのIDを取得
        $user_id = Auth::id();

        // 日付と時間を結合
        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_at']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_at']);

        // イベントの作成
        $eventModel = new Event();
        // イベント情報取得
        $event = Event::findOrFail($event->id);
        $eventModel->updateEvent($request, $event, $user_id, $startDate, $endDate);

        // 登録成功のセッション
        session()->flash('status', 'イベントを更新しました');

        return to_route('events.index');
    }

    /**
     * 過去イベント一覧
     * 
     * @return view
     */
    public function past()
    {

        $eventModel = new Event();
        // 過去のイベント取得
        $events = $eventModel->getPastEvents();

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
