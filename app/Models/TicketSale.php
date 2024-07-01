<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\User;

class TicketSale extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'ticket_sales';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',           // イベントID
        'user_id',           // イベントID
        'quantity',               // チケットタイプ
        'total_price',              // 価格
        'status',           // 
        'payment_intent_id',
    ];

    /*
|--------------------------------------------------------------------------
| relations
|--------------------------------------------------------------------------
|
|
*/
    /**
     * Relation App\Models\Event
     * @return belongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    /**
     * Relation App\Models\User
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Relation App\Models\Eticket
     * @return Hasone
     */
    public function etickets()
    {
        return $this->hasOne(Eticket::class);
    }


    /**
     * チケット販売履歴作成
     * @param  $eventId, $request
     * @return void
     */
    public function createTicketSale($ticketId, $totalAmount, $request)
    {
        DB::beginTransaction();
        try {

            self::create([
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
                'total_price' => $totalAmount,
                'quantity' => $request->quantity,
                'status' => 'completed',
                'payment_intent_id' => $request->input('payment_intent_id'),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("チケット登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }

}
