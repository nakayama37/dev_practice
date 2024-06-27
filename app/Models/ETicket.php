<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TicketSale;

class ETicket extends Model
{
    use HasFactory;


    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'etickets';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_sale_id',           // イベントID
        'user_id',           // イベントID
        'ticket_number',               // チケットタイプ
        'qr_code',              // 価格
        'issued_at',           // チケット数
        'checked_in_at',           // チケット数
    ];

    /*
|--------------------------------------------------------------------------
| relations
|--------------------------------------------------------------------------
|
|
*/
    /**
     * Relation App\Models\TicketSale
     * @return belongsTo
     */
    public function ticketSale()
    {
        return $this->belongsTo(TicketSale::class);
    }
}
