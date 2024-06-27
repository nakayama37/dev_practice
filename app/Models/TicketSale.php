<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'status',           // チケット数
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


}
