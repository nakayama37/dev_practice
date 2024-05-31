<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    /**
     * イベント管理画面一覧
     * 
     * @return void
     */
    public function index()
    {
        $events = DB::table('events')
        ->orderBy('start_at', 'asc')
        ->paginate(10);
        return view('manager.events.index', compact('events'));
    }

    /**
     * イベント作成画面
     * 
     * @return void
     */
    public function create()
    {
        return view('manager.events.create');

    }

    /**
     * イベント登録
     * 
     * @param $request
     * @return void
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
