<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use File;
use Image;
use Log;
use Auth;
use Html;
use DateTime;
use LaravelAnalytics;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Request;

class AnalyticsController extends Controller
{
    public function getAnalytics(Request $request)
    {
        if($request->get('dateRange')) {
            $dateParts = explode(' - ', $request->input('dateRange'));
            $startDate = DateTime::createFromFormat('m/d/Y', $dateParts[0]);
            $endDate = DateTime::createFromFormat('m/d/Y', $dateParts[1]);
        }
        else {
            $startDate = (new DateTime())->modify('-7 days');
            $endDate = new DateTime();
        }

        $options = ['dimensions' => 'ga:pagePath', 'sort' => '-ga:pageviews', 'max-results' => '1000'];
        $analyticsData = LaravelAnalytics::performQuery($startDate, $endDate,
        'ga:sessions,ga:pageviews,ga:pageviewsPerSession,ga:bounceRate,ga:avgSessionDuration', $options);

        $posts = Post::with('author')->get();

        foreach($posts as $post) {
            foreach($analyticsData->rows as $row) {
                if(substr($row[0], 1) == $post->slug) {
                    $post->analytics = $row;
                    break;
                }
            }
        }

        $posts = $posts->filter(function($post) { return !empty($post->analytics); })->toArray();

        // sort descending by sessions
        usort($posts, function($a, $b) {
            if ($a['analytics'][1] == $b['analytics'][1]) {
                return 0;
            }
            return ($a['analytics'][1] < $b['analytics'][1]) ? 1 : -1;
        });


        return view('pages.admin.analytics')
            ->with('posts', $posts);
    }
}


