<?php

namespace App\Services;

use Carbon\Carbon;

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
}
