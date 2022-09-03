<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\API\StorePostRequest;
use App\Http\Requests\API\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => true, 'posts' => Post::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $validatedRequest = $request->validated();
        $validatedRequest['image'] = $request->file('image')->store('post/image');

        $post = Post::create($validatedRequest);
        return response()->json(['status' => true, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedRequest = $request->validated();
        
        if ($request->hasFile('image')) {
            Storage::delete($post->image);
            $validatedRequest['image'] = $request->file('image')->store('post/image');
        }

        $post->update($validatedRequest);
        return response()->json(['status' => true, 'message' => 'Data successfully updated', 'post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Storage::delete($post->image);
        $post->delete();

        return response()->json(['status' => true, 'message' => 'Data successfully deleted']);
    }
}
