<?php

namespace App\Models;

use DB;
use LaravelAnalytics;
use DateTime;
use Auth;

class PostService
{
    public static function get($category = null, $numberOfPosts = 10)
    {
        $posts = Post::where('status', PostStatus::Enabled);
        if ($category != null) {
            $posts->where('category', $category);
        }

        return $posts->take($numberOfPosts)
            ->orderBy('id')->get();
    }

    public static function getRandomUrl()
    {
        $post = head(DB::select(DB::raw('SELECT r1.slug
                                         FROM post AS r1
                                            JOIN (SELECT (RAND() * (SELECT MAX(id)
                                                  FROM post where status = ' . PostStatus::Enabled . ')) as id)
                                            AS r2
                                         WHERE r1.id >= r2.id
                                         AND r1.status = ' . PostStatus::Enabled . '
                                          ORDER BY r1.id ASC LIMIT 1')));

        return url($post->slug);
    }

    public function updateClicksAllTime()
    {
        $posts = Post::get();

        $options = ['dimensions' => 'ga:landingPagePath', 'sort' => '-ga:sessions', 'max-results' => '10000'];
        $analyticsData = LaravelAnalytics::performQuery(new DateTime('2016-01-01'), new DateTime('tomorrow'),
            'ga:sessions', $options);

        foreach ($analyticsData['rows'] as $row) {
            foreach ($posts as $post) {
                if ($post->slug == trim($row[0], '/')) {
                    $oneMonthAgo = (new DateTime())->modify('-1 month');
                    $postCreationDate = new DateTime($post->created_at);
                    if (!$post->bonus_1_achieved_at &&
                        $post->clicks_all_time >= config('custom.bonus_1_metric_count') &&
                        $postCreationDate < $oneMonthAgo) {
                        $post->bonus_1_achieved_at = date('Y-m-d H:i:s');
                    }
                    if (!$post->bonus_2_achieved_at &&
                        $post->clicks_all_time >= config('custom.bonus_1_metric_count') &&
                        $postCreationDate < $oneMonthAgo) {
                        $post->bonus_2_achieved_at = date('Y-m-d H:i:s');
                    }

                    $post->clicks_all_time = $row[1];
                    $post->save();
                    break;
                }
            }
        }
    }

    public function getPostBySlug($slug, $preview) {
        $post = Post::join('post_url as pu', 'pu.post_id', '=', 'post.id')
            ->with('category')
            ->where('pu.url', $slug);

        if(!$preview || !Auth::check()) {
            $post->whereStatus(PostStatus::Enabled);
        }

        return $post->first();
    }

    public function getUrlByPostId($postId)
    {
        return PostUrl::where('post_id', $postId)->orderBy('id', 'desc')->value('url');
    }
}