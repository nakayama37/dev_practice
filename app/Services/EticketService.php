<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\ETicket;

class EticketService

{
  /**
   * Eチケット生成
   * @param  $request, $event
   * @return void
   */
  public static function createEticket($ticketSale, $userId)
  {
      $ticketNumber = Str::random(8);

      // eTicketの生成
      $eticket = Eticket::create([
        'ticket_sale_id' => $ticketSale,
        'user_id' => $userId,
        'qr_code' => '', // 後でQRコードを生成
        'ticket_number' => $ticketNumber,
        'issued_at' => Carbon::now(),
      ]);

      // QRコードを生成する
      $qrCodeContent = QrCode::format('png')->size(300)->generate(route('etickets.show', $eticket->id));

      // 保存先のパスを設定する
      $qrCodePath = 'qrcodes/' . $eticket->id . '.png';

      // ストレージにQRコードを保存する
      Storage::put('public/' . $qrCodePath, $qrCodeContent);

      // 生成されたQRコードのパスをeticketモデルに保存する
      $eticket->qr_code = $qrCodePath;
      $eticket->save();

      return $qrCodePath;

    }

}
