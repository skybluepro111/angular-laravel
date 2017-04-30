<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Extensions;
use App\Models\Post;
use App\Models\PostActivity;
use App\Models\PostActivityType;
use App\Models\PostRequest;
use App\Models\PostService;
use App\Models\PostStatus;
use App\Models\PostStatusPresenter;
use App\Models\PostTransformer;
use App\Models\PostUrl;
use App\Models\UrlHelpers;
use App\Models\UserType;
use App\User;
use Auth;
use DB;
use File;
use Html;
use Image;
use Log;
use JavaScript;
use Symfony\Component\HttpFoundation\Request;
use Session;


class ManagePostController extends Controller
{
    public function getAddEditPost(Request $request, $postId = null)
    {
        $post = [];
        if (!empty($postId)) {
            $post = Post::where('id', $postId)
                ->orderBy('id', 'desc')
                ->first();

            if (empty($post)) return redirect()->to('dashboard/post')->with('message', 'danger|The requested post does not exist.');
            $post->blocks = $post->blockcontent ? unserialize(base64_decode($post->blockcontent)) : [];
            $post->slug = (new PostService())->getUrlByPostId($postId);

            JavaScript::put([
                "post" => $post,
                "blocks" => $post->blocks
            ]);
        } else {
            JavaScript::put([
                "post" => null,
                "blocks" => []
            ]);
        }

        $postActivity = null;
        if ($postId != null) {
            $postActivity = PostActivity::where('post_id', $postId)->orderBy('created_at', 'desc')->get();

            if ($postActivity) {
                foreach ($postActivity as $activity) {
                    $user = User::find($activity->user_id);

                    if ($user) {
                        $activity->user = $user;
                    }
                }
            }
        }

        if(isset($post->post_request_id)) {
            $postRequest = PostRequest::where('id', $post->post_request_id)->first();
        }
        else {
            $postRequest = $request->get('post_request_id') ? PostRequest::where('id', $request->get('post_request_id'))->first() : null;
        }

        return view('pages.admin.add-edit-post')
            ->with('post', $post)
            ->with('categories', Category::get())
            ->with('postRequest', $postRequest)
            ->with('postActivity', $postActivity);
    }

    public function postUploadImage(Request $request)
    {
        $this->validate($request, [
            'imagecontent' => 'image'
        ]);

        if ($request->hasFile('imagecontent') && $request->file('imagecontent')->isValid()) {

            Log::info('Payload included "image" parameter, and the image is valid');
            $filename = Extensions::getChars(32) . '.' . $request->file('imagecontent')->getClientOriginalExtension();
            $request->file('imagecontent')->move(
                public_path() . '/assets/front/img/', $filename
            );
            return response()->json(['success' => 'true', 'url' => url('assets/front/img/' . $filename)]);
        } else {
            return response()->json(['message' => 'Error'], 400);
        }
    }

