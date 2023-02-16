<?php

namespace App\Http\Controllers;
use ProtoneMedia\Splade\SpladeTable;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index',[
            'posts' => SpladeTable::for(Post::class)
                ->column('title', canBeHidden: false, sortable: true,)
                ->withGlobalSearch(columns: ['title'])
                ->column('slug')
                ->column('description')
                ->column('category_id')
                ->paginate(5),
        ]);
    }
}
