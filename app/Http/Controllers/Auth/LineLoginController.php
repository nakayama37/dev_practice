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
    public function redirectToProvider()
    {
       
        return Socialite::driver('line')->redirect();
    }

    public function handleProviderCallback()
    {
        try {

            $lineUser  = Socialite::driver('line')->stateless()->user();

            // LINEユーザーの情報を取得
            $lineUserId = $lineUser->id;
            // Line 開発コンソール上で同意をえないといけないため 開発環境は以下
            // $email = Str::random(10) . '@example.com';
            $email = 'daijirou.makabe@gmail.com';
            // $password = bcrypt(Str::random(10));
            $password = 'password1234';
            $role = 9;

            // 既存のユーザーをメールアドレスまたはLINEユーザーIDで検索
            $existingUser = User::where('email', $email)
                ->orWhere('line_id', $lineUserId)
                ->first();

            if ($existingUser) {

                // 既存ユーザーが存在する場合、LINE IDを更新
                $existingUser->line_id = $lineUserId;
                $existingUser->save();

                Auth::login($existingUser, true);
            } else {
                
                $newUser = User::create([
                    'name' => $lineUser->name ?? $lineUser->nickname,
                    'email' => $email,
                    'line_id' => $lineUserId,
                    'password' => $password,
                    'role' => $role,
                    
                ]);

                $addFriendUrl = config('services.line.add_friend_url');

                // 確認メール送信
                Mail::to($email)->send(new RegistrationConfirmation($newUser, $password, $addFriendUrl));
                
                Auth::login($newUser, true);
            }


            return redirect()->intended('/home');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'LINEログインに失敗しました');
        }
       
    }
}