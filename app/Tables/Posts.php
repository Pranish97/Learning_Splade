<?php

namespace App\Tables;

use App\Models\Post;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Category;

class Posts extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Post::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
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

        $table
        ->column('title', canBeHidden: false, sortable: true,)
        ->withGlobalSearch(columns: ['title'])
        ->column('slug', sortable: true)
        ->column('action',  exportAs: false)
        ->selectFilter('category_id' , $categories)
        ->export(label: 'Post Excel')
        ->paginate();

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
