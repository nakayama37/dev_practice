<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Event;

class CommentController extends Controller
{
    /**
     * コメント一覧
     * 
     * @return view
     */
    public function index($eventId)
    {
        $comments = Comment::where('event_id', $eventId)->with('user')->get();

        return response()->json([
            'comments' => $comments,
            'userId' => Auth::id()
        ]);
    }

    /**
     * コメント登録
     * 
     * @return view
     */
    public function store(StoreCommentRequest $request, $eventId)
    {
        $commentModel = new Comment(); 
        $commentModel->createComment($request, $eventId);
       
        $comments = Comment::where('event_id', $eventId)->with('user')->get();

        return response()->json([
            'comments' => $comments,
            'userId' => Auth::id()
        ]);
    }

    /**
     * コメント編集
     * 
     * @return view
     */
    public function destroy(Comment $comment)
    {

        // ユーザーがコメントの所有者であることを確認
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // コメントを削除
        $comment->delete();

        return response()->json(['success' => true]);
    }
   
}
