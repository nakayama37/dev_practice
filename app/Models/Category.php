<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Carbon\Carbon;

class Category extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Fillable columns.
     *
     * @var array
     */
    protected $fillable = [
        'name',            // カテゴリー名
    ];

    /**
     * Relation App\Models\Event
     * @return belongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_categories');
    }

    /**
     * カテゴリーごとにイベント一覧取得
     * @param  void
     * @return $categories
     */
    public function getEvents()
    {
        $today = Carbon::today();

        $participants = DB::table('participants')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_at')
        ->groupBy('event_id');


        $categories = self::with(['events' => function ($query) use ($today, $participants) {
            $query->leftJoinSub($participants, 'participants', function ($join) {
                $join->on('events.id', '=', 'participants.event_id');
            })
            // 本日以降の日付データを入れてからコメントイン
                ->where('events.is_public',  true);
                // ->whereDate('events.start_at', '>=',  $today)
                // ->orderBy('events.start_at', 'asc');
        }])
            ->paginate(10);

        return $categories;
    }
}
