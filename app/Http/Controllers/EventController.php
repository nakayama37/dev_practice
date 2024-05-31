<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
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

        // アクセサでフォーマットされた日付を取得
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.show', compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
