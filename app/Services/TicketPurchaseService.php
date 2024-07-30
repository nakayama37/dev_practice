<?php

namespace App\Services;

use App\Models\Event;
use App\Models\TicketSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TicketPurchaseService

{
  /**
   * チケット購入
   * @param  $request, $event
   * @return $reservedEvents;
   */
  public static function createPaymentIntent($request, $event)
  {
    $event = Event::findOrFail($request->event_id);
    $ticket = $event->ticket()->firstOrFail();
    $totalAmount = $ticket->price * $request->quantity;

    if ($totalAmount == 0) {
      
      $paymentIntent = null;

      return $paymentIntent;

    } else {
      // StripeのAPIキーを設定
      Stripe::setApiKey(config('services.stripe.secret'));

      // トランザクションを開始
      DB::beginTransaction();

      try {
        // PaymentIntentを作成
        $paymentIntent = PaymentIntent::create([
          'amount' => $totalAmount, 
          'currency' => 'jpy',
          'payment_method_types' => ['card'],
        ]);
        // トランザクションをコミット
        DB::commit();
        
        return $paymentIntent;

      } catch (\Exception $e) {

        // エラーが発生した場合はロールバック
        DB::rollBack();

        $paymentIntent = 'error';

        return $paymentIntent;

      }


    }

  }

  /**
   * 販売履歴無料登録
   * @param  $ticket, $quantity
   * @return void
   */
  private static function handleFreeRegistration($ticket, $quantity)
  {
    TicketSale::create([
      'ticket_id' => $ticket->id,
      'user_id' => Auth::id(),
      'price' => 0,
      'quantity' => $quantity,
      'status' => 'completed',
    ]);


  }
}