    /**
     * @param Request $request
     * @param null $postId
     * @return mixed
     */
    public function postAddEditPost(Request $request, $postId = null)
    {
        $isNewPost = false;
        $originalStatus = -1;

        if ($postId == null) {
            $isNewPost = true;
            $post = new Post();
            $post['slug'] = str_slug($request->input('title'));

            if (Auth::user()->type == UserType::Administrator)
                $post['status'] = $request->get('status');
            else
                $post['status'] = PostStatus::Pending;
            $post['user_id'] = Auth::user()->getAuthIdentifier();
        } else {
            $post = Post::find($postId);
            $originalStatus = $post->status;

            /*if (Auth::user()->type == UserType::Normal && ($post->user_id != \Illuminate\Support\Facades\Auth::user()->getAuthIdentifier() || $post->status == PostStatus::Enabled)) {
                return redirect()->to('dashboard/post/list');
            }*/

            if ($request->input('status') == PostStatus::Deleted && $post['status'] != PostStatus::Deleted) {
                $post['deleted_at'] = Extensions::getDate();
            } else if ($request->input('status') != PostStatus::Deleted && $post['status'] == PostStatus::Deleted) {
                $post['deleted_at'] = null;
            }

            $post['status'] = $request->input('status');
        }

        $post['title'] = $request->input('title');
        $originalSlug = $post['slug'];
        $newSlug = null;

        if($isNewPost || $post['slug'] != $request->get('url')) {
            $newSlug = $request->get('url', $post['slug']);
            while (DB::table('post_url')->where('url', $newSlug)->whereNull('deleted_at')->first()) {
                $newSlug .= '-';
            }
        }

        $post['slug'] = $newSlug ? $newSlug : $post['slug'];

        if($request->get('post_request_id')) {
            $post['post_request_id'] = $request->get('post_request_id');
        }

        if ($request->get('comment')) {
            PostActivity::create([
                'post_id' => $post->id,
                'type' => PostActivityType::AddedComment,
                'comment' => $request->get('comment'),
                'user_id' => Auth::user()->getAuthIdentifier()]);
        }

        $post['description'] = $request->get('description');
        $post['category_id'] = $request->input('category_id', 1);
        $post->save();

        if ($isNewPost) {
            PostActivity::create(['post_id' => $post->id, 'type' => PostActivityType::CreatedPost, 'comment' => Auth::user()->name . ' created the post.', 'user_id' => Auth::user()->getAuthIdentifier()]);
        }

        if ($originalStatus != -1 && $originalStatus != $post->status) {
            PostActivity::create(['post_id' => $post->id, 'type' => PostActivityType::ChangedStatus, 'comment' => Auth::user()->name . ' changed the status to "' . PostStatusPresenter::present($post->status) . '".', 'user_id' => Auth::user()->getAuthIdentifier()]);

            $postRequest = PostRequest::where('id', $post->post_request_id)->first();
            $postActivity = PostActivity::where('post_id', $post->id)->where('type', PostActivityType::PublishedRequestedPost)->first();
            if($postRequest && !$postActivity) {
                $postAuthor = User::where('id', $post->user_id)->first();
                PostActivity::create(['post_id' => $post->id, 'type' => PostActivityType::PublishedRequestedPost, 'comment' => $postAuthor->name . ' was awarded a bonus for completing an article request.', 'user_id' => Auth::user()->getAuthIdentifier()]);
                if(!$postRequest->recurring) {
                    $postRequest->status = 1; // fulfilled
                    $postRequest->save();
                }
            }
        }

        if($isNewPost || $originalSlug != $request->get('url')) {
            PostUrl::create(['post_id' => $post->id, 'url' => $post['slug']]);

            if($originalSlug != $request->get('url')) {
                PostActivity::create(['post_id' => $post->id, 'type' => PostActivityType::ChangedStatus, 'comment' => Auth::user()->name . ' added a new URL for this post: "' . url($post['slug']) . '"', 'user_id' => Auth::user()->getAuthIdentifier()]);
            }
        }

        $postTransformer = new PostTransformer();
        $blocks = json_decode($request->input('blocks'));
        $content = [];

        for ($i = 0; $i < count($blocks); $i++) {

            if ($blocks[$i]->type == "text" || $blocks[$i]->type == "embed") {
                $newcontent = $blocks[$i]->content;
                $newcontent = str_replace(' &#63;', '&#63;', $newcontent);
                $newcontent = str_replace(' &#33;', '&#33;', $newcontent);
            } elseif ($blocks[$i]->type == "image") {
                $newcontent = "";

                if (!empty($blocks[$i]->title)) {
                    $newcontent = str_replace(' &#63;', '&#63;', $blocks[$i]->title);
                    $newcontent = str_replace(' &#33;', '&#33;', $newcontent);
                    $newcontent .= "<h2>" . $newcontent . "</h2>";
                }

                if (!empty($blocks[$i]->description)) {
                    $newcontent = str_replace(' &#63;', '&#63;', $blocks[$i]->description);
                    $newcontent = str_replace(' &#33;', '&#33;', $newcontent);
                    $newcontent .= "<p>" . $newcontent . "</p>";
                }

                $nc = '<img src="#" />';
                $transformed = $postTransformer->uploadFileToS3($blocks[$i]->url, $post->id, false);
                if ($transformed) {
                    $blocks[$i]->url = $transformed;
                    $nc = '<img src="' . $transformed . '" />';
                    $newcontent .= $nc;

                } else {
                    $newcontent .= $nc;
                }

                if (!empty($blocks[$i]->source) && !empty($blocks[$i]->sourceurl))
                    $newcontent .= "<span class='source'><span>via:</span> <a href='" . $blocks[$i]->sourceurl . "' target='_blank'>" . $blocks[$i]->source . "</a></span>";
                elseif (!empty($blocks[$i]->source) && empty($blocks[$i]->sourceurl))
                    $newcontent .= "<span class='source'><span>via:</span> " . $blocks[$i]->source . "</span>";
                elseif (empty($blocks[$i]->source) && !empty($blocks[$i]->sourceurl))
                    $newcontent .= "<span class='source'><span>via:</span> <a href='" . $blocks[$i]->sourceurl . "' target='_blank'>Source</a></span>";
            }

            array_push($content, $newcontent);
        }

        $post['content'] = base64_encode(serialize($content));
        $post['blockcontent'] = base64_encode(serialize($blocks));

        $post->save(); // Saving now to get an ID for naming the images

        /*
         * thumbnail processing
         */
        if ($request->get('thumbnail_output')) {
            $thumb = Image::make($request->get("thumbnail_output"));
            $filename = Extensions::getChars(6) . '_' . $post->id . '.jpg';
            $thumb->save(public_path() . '/' . config('custom.thumbs-directory') . $filename);
            $post['image'] = $postTransformer->uploadFileToS3(asset(config('custom.thumbs-directory') . $filename), $postId, true);
        }

        if ($request->hasFile('preview_thumbnail') && $request->file('preview_thumbnail')->isValid()) {
            $post['preview_thumbnail'] = $postTransformer->uploadFileToS3($request->file('preview_thumbnail')->getRealPath(), $postId, true);
        } else if ($request->get('preview_thumbnail_url')) {
            $post['preview_thumbnail'] = $request->get('preview_thumbnail_url');
        }

        $post->save();
        $message = 'success|Post saved successfully.';
        //$post->blocks = unserialize(base64_decode($post->content));
        return redirect()->to('dashboard/post/' . $post->id)
            ->with('post', $post)
            ->with('categories', Category::get())
            ->with('message', $message);
    }

