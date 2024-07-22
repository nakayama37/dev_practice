<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;


class CategoryController extends Controller
{

    /**
     * セッションメッセージ
     *
     * @var array<string,string>
     */
    private const MESSAGES = [
        'SUCCESS' => [
            'STORE_CATEGORY' => 'イベントを登録しました',
            'UPDATE_CATEGORY' => 'イベントを更新しました',
            'TOGGLE_PUBLIC' => '公開・非公開を変更しました'
        ],
        'ERROR' => [
            
        ]
    ];

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
        session()->flash('status', self::MESSAGES['SUCCESS']['STORE_CATEGORY']);

        return to_route('categories.index');
    }

    /**
     * イベント編集画面
     * 
     * @param $event
     * @return view
     */
    public function edit(Category $category)
    {
        // イベント情報取得
        $category = Category::findOrFail($category->id);

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * カテゴリー編集
     * 
     * @param $request
     * @return view
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        // イベントの作成
        $categoryModel = new Category();
        // イベント情報取得
        $category = Category::findOrFail($category->id);
        $categoryModel->updateCategory($request, $category);

        // 登録成功のセッション
        session()->flash('status', self::MESSAGES['SUCCESS']['UPDATE_CATEGORY']);

        return to_route('categories.index');
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
        session()->flash('status', self::MESSAGES['SUCCESS']['TOGGLE_PUBLIC']);

        return to_route('categories.index');
    }
}
