<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',           // イベントID
        'price',              // 価格
        'quantity',           // チケット数
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
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

/*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/

}
