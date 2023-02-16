<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
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
                ->paginate(5),
        ]);


    }
}
