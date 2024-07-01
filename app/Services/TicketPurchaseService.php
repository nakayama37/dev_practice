<?php

namespace App\Services;

use App\Models\Event;
use App\Models\TicketSale;
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
      // 金額が0円の場合は即座に参加登録とチケット販売を処理
      self::handleFreeRegistration($ticket, $request->quantity);
      return response()->json(['message' => 'チケットの購入が完了しました']);
    }

    // StripeのAPIキーを設定
    Stripe::setApiKey(config('services.stripe.secret'));

    // PaymentIntentを作成
    $paymentIntent = PaymentIntent::create([
      'amount' => $totalAmount, 
      'currency' => 'jpy',
      'payment_method_types' => ['card'],
    ]);

      return $paymentIntent;
  }

  /**
   * 販売履歴無料登録
   * @param  $ticket, $quantity
   * @return void
   */
  private function handleFreeRegistration($ticket, $quantity)
  {
    TicketSale::create([
      'ticket_id' => $ticket->id,
      'user_id' => Auth::id(),
      'price' => 0,
      'quantity' => $quantity,
      'status' => 'complete',
    ]);
  }
}
