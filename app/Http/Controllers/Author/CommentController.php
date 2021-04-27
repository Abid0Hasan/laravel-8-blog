<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $posts  = Auth::user()->posts;
        return view('author.comments', compact('posts'));
    }

    public function destroy($id)
    {
        $comments = Comment::findOrFail($id);
        if ($comments->post->user->id == Auth::id()){
            $comments->delete();
            Toastr::success('Comment Successfully Deleted','Success');
        }else{
            Toastr::error('You are not Authorise to delete this comment','Access Denied !!!');
        }
        return redirect()->back();
    }

}
