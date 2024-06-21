<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Like;
use App\Models\Event;
use App\Models\Participant;
use App\Services\MyPageService;


class MyPageController extends Controller
{
    /**
     * イベント一覧
     * 
     * @return view
     */
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $events = $user->events;
        // 本日以降のマイイベント取得
        $fromTodayEvents = MyPageService::reservedEvent($events, 'fromToday');
        // 過去のマイイベント取得
        $pastEvents = MyPageService::reservedEvent($events, 'past');

        $likeModel = new Like();
        $likedEvents = $likeModel->getLikedEvents($user);

        return view('mypage.index', compact('fromTodayEvents', 'pastEvents', 'likedEvents'));
    }
    /**
     * イベント詳細
     * 
     * @param $id イベントID
     * @return view
     */
    public function show($id)
    {

        $event = Event::findOrFail($id);
        // イベント参加者数取得
        $participantModel = new Participant();
        $reservation = $participantModel->getEventParticipant($id);

        return view('mypage/show', compact('event', 'reservation'));
    }
    /**
     * イベントキャンセル
     * 
     * @param $id イベントID
     * @return view
     */
    public function cancel($id)
    {
        // イベント参加者数取得
        $participantModel = new Participant();
        $reservation = $participantModel->getEventParticipant($id);
        // イベントキャンセル
        $reservation = $participantModel->cancel($reservation);

        session()->flash('status', 'キャンセルしました');

        return to_route('home');
    }
}
