<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Tables\Posts;
use Illuminate\Http\Request;
use App\Http\Requests\PostStoreRequest;
use ProtoneMedia\Splade\Facades\Toast;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index',[
            // 'posts' => SpladeTable::for($posts)
            //     ->column('title', canBeHidden: false, sortable: true,)
            //     ->withGlobalSearch(columns: ['title'])
            //     ->column('slug', sortable: true)
            //     ->column('action')
            //     ->selectFilter('category_id' , $categories)
            //     ->paginate(),
            'posts' => Posts::class,
        ]);
    }

    public function create()
    {
        $category = Category::pluck('name', 'id')->toArray();
        return view('posts.create', compact('category'));
    }

    public function store(PostStoreRequest $request)
    {
        Post::create($request->validated());
        Toast::title('Post Created Successfully');

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $category = Category::pluck('name', 'id')->toArray();
        return view('posts.edit', compact('category', 'post'));
    }

    public function update(PostStoreRequest $request, Post $post){
        $post->update($request->validated());
        Toast::title('Post Updated Successfully');

        return to_route('posts.index');
    }

    public function destroy(Post $post){
        $post->delete();
        Toast::title('Post Deleted Successfully');

        return to_route('posts.index');

    }
}