    public function getPostList(Request $request)
    {
        $postStatesShown = [PostStatus::Enabled, PostStatus::RequiresRevision, PostStatus::ReadyForReview, PostStatus::Pending];

        $statusFilter = $request->get('statusFilter', Session::get('statusFilter', null));
        if ($statusFilter != null) {
            Session::put('statusFilter', $statusFilter);

            switch ($statusFilter) {
                case 0:
                    $postStatesShown = [PostStatus::Pending];
                    break;
                case 1:
                    $postStatesShown = [PostStatus::Enabled];
                    break;
                case 3:
                    $postStatesShown = [PostStatus::ReadyForReview];
                    break;
                case 4:
                    $postStatesShown = [PostStatus::RequiresRevision];
                    break;
            }
        }

        $postsPerPage = $request->get('postsPerPageFilter', Session::get('postsPerPageFilter', 20));
        Session::put('postsPerPageFilter', $postsPerPage);

        $posts = Post::join('user as u', 'u.id', '=', 'post.user_id')
            ->join('category as c', 'c.id', '=', 'post.category_id')
            ->whereIn('post.status', $postStatesShown)
            ->orderBy('id', 'desc')
            ->select(['post.*', 'u.name as author_name', 'u.email', 'u.image as author_image', 'c.name as category_name'])
            ->paginate($postsPerPage);

        $numberOfPostsRequiringRevision = 0;
        foreach ($posts as $post) {
            if ($post->status == PostStatus::RequiresRevision && Auth::user()->getAuthIdentifier() == $post->user_id)
                $numberOfPostsRequiringRevision++;
        }

        return view('pages.admin.post-list')
            ->with(['posts' => $posts, 'numberOfPostsRequiringRevision' => $numberOfPostsRequiringRevision]);
    }

    public function getPostActivityDelete($id)
    {
        PostActivity::where('id', $id)->delete();

        return redirect()->back();
    }

    public function getAddPostRequest($postRequestId = null)
    {
        $postRequest = $postRequestId ? PostRequest::where('id', $postRequestId)->first() : null;

        return view('pages.admin.add-edit-post-request')
            ->with('postRequest', $postRequest);
    }

    public function postAddPostRequest(Request $request, $postRequestId = null)
    {
        if ($postRequestId == null) {
            $postRequest = new PostRequest();
        } else {
            $postRequest = PostRequest::find($postRequestId);

            if ($request->input('status') == PostStatus::Deleted && $postRequest['status'] != PostStatus::Deleted) {
                $postRequest->delete();
            } else if ($request->input('status') != PostStatus::Deleted && $postRequest['status'] == PostStatus::Deleted) {
                $postRequest['deleted_at'] = null;
            }

        }

        $postRequest['title'] = $request->input('title');
        $postRequest['description'] = $request->input('description');
        $postRequest['price_per_post'] = $request->input('price_per_post');
        $postRequest['recurring'] = $request->get('recurring') == 'on' ? 1 : 0;
        $postRequest['status'] = $request->input('status');

        $postRequest->save();

        return redirect()->to('dashboard/post/request/' . $postRequest->id);
    }

    public function getPostRequestList()
    {
        $postRequests = PostRequest::leftJoin('user as u', 'u.id', '=', 'post_request.user_id')
                            ->where('post_request.status', 0)->get(['post_request.*', 'u.name as user_name', 'u.image as user_image']);

        return view('pages.admin.post-request-list')
            ->with('postRequests', $postRequests);
    }

    public function postAssignPostRequest($postRequestId) {
        $postRequest = PostRequest::where('id', $postRequestId)->first();
        if($postRequest->user_id != null) {
            $postRequest = PostRequest::where('id', $postRequestId)->first();
            $postRequest->user_id = null;
            $postRequest->save();
            return redirect()->to('dashboard/post/request/list');
        }
        else {
            PostRequest::where('id', $postRequestId)->update(['user_id' => Auth::user()->getAuthIdentifier()]);
            return redirect()->to('dashboard/post?post_request_id=' . $postRequestId);
        }
    }
}
