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
        'is_public',            // 公開・非公開
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
     * カテゴリー一覧取得
     * @param  void
     * @return $categories
     */
    public function getCategories()
    {

        $categories = self::paginate(10);

        return $categories;
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

    /**
     * カテゴリー作成
     * @param  $request
     * @return void
     */
    public function createCategory($request)
    {

        DB::beginTransaction();
        try {

             self::create([
                'name' => $request['name'],
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("カテゴリー登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $request);
            \Log::error($e);
        }

        return;
    }

    /**
     * カテゴリー編集
     * @param  $request, $category
     * @return void
     */
    public function updateCategory($request, $category)
    {

        DB::beginTransaction();
        try {
            $category->name = $request['name'];
            $category->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("カテゴリー編集時にエラーが発生しました。エラー内容は下記です。編集内容:", $request);
            \Log::error($e);
        }

        return;
    }


    /**
     * カテゴリー公開、非公開変更
     * @param  $caategory, $id
     * @return void
     */
    public function togglePublic($category, $flg)
    {

        DB::beginTransaction();
        try {
            $category->is_public = $flg;
            $category->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            \Log::error("イベント登録時にエラーが発生しました。エラー内容は下記です。登録内容:", $category);
            \Log::error($e);
        }

        return;
    }
}
