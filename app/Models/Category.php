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
/*
|--------------------------------------------------------------------------
| relations
|--------------------------------------------------------------------------
|
|
*/
    /**
     * Relation App\Models\Event
     * @return belongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_categories');
    }

 /*
|--------------------------------------------------------------------------
| database
|--------------------------------------------------------------------------
|
|
*/

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

        $likes = DB::table('likes')
        ->select('event_id', DB::raw('count(*) as like_count'))
        ->groupBy('event_id');

        $comments = DB::table('comments')
        ->select('event_id', DB::raw('count(*) as comment_count'))
        ->groupBy('event_id');

        $categories = self::with(['events' => function ($query) use ($today, $participants, $likes, $comments) {
            $query->leftJoinSub($participants, 'participants', function ($join) {
                $join->on('events.id', '=', 'participants.event_id');
            })
                ->leftJoinSub($likes, 'likes', function ($join) {
                    $join->on('events.id', '=', 'likes.event_id');
                })
                ->leftJoinSub($comments, 'comments', function ($join) {
                    $join->on('events.id', '=', 'comments.event_id');
                })
                ->where('events.is_public', true)
                ->whereDate('events.start_at', '>=', $today)
                ->orderBy('events.start_at', 'asc')
                ->select('events.*', 'participants.number_of_people', 'likes.like_count', 'comments.comment_count'); 
        }, 'events.location'])
        ->paginate(10);


        return $categories;
    }


    /**
     * カテゴリー公開、非公開変更
     * @param  $caategory, $id
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

            \Log::error("カテゴリー登録時にエラーが発生しました。エラー内容は下記です。変更内容:", $request);
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

            \Log::error("公開・非公開変更時にエラーが発生しました。エラー内容は下記です。変更内容:", $category);
            \Log::error($e);
        }

        return;
    }
}
