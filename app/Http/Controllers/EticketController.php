<?php

namespace App\Http\Controllers;

use App\Models\Eticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EticketController extends Controller
{
    /**
     * Eチケット詳細ページ（本番環境のみ）
     * 
     * @param $id
     * @return view
     */
    public function show($id)
    {
        // eticket の情報を取得
        $eticket = Eticket::findOrFail($id);

        // eticket の詳細ページを表示
        return view('etickets.show', compact('eticket'));
    }
    /**
     * チェックイン（本番環境のみ）
     * 
     * @param $id
     * @return view
     */
    public function checkIn(Eticket $eticket)
    {
        // タイムスタンプを保存して入場済みとマークする
        $eticket->check_in_at = Carbon::now();
        $eticket->save();

        return redirect()->route('etickets.show', $eticket->id)->with('success', '入場が完了しました');
    }
}
