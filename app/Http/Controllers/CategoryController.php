<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use ProtoneMedia\Splade\SpladeTable;

class CategoryController extends Controller
{
    public function index()
    {

        return view('categories.index',[
            'categories' => SpladeTable::for(Category::class)
                ->column('name', canBeHidden: false, sortable: true,)
                ->withGlobalSearch(columns: ['name', 'slug'])
                ->column('slug')
                ->column('action')
                ->paginate(5),
        ]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index');

    }
}
