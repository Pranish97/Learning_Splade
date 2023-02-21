<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;

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
        Toast::title('New Category Created Successfully');

        return redirect()->route('categories.index');

    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryStoreRequest $request, Category $category){
        $category->update($request->validated());
        Toast::title('Category Updated Successfully');

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category){
        $category->delete();
        Toast::success('Category Deleted Successfully');

        return redirect()->route('categories.index');
    }

}
