<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\User;
use App\Models\UserType;
use DB;
use File;
use LaravelAnalytics;
use DateTime;
use Image;
use Log;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function getDashboard(Request $request) {
        $options = ['dimensions' => 'ga:pagePath', 'sort' => '-ga:pageviews', 'max-results' => '10000'];
        $analyticsData = LaravelAnalytics::performQuery(new DateTime('2016-01-01'), new DateTime('tomorrow'),
            'ga:sessions,ga:pageviews,ga:pageviewsPerSession,ga:bounceRate,ga:avgSessionDuration', $options);

        $firstDayOfMonth = new DateTime($request->get('reportMonth', date('Y-m-01')));
        $firstDayOfNextMonth = (new DateTime($request->get('reportMonth', date('Y-m-01'))))->modify('+32 days');
        $users = User::where('type', UserType::Normal)->get();

        foreach($users as $user) {
            $posts = Post::leftJoin('post_request as pr', 'pr.id', '=', 'post.post_request_id')
                ->where('post.user_id', $user->id)
                ->where('post.created_at', '>=', $firstDayOfMonth->format('Y-m-01'))
                ->where('post.created_at', '<', $firstDayOfNextMonth->format('Y-m-01'))
                ->get(['post.*', 'pr.price_per_post']);

            foreach($posts as $post) {
                $post->analytics = [null, 0];

                foreach($analyticsData->rows as $row) {
                    if(substr($row[0], 1) == $post->slug) {
                        $post->analytics = $row;
                        break;
                    }
                }

                $post->price_per_post = isset($post->price_per_post) ? $post->price_per_post : config('custom.price_per_post');
                if($post->analytics[1] >= config('custom.bonus_2_metric_count')) {
                    $post->price_per_post += config('custom.bonus_2_earning_amount');
                    $post->has_traffic_bonus = true;
                }
                elseif($post->analytics[1] >= config('custom.bonus_1_metric_count')) {
                    $post->price_per_post += config('custom.bonus_1_earning_amount');
                    $post->has_traffic_bonus = true;
                }
            }

            $posts = $posts->filter(function() { return true; })->toArray();
            // sort descending by sessions
            usort($posts, function($a, $b) {
                if ($a['analytics'][1] == $b['analytics'][1]) {
                    return 0;
                }
                return ($a['analytics'][1] < $b['analytics'][1]) ? 1 : -1;
            });

            $user->posts = $posts;
        }

        return view('pages.admin.dashboard')
            ->with('users', $users)
            ->with('billing', $request->get('billing'))
            ->with('reportMonth', $firstDayOfMonth->format('Y-m-01'));
    }
}
