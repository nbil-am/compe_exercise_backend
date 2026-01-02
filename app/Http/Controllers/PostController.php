<?php

namespace App\Http\Controllers;

use App\Models\likes;
use App\Models\Posts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate(['content' => ['required', 'string']]);
        $user = $request->user();
        // return response()->json(['post' => $validated['content'], 'user' => $user], 201);


        $post = $user->Posts()->create(['content' => $validated['content']]);

        return response()->json(['post' => $post, 'user' => $user], 201);
    }
    public function getAll()
    {
        $posts = Posts::with(relations: 'User')->get();

        return $posts->map(
            fn($post) =>
            [
                'id' => $post->id,
                'user_id' => $post->user_id,
                'content' => $post->content,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'author' => $post->user, // 🔥 rename di sini
            ]
        );

    }
    public function edit(Request $request,$id)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000']
        ]);

        $post = Posts::findOrFail($id);
        $user = $request->user();

        if ($post->user_id !== $user->id) {
            return response()->json(['error' => 'not the author'], 403);
        }

        $post->update(['content' => trim($validated['content'])]);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }
    public function delete(Request $req,$id)
    {
        $post = Posts::findOrFail( $id);
        $user = $req->user();
         if($post->user_id != $user->id) {
            return response()->json(['error'=>'not the author'],403);
        }
        $post->delete();
        return ['message'=>'Post deleted successfully'];
    }
    public function like(Request $req)
    {
        $validated = $req->validate(['id'=>['required','exists:posts,id']]);
        $user = $req->user();
        $liked = likes::where('post_id',$validated['id'])->where('user_id',$user->id)->first();
        if($liked) {
            return ['error' => 'you have liked it'];
        }
        $like = $user->likes()->create(['post_id'=>$validated['id']]);
        return ['message' => 'post liked successfully',$liked];
    }
    public function comment(Request $req)
    {
        $validated = $req->validate(['post_id'=>['required'],'comment'=>['required','string']]);
        $user = $req->user();
        $comment= $user->comments()->create(['post_id'=>$validated['post_id'],'comment'=>$validated['comment']]);
        return ['Message'=>'Comment added successfully'];
    }
}
