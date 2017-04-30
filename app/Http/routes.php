<?php

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', 'DashboardController@getDashboard');
    Route::any('dashboard/post/list', 'ManagePostController@getPostList');
    Route::post('dashboard/post/delete', 'ManagePostController@postDeletePost');
    Route::post('dashboard/post/uploadimage', 'ManagePostController@postUploadImage');
    Route::get('dashboard/post/request/list', 'ManagePostController@getPostRequestList');
    Route::post('dashboard/post/request/assign/{postRequestId}', 'ManagePostController@postAssignPostRequest');
    Route::get('dashboard/post/request/{postRequestId?}', 'ManagePostController@getAddPostRequest');
    Route::post('dashboard/post/request/{postRequestId?}', 'ManagePostController@postAddPostRequest');
    Route::get('dashboard/post/{postId?}', 'ManagePostController@getAddEditPost');
    Route::post('dashboard/post/{postId?}', 'ManagePostController@postAddEditPost');
    Route::get('dashboard/user/list', 'ManageUserController@getUserList');
    Route::get('dashboard/user/settings', 'UserController@getSettings');
    Route::post('dashboard/user/settings', 'UserController@postSettings');
    Route::post('dashboard/user/password', 'UserController@postUpdatePassword');
    Route::get('dashboard/user/{userId?}', 'ManageUserController@getAddEditUser');
    Route::post('dashboard/user/{userId?}', 'ManageUserController@postSaveUser');
    Route::get('dashboard/analytics', 'AnalyticsController@getAnalytics');
    Route::get('dashboard/post-activity/{id}/delete', 'ManagePostController@getPostActivityDelete');
});

Route::group(['middleware' => ['web']], function () {
	Route::get('/', 'SiteController@getHome');

    Route::get('login', 'Auth\AuthController@getLogin');
    Route::post('login', 'Auth\AuthController@postLogin');

    Route::get('password-reset', 'Auth\PasswordController@getEmail');
    Route::post('password-reset', 'Auth\PasswordController@postEmail');

    Route::get('password-set', 'Auth\PasswordController@getReset');
    Route::post('password-set', 'Auth\PasswordController@postReset');

    Route::get('logout', 'Auth\AuthController@getLogout');

    Route::get('search', 'SiteController@getSearch');

    Route::get('terms', function () {
        View::share('page', 'landing');
        View::share('current_page', 'terms');
        return view('pages.terms');
    });
    Route::get('privacy', function () {
        View::share('page', 'landing');
        View::share('current_page', 'privacy');
        return view('pages.privacy');
    });
    Route::get('copyright', function () {
        View::share('page', 'landing');
        View::share('current_page', 'copyright');
        return view('pages.copyright');
    });
    Route::get('contact', function () {
        View::share('page', 'landing');
        View::share('current_page', 'contact');
        return view('pages.contact');
    });
    Route::get('404', function () {
        View::share('page', '404');
        return view('errors.404');
    });
    Route::get('/category/{category}', 'SiteController@getCategoryPage');
});

Route::get('v1/posts/feed', function(\Illuminate\Http\Request $request) {
    $posts = \App\Models\Post::where('status', \App\Models\PostStatus::Enabled)
        ->orderBy('id', 'desc')
        ->take($request->get('limit', 100))
        ->get(['title', 'description', 'slug as url' , 'image', 'preview_thumbnail']);

    foreach($posts as $post) {
        $post->url = 'http://postize.com/' . $post->url;
        $post->image = $post->preview_thumbnail != null ? $post->preview_thumbnail : $post->image;
    }

    return $posts;
});

Route::get('{slug}/{pageNumber?}', 'PostController@getPost');