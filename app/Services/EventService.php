<?php

namespace App\Services;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegistrationConfirmation;

class EventService

{
  /**
   * 日付と時間の結合
   * @param  $date, $time
   * @return $dateTime
   */
  public static function joinDateAndTime($date, $time) 
  {
      $dateTime = $date . " " . $time;
      return Carbon::createFromFormat('Y-m-d H:i', $dateTime);
  }

  /**
   * イベント参加登録
   * @param  $user_id, $request
   * @return view
   */
  public static function join($user, $request, $event) 
  {
      $participantModel = new Participant();
      // // イベント参加の作成
      $joined = $participantModel->joinEvent($user->id, $request);

      if (!$joined) {
        // イベントには既に参加済みの場合
        session()->flash('status', 'このイベントは参加済みです');

        return to_route('home');
      }

      // 確認メール送信
      Mail::to($user->email)->send(new EventRegistrationConfirmation($event, $user));

      // 登録成功のセッション
      session()->flash('status', 'イベント参加登録が完了し、確認メールを送信しました');  
  }
}
