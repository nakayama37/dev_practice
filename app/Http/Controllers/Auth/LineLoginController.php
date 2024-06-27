<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Facades\Mail;

class LineLoginController extends Controller
{
    /**
     * roles
     *
     * @var
     * 
     *  ADD_FRIEND_URL: string,
     * 
     */
    private const ADD_FRIEND_URL = config('services.line.add_friend_url');
    /**
     * lineログイン画面リダイレクト
     * 
     * @return linelogin
     */
    public function redirectToProvider()
    {
       
        return Socialite::driver('line')->redirect();
    }
    /**
     * コールバック
     * 
     * @return view
     */
    public function handleProviderCallback()
    {
        try {

            $userModel = new User();

            $lineUser  = Socialite::driver('line')->stateless()->user();

            // LINEユーザーの情報を取得
            $lineUserId = $lineUser->id;
            // Line 開発コンソール上でメールアドレス同意をえないといけないため 開発環境emailは以下
            $email = Str::random(10) . '@example.com';

            $password = bcrypt(Str::random(10));

            // 既存のユーザーをメールアドレスまたはLINEユーザーIDで検索
            $existingUser = $userModel->getExistingUserByLineLogin($lineUserId, $email);

            if ($existingUser) {

                // 既存ユーザーが存在する場合、LINE IDを更新
                $existingUser->line_id = $lineUserId;
                $existingUser->save();

                Auth::login($existingUser, true);
            } else {
                
                $newUser = $userModel->storeByLineLogin($lineUser, $email, $lineUserId, $password);

                // 確認メール送信
                Mail::to($email)->send(new RegistrationConfirmation($newUser, $password, self::ADD_FRIEND_URL));
                // ログイン
                Auth::login($newUser, true);
            }


            return redirect()->intended('/home');
            
        } catch (Exception $e) {

            return redirect()->route('login')->with('error', 'LINEログインに失敗しました');

        }
       
    }
}