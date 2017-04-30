<?php

namespace App\Http\Controllers;

use App\Models\AdSets;
use App\Models\Post;
use App\Models\PostService;
use App\Models\PostStatus;
use App\Models\UrlHelpers;
use Agent;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use View;
use DB;

class PostController extends Controller
{
    public function getPost(Request $request, $slug, $pageNumber = 1)
    {
        $preview = $request->get('__preview') == 1;
        $postService = new PostService();
        $post = $postService->getPostBySlug($slug, $preview);

        if (empty($post)) {
            \App::abort(404);
        }

        View::share('current_category', strtolower($post->category->name));

        $relatedPostsSidebar = Post::with('author')
            ->with('category')
            ->where('id', '!=', $post->id)
            ->where('status', PostStatus::Enabled)
            ->orderByRaw(DB::raw('RAND()'))
            ->take(12)
            ->get();

        $relatedPostsBottom = array_slice($relatedPostsSidebar->toArray(), 0, 3);

        $blockContent = unserialize(base64_decode($post->blockcontent));

        $numberOfPagesInArticle = 1;
        $pages = [];
        $currentPageContent = [];
        $utmSourceParameter = $request->get('utm_source', Session::get('source'));
        if($utmSourceParameter) {
            Session::put('source', $utmSourceParameter);
        }

        $mediaBlocksShown = 0;
        for ($i = 0; $i < count($blockContent); $i++) {
            if ($utmSourceParameter && $blockContent[$i]->type == 'pagebreak') {
                $pages[] = $currentPageContent;
                $currentPageContent = [];
            } else {
                if ($blockContent[$i]->type == 'image') {
                    $blockContent[$i]->content = '';

                    /*if(!$preview && $mediaBlocksShown == 3) { // Show this ad on the second block, between the image description and actual image
                        $blockContent[$i]->content .= '<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=d7f375bb-9519-45d8-b71b-1047d18648a9&storeId=postize-20"></script>';
                    }*/

                    if (!empty($blockContent[$i]->title))
                        $blockContent[$i]->content .= '<h2>' . $blockContent[$i]->title . '</h2>';

                    if (!empty($blockContent[$i]->description))
                        $blockContent[$i]->content .= '<p>' . $blockContent[$i]->description . '</p>';

                    if(!$preview && $mediaBlocksShown == 1) { // Show this ad on the second block, between the image description and actual image
                        $blockContent[$i]->content .= '<div class="row" style="display:block; width: 100%; height: auto;margin-bottom: 20px">

                                    <div class="ad-content">
                                        <span class="ad-disclaimer">ADVERTISEMENT</span>
                                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                        <!-- Postize_InPost_Responsive_Rectangle_2 -->
                                        <ins class="adsbygoogle"
                                             style="display:block"
                                             data-ad-client="ca-pub-1766805469808808"
                                             data-ad-slot="5412934179"
                                             data-ad-format="rectangle"></ins>
                                        <script>
                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </div>
                                </div>';
                    }
                    elseif(!$preview && $mediaBlocksShown == 2) {
                        $blockContent[$i]->content .= '<div class="row" style="display:block; width: 100%; height: auto;margin-bottom: 20px">
                                    <div class="ad-content">
                                                                            <span class="ad-disclaimer">ADVERTISEMENT</span>
                                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                    <!-- Postize_InPost_Responsive_Rectangle_3 -->
                                    <ins class="adsbygoogle"
                                         style="display:block"
                                         data-ad-client="ca-pub-1766805469808808"
                                         data-ad-slot="6889667379"
                                         data-ad-format="rectangle"></ins>
                                    <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                    </div></div>';
                    }

                    $blockContent[$i]->content .= '<img src="' . $blockContent[$i]->url . '" />';

                    if (!empty($blockContent[$i]->source) && !empty($blockContent[$i]->sourceurl)) {
                        $blockContent[$i]->content .= '<span class="source"><span>via:</span><a href="' .
                            $blockContent[$i]->sourceurl . '" target="blank">' . $blockContent[$i]->source . '</a></span>';
                    }

                    if(in_array($blockContent[$i]->type, ['image', 'embed'])) {
                        $mediaBlocksShown++;
                    }
                }

                $currentPageContent[] = $blockContent[$i];
            }

            if ($i == count($blockContent) - 1) {
                $pages[] = $currentPageContent;
                $currentPageContent = [];
            }
        }

        if ($pageNumber == 0) $pageNumber = 1;
        if ($pageNumber > count($pages))
            return redirect()->to('/');

        $post->blocks = !$utmSourceParameter ? $pages[0] : $pages[$pageNumber - 1]; // Uses $pages[0] because without a utm_source parameter, the entire article is shown as 1 page.
        $post->is_last_page = count($pages) == $pageNumber;

        $nextPageUrl = url($slug) . '/' . ($pageNumber + 1);
        /*$urlParts = parse_url($request->fullUrl());
        if (!empty($urlParts['query'])) {
            $nextPageUrl .= '?' . $urlParts['query'];
        }*/
        
        $adSet = AdSets::belowArticle();
        $randomNumber = mt_rand(1, 100);
        $advertisements = [];

        if($randomNumber > 25) {
            $advertisements['below-article']['code'] = $adSet['below-article-revcontent'];
            $advertisements['below-article']['name'] = 'revcontent';

        }
        else {
            $advertisements['below-article']['code'] = $adSet['below-article-contentad'];
            $advertisements['below-article']['name'] = 'contentad';
        }

        return view('pages.post')
            ->with('post', $post)
            ->with('pageNumber', $pageNumber)
            ->with('nextPageUrl', $nextPageUrl)
            ->with('relatedPosts', $relatedPostsBottom)
            ->with('relatedPostsSidebar', $relatedPostsSidebar)
            ->with('preview', $preview)
            ->with('advertisements', $advertisements)
            ->with('mobile', Agent::isMobile() || Agent::isTablet());
    }
}