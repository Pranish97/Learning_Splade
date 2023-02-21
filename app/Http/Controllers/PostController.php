<?php

namespace App\Http\Controllers;

use App\Models\Category;
use ProtoneMedia\Splade\SpladeTable;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\PostStoreRequest;
use ProtoneMedia\Splade\Facades\Toast;

class PostController extends Controller
{
    public function index()
    {
            $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    Collection::wrap($value)->each(function ($value) use ($query) {
                        $query
                            ->orWhere('title', 'LIKE', "%{$value}%")
                            ->orWhere('slug', 'LIKE', "%{$value}%");
                    });
                });
            });

           $posts = QueryBuilder::for(Post::class)
            ->defaultSort('title')
            ->allowedSorts(['title', 'slug'])
            ->allowedFilters(['title', 'slug','category_id', $globalSearch]);

            $categories = Category::pluck('name', 'id')->toArray();

        return view('posts.index',[
            'posts' => SpladeTable::for($posts)
                ->column('title', canBeHidden: false, sortable: true,)
                ->withGlobalSearch(columns: ['title'])
                ->column('slug', sortable: true)
                ->column('action')
                ->selectFilter('category_id' , $categories)
                ->paginate(),
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
}
