<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * toggle like
     * 
     * @return view
     */
    public function toggleLike(Event $event)
    {
        $like = $event->likes()->where('user_id', Auth::id())->first();
    
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $event->likes()->create(['user_id' => Auth::id()]);
            $liked = true;
        }

        return response()->json([
            'likeCount' => $event->likes()->count(),
            'liked' => $liked
        ]);
    }
}
