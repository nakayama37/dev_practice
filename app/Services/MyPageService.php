<?php

namespace App\Services;

use Carbon\Carbon;

class MyPageService

{
  /**
   * 予約したイベント取得
   * @param  $events, $string
   * @return $reservedEvents;
   */
  public static function reservedEvent($events, $string)
  {
    $reservedEvents = [];

    if($string === 'fromToday') {
      foreach ($events->sortBy('start_at') as $event) {
        if(is_null($event->pivot->canceled_at) && $event->start_at >= Carbon::now()->format('Y-m-d 00:00:00')) {
          
          $eventInfo = [
            'id' => $event->id,
            'title' => $event->title,
            'start_at' => $event->start_at,
            'end_at' => $event->end_at,
            'number_of_people' => $event->pivot->number_of_people,
          ];
          array_push($reservedEvents, $eventInfo);
        }
      }
    }
    if($string === 'past') {
      foreach ($events->sortBy('start_at') as $event) {
        if (is_null($event->pivot->canceled_at) && $event->start_at < Carbon::now()->format('Y-m-d 00:00:00')) {
          $eventInfo = [
            'id' => $event->id,
            'title' => $event->title,
            'start_at' => $event->start_at,
            'end_at' => $event->end_at,
            'number_of_people' => $event->pivot->number_of_people,
          ];
          array_push($reservedEvents, $eventInfo);
        }
      }
    }

    return $reservedEvents;

  }


}