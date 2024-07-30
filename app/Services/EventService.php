<?php

namespace App\Services;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\EventRegistrationConfirmation;
use App\Services\EticketService;

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
  public static function join($user, $request, $event, $ticketSale) 
  {

    // トランザクションを開始
    DB::beginTransaction();
    try {
      // // イベント参加の作成
      $participantModel = new Participant();
      $joined = $participantModel->joinEvent($user->id, $request);

      if (!$joined) {
        // イベントには既に参加済みの場合
        return;
      }
      // Eチケット生成
      $qrCodePath = EticketService::createEticket($ticketSale, $user->id);

      // 確認メール送信
      Mail::to($user->email)->send(new EventRegistrationConfirmation($event, $user, $qrCodePath));
      // トランザクションをコミット
      DB::commit();

    } catch (\Exception $e) {

      // エラーが発生した場合はロールバック
      DB::rollBack();
      
      }
     
  }
}