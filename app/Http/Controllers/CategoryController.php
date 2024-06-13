<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;


class CategoryController extends Controller
{
    /**
     * カテゴリー一覧
     * 
     * @return view
     */
    public function index()
    {

        $categoryModel = new Category();
        // 全てのイベント取得
        $categories = $categoryModel->getCategories();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * カテゴリー作成画面
     * 
     * @return view
     */
    public function create()
    {
    
        return view('admin.categories.create');
    }

    /**
     * カテゴリー登録
     * 
     * @param $request
     * @return view
     */
    public function store(StoreCategoryRequest $request)
    {

        $categoryModel = new Category();

        // カテゴリーの作成
        $categoryModel->createCategory($request);

        // 登録成功のセッション
        session()->flash('status', 'カテゴリーを登録しました');

        return to_route('admin.categories.index');
    }

    /**
     * イベント編集画面
     * 
     * @param $event
     * @return view
     */
    public function edit(Category $category)
    {
        dd($category);
        // イベント情報取得
        $event = Event::findOrFail($id);

        // アクセサでフォーマットされた日付を取得
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit', compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    /**
     * カテゴリーの公開、非公開を変える
     * 
     * @param $request
     * @return view
     */
    public function toggle(Category $category)
    {
        
        $categoryInstance = new Category();

        $categoryModel = Category::findOrFail($category->id);
        // 公開なら非公開へ 非公開なら公開へ変更
        if($category->is_public == 0) {
            $categoryInstance->togglePublic($categoryModel, 1);
            
            } else if($category->is_public == 1) {
            $categoryInstance->togglePublic($categoryModel, 0);
        }
        
        // 登録成功のセッション
        session()->flash('status', '公開・非公開を変更しました');

        return to_route('categories.index');
    }
}
