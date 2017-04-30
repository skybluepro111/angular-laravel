<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\PostStatus;
use Symfony\Component\HttpFoundation\Request;
use View;
use Auth;

class SiteController extends Controller
{
    public function getHome() {
        $posts = Post::with('author')->with('category')->whereStatus(PostStatus::Enabled)->orderBy('id', 'desc')->take(20)->get();

        return view('pages.page', compact($posts));
    }

    public function getLatestPosts() {
        if (\Request::ajax()) {
            $data = \Input::all();
            return view('partials.' . $data['action']);
        }
    }

    public function getCategoryPage($category) {
        $category = Category::where('name', $category)->first();
        if(!$category) {
            \App::abort(404);
        }

        View::share('current_category', strtolower($category->name));

        return view('pages.page')
            ->with('category', $category);
    }

    public function getSearch(Request $request) {
        $maximumResultCount = Auth::check() ? 1000 : 10;
        $posts = Post::whereStatus(PostStatus::Enabled)->where('title', 'like', '%' . $request->input('s') . '%')->orderBy('id', 'desc')->take($maximumResultCount)->get();

        return view('pages.search-results')->with('posts', $posts);
    }
}
