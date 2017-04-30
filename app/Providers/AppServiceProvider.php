<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share('page', 'page');
        view()->share('current_category', '');
        view()->share('current_page', '');

        view()->composer('partials.related', function($view)
        {
            $view->with('posts', Post::with('author')->with('category')->where('status', 1)->orderByRaw(DB::raw('RAND()'))->take(6)->get());
        });
        view()->composer('partials.you-may-like', function($view)
        {
            $view->with('posts', Post::with('author')->with('category')->where('status', 1)->orderByRaw(DB::raw('RAND()'))->take(9)->get());
        });
        view()->composer('partials.top-stories', function($view)
        {
            $viewData = $view->getData();
            $posts = Post::with('author')->with('category')->where('status', 1);

            if(array_key_exists('category', $viewData)) {
                $posts->where('category_id', $viewData['category']['id']);
            }

            $view->with('posts', $posts->orderBy('id', 'desc')->take(6)->get());
        });

        view()->composer('partials.sidebar-articles', function($view)
        {
            $view->with('posts', Post::with('author')->with('category')->where('status', 1)->orderByRaw(DB::raw('RAND()'))->take(12)->get());
        });

        view()->composer('pages.post', function($view) {
            view()->share('page', 'post');
            $posts = DB::table('post as p')
                    ->join('category as c', 'c.id', '=', 'p.category_id')
                    ->join('user as u', 'u.id', '=', 'p.user_id')
                ->where('p.id', '!=', $view->post->id)
                ->where('p.status', 1)
                ->orderBy('p.id', 'desc')
                ->take(50)
                ->get(['p.*',
                    'c.name as category_name',
                    'u.name as author_name',
                    'u.image as author_image']);

            $post = $posts[array_rand($posts)];

            $view->with('nextPost', $post);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
