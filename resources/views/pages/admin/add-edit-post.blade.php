@extends('layouts.dashboard')

@section('title', !empty($post) ? ' - Edit: ' . $post->title : ' - New Post')

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='{{ asset('assets/plugins/editors/textangular/textAngular.css') }}'>

    <style type="text/css">
        .post-statistics span {
            font-size: 1.5em !important;
        }

        .post-statistics ul {
            list-style: none;
        }

        ul.enlarge {
            list-style-type: none; /*remove the bullet point*/
            margin-left: 0;
        }

        ul.enlarge li {
            display: inline-block; /*places the images in a line*/
            position: relative;
            z-index: 0; /*resets the stack order of the list items - later we'll increase this*/
            margin: 10px 40px 0 20px;
        }

        ul.enlarge img {
            background-color: #eae9d4;
            padding: 6px;
            -webkit-box-shadow: 0 0 6px rgba(132, 132, 132, .75);
            -moz-box-shadow: 0 0 6px rgba(132, 132, 132, .75);
            box-shadow: 0 0 6px rgba(132, 132, 132, .75);
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

        ul.enlarge span {
            position: absolute;
            left: -9999px;
            background-color: #eae9d4;
            padding: 10px;
            font-family: 'Droid Sans', sans-serif;
            font-size: .9em;
            text-align: center;
            color: #495a62;
            -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, .75));
            -moz-box-shadow: 0 0 20px rgba(0, 0, 0, .75);
            box-shadow: 0 0 20px rgba(0, 0, 0, .75);
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
        }

        ul.enlarge li:hover {
            z-index: 50;
            cursor: pointer;
        }

        ul.enlarge span img {
            padding: 2px;
            background: #ccc;
        }

        ul.enlarge li:hover span {
            top: -300px; /*the distance from the bottom of the thumbnail to the top of the popup image*/
            left: -20px; /*distance from the left of the thumbnail to the left of the popup image*/
        }

        ul.enlarge li:hover:nth-child(2) span {
            left: -100px;
        }

        ul.enlarge li:hover:nth-child(3) span {
            left: -200px;
        }

    </style>
@endsection

@section('content')
    <form class="form-horizontal"
          action="{{ url('dashboard/post' . (!empty($post) ? '/' . $post->id : '')) }}"
          method="post"
          enctype="multipart/form-data"
          id="addEditForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div ng-app="MethodizeEditor" ng-controller="MethodizeController as PCTRL" ng-init="PCTRL.init()"
             id="MethodizeEditor">
            <div class="ng-cloak alert alert-warning alert-styled-left"
                 ng-if="::PCTRL.post.id && PCTRL.blocks.length == 0">
                <span class="text-semibold">Warning! </span>
                This post has been created with an older version of the editor so it's content blocks can't be edited.
                <br>If you want to edit this post, you'll have to recreate the blocks using the new editor.
                <br>However, this post will still be displayed normally on the frontend so immediate action isn't
                required.
            </div>
            @if(isset($postRequest))
                <div class="alert alert-success alert-styled-left">
                    <span class="text-semibold">Awesome! </span>
                    You've created this post from the post request screen, great! As thanks, this article will earn you <strong>${{ number_format($postRequest->price_per_post, 2) }}!</strong><br /><br />
                    <strong style="text-decoration: underline">Title: </strong><strong></strong>{{$postRequest->title }}</strong></strong><br />
                    <strong style="text-decoration: underline">Description: </strong>{{$postRequest->description}}
                </div>
                <input type="hidden" name="post_request_id" value="{{$postRequest->id}}" />
            @endif
            @include('partials.alert-message')
            @if(!empty($post) && !empty($postActivity) && count($postActivity) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info panel-bordered">
                            <div class="panel-heading">
                                <h6 class="panel-title">Post Activity and Comments<a class="heading-elements-toggle"><i
                                                class="icon-more"></i></a></h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Type</th>
                                            <th>Activity</th>
                                            <th class="text-center">User</th>
                                            <th class="text-center">Date</th>
                                            @if(Auth::user()->type == \App\Models\UserType::Administrator)
                                                <th class="text-center">Actions</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($postActivity as $activity)
                                            <tr>
                                                <td class="text-center">
                                                    @if($activity->type == \App\Models\PostActivityType::AddedComment)
                                                        <i class="fa fa-comment fa-3x" style="color: #a6e1ec"></i><br/>
                                                        Comment
                                                    @else
                                                        <i class="fa fa-file-text-o fa-3x"></i><br/>
                                                        Activity
                                                    @endif
                                                </td>
                                                <td style="width:80%">
                                                    {{ $activity->comment }}
                                                </td>
                                                <td class="text-center"><img src="{{$activity->user->image}}"
                                                                             class="img-circle img-md"/><br><strong>{{ $activity->user->name }}</strong>
                                                </td>
                                                <td>{{ \App\Models\DateTimeExtensions::toRelative($activity->created_at) }}</td>
                                                @if(Auth::user()->type == \App\Models\UserType::Administrator)
                                                    <td>
                                                        <a href="{{ url('dashboard/post-activity')}}/{{$activity->id}}/delete">Delete</a>
                                                    </td>
                                                @endif
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <a href="{{url('dashboard/post/list')}}" class="btn bg-indigo-400 btn-labeled btn-rounded"><b><i
                                    class="glyphicon glyphicon-chevron-left"></i></b> All Posts</a><br><br>
                </div>

                <div class="col-md-6 text-right">
                    <div class="btn-group">
                        <button type="button" class="btn bg-teal-400 btn-labeled dropdown-toggle"><b><i
                                        class="icon-reload-alt"></i></b> Previous Versions <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:;" ng-repeat="(i, state) in PCTRL.autosaves"
                                   ng-click="PCTRL.loadAutosaveModal(i)"><i class="icon-chevron-right"></i>
                                    Version @{{ i+1 }} (@{{ state.name }})</a></li>
                            <li><a href="javascript:;" ng-show="PCTRL.autosaves.length == 0"><i
                                            class="icon-chevron-right"></i> No saved versions yet.</a></li>
                            <li class="divider"></li>
                            <li><a href="javascript:;" ng-click="PCTRL.toggleAutosaveState()"><i class="fa fa-gear"></i>
                                    Autosave: @{{ PCTRL.autosavestate ? "ON" : "OFF" }}</a></li>
                            <li><a href="javascript:;" ng-click="PCTRL.clickAutosave()"><i class="fa fa-save"></i> Save
                                    now</a></li>
                            <li class="divider" ng-show="PCTRL.autosaves.length > 0"></li>
                            <li><a href="javascript:;" ng-show="PCTRL.autosaves.length > 0"
                                   ng-click="PCTRL.clearSavedVersions()"><i class="fa fa-times-circle"></i> Clear saved
                                    versions</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Basic layout-->

                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Post Details</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse" class=""></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-lg-1 control-label">Post Title:</label>

                                <div class="col-lg-9">
                                    <input name="title" type="text" class="form-control"
                                           placeholder="Enter a title for this post..."
                                           ng-model="PCTRL.post.title">
                                </div>
                            </div>

                            @if (!empty($post))
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">Link:</label>
                                    <label class="col-lg-2 control-label">http://postize.com/</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="url" class="form-control" value="{{$post->slug}}" /></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a class="col-lg-2 col-lg-offset-1 control-label" href="{{url($post->slug)}}"
                                       target="_blank">View Post</a>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-lg-1 control-label">Description (tagline):</label>

                                <div class="col-lg-9">
                                    <input name="description" type="text" class="form-control"
                                           placeholder="Enter a description for this post..."
                                           ng-model="PCTRL.post.description">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-1 control-label">Category:</label>

                                <div class="col-lg-9">
                                    <select id="category" name="category_id" class="form-control" required>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{ !empty($post->category_id) && $post->category_id == $category->id ? ' selected' : '' }}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if (Auth::user()->type == 1 || (isset($post->user_id) && $post->user_id == Auth::user()->id))
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">Status:</label>

                                    <div class="col-lg-9">
                                        <select id="status" name="status" class="form-control select">
                                            @if (Auth::user()->type == 1 || (isset($post) && $post->status == 1))
                                                <option value="1" {{ !empty($post) && $post->status == 1 ? 'selected' : '' }}>
                                                    Published
                                                </option>
                                            @endif
                                            <option value="0" {{ !empty($post) && $post->status == 0 ? 'selected' : '' }}>
                                                In Progress (Draft)
                                            </option>
                                            <option value="3" {{ !empty($post) && $post->status == 3 ? 'selected' : '' }}>
                                                Ready For Review
                                            </option>
                                            <option value="4" {{ !empty($post) && $post->status == 4 ? 'selected' : '' }}>
                                                Requires Revision
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="col-lg-1 control-label">Statistics:</label>

                                <div class="col-lg-9 post-statistics">
                                    <ul>
                                        <li><i class="fa fa-image fa-2x" style="color: #b3d271"></i>
                                            <span ng-bind="PCTRL.imageBlockCount()" disabled></span><span
                                                    style="font-size: 1.5em"> Image Blocks</span></li>
                                        <li>

                                            <i class="fa fa-file-text-o fa-2x" style="color: #cc6666"></i>
                                            <span ng-bind="PCTRL.pageCount()"
                                                  disabled></span><span> Pages In Article</span>
                                        </li>
                                        <li>

                                            <i class="fa fa-file-word-o fa-2x" style="color: #455A64"></i>
                                            <span ng-bind="PCTRL.totalWordCount"
                                                  disabled></span><span> Word Count</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if(Auth::user()->type == \App\Models\UserType::Administrator || Auth::user()->type == \App\Models\UserType::Moderator)
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">Comment: </label>

                                    <div class="col-lg-9">
                                        <textarea name="comment" type="text" class="form-control"
                                                  style="border:1px solid black; padding: 5px; border-radius:5px"></textarea><br/>
                                        <small>Save a comment by clicking on the 'Save Post' button. To view all
                                            comments, see the 'Post Activity' section at the top.
                                        </small>
                                    </div>
                                </div>
                            @endif

                            <div class="text-right">
                                @if(!empty($post))
                                    <a class="btn btn-success legitRipple" href="{{url($post->slug)}}?__preview=1"
                                       target="_blank">Preview Post<i
                                                class="icon-arrow-right14 position-right"></i></a>
                                @endif
                                <button type="submit" class="btn btn-primary legitRipple">Save Post<i
                                            class="icon-arrow-right14 position-right"></i></button>
                            </div>

                        </div>
                    </div>

                    <!-- /basic layout -->
                </div>
            </div>

            <div class="row" ng-init="PCTRL.initCanvas()">
                <div class="col-md-12">
                    <div class="panel panel-info panel-bordered panel-collapsed">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="font-weight: bold">Thumbnail Generator (Default thumbnail -
                                shows at the top of the article).</h2>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse" class=""></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">

                            <div ng-show="PCTRL.post.id">
                                <span ng-show="!PCTRL.showThmbEditor"> Image Thumbnail:<br></span>
                                <img ng-show="!PCTRL.showThmbEditor" ng-src="@{{ PCTRL.post.image }}"><br><br>
                                <button type="button" class="btn btn-primary"
                                        ng-click="PCTRL.showThmbEditor = !PCTRL.showThmbEditor">@{{ PCTRL.showThmbEditor ? 'Cancel creating new thumbnail' : 'Create new thumbnail' }}</button>
                                <br><br>
                            </div>
                            <div ng-show="!PCTRL.post.id || PCTRL.showThmbEditor">
                                <div class="uploader">
                                    <input type="file" class="file-styled" id="canvasImageUpload" multiple>
                                    <span class="action btn bg-pink-400 legitRipple" style="-webkit-user-select: none;">Choose File</span>
                                </div>
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb. Accepts between 1 to 4 images. Result thumbnail dimensions will be 1200x630px</span>
                                <canvas id="thumbnailGenerator" style="margin: 0 auto;"></canvas>

                                <div class="text-center">
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.deleteFromCanvas()"
                                            ng-show="!PCTRL.cropThumbnail"><i class="icon-cancel-circle2"></i> Remove
                                        selected image
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.toFrontCanvas()"
                                            ng-show="!PCTRL.cropThumbnail"><i class="icon-stack-up"></i> Move selected
                                        image
                                        to front
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.toBackCanvas()"
                                            ng-show="!PCTRL.cropThumbnail"><i class="icon-stack-down"></i> Move selected
                                        image to back
                                    </button>
                                    <button type="button"
                                            class="btn @{{PCTRL.cropThumbnail ? 'btn-primary' : 'btn-default'}}"
                                            ng-click="PCTRL.cropMode()"><i class="icon-crop2"></i> Crop
                                        Mode: @{{PCTRL.cropThumbnail ? 'ON' : 'off'}}</button>
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.cropSelected()"
                                            ng-show="PCTRL.cropThumbnail"><i class="icon-crop2"></i> Crop Selected Area
                                    </button>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.undoCanvas()"><i
                                                class="icon-undo"></i> Undo
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.redoCanvas()"><i
                                                class="icon-redo"></i> Redo
                                    </button>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button type="button" class="btn btn-default" ng-click="PCTRL.arrangeCanvas()"><i
                                                class="icon-magic-wand"></i> Arrange Automagically
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" id="thumbnail_output" name="thumbnail_output" value="">
                        </div>
                    </div>
                </div>
            </div>

            <input id="preview_thumbnail_url" type="hidden" name="preview_thumbnail_url"
                   value="{{$post->preview_thumbnail or ''}}"/>
            <div class="panel panel-success panel-bordered panel-collapsed">
                <div class="panel-heading">
                    <h2 class="panel-title" style="font-weight: bold">Set a Custom Thumbnail for Facebook (optional)<a
                                class="heading-elements-toggle"><i class="icon-more"></i></a></h2>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div>
                    <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

                <div class="panel-body">
                    @if(empty($post))
                        <p>You must save your post first before this section will be enabled.</p>
                    @else
                    <div class="form-group">
                        <div class="col-lg-12">
                            <h3>(Option 1) Use the default thumbnail (the one created through the Thumbnail
                                Generator)</h3>
                            <img class="preview-thumbnail-default" src="{{$post->image}}"
                                 style="margin:5px;width:140px;height:100px; border:2px solid black; border-radius:5px;"/>
                            <hr/>

                            <h3>(Option 2) Choose a file</h3>
                            <input id="preview-thumbnail" name="preview_thumbnail" type="file" class="form-control"
                                   placeholder="Select a file..."
                                   ng-model="PCTRL.post.preview_thumbnail">
                            <br/>
                            <button id="preview-thumbnail-upload-clear" type="submit" class="btn btn-danger legitRipple">Discard File<i
                                        class=" position-right"></i></button>
                            <button type="submit" class="btn btn-primary legitRipple">Upload Image and Save Post<i
                                        class="icon-arrow-right14 position-right"></i></button>
                            <hr/>
                            <h3>(Option 3) Select an existing image</h3>
                            <ul class="enlarge">
                                @foreach($post->blocks as $block)
                                    @if($block->type == 'image')
                                        <li>
                                            <img class="preview-thumbnail-image" src="{{ $block->url }}"
                                                 style="margin:5px;width:140px;height:100px; border:2px solid black; border-radius:5px; "/>
                                            <span><img class="preview-thumbnail-image-large"
                                                       src="{{$block->url}}"/></span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <hr/>
                            @if(!empty($post->preview_thumbnail))
                                <hr/>
                                <h3>Current Facebook Thumbnail: </h3>
                                <img style="max-width: 800px; max-height:500px;" src="{{ $post->preview_thumbnail }}"/>
                            @endif
                        </div>
                    </div>
                        @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Content Editor</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button ng-click="PCTRL.previousActiveSelection = PCTRL.editor.active; PCTRL.editor.active = 'text'"
                                            type="button"
                                            class="btn btn-labeled btn-xlg"
                                            ng-class="PCTRL.editor.active == 'text' ? 'btn-success' : 'btn-primary'"><b><i
                                                    class="icon-typography"></i></b> Text
                                    </button>
                                    <button ng-click="PCTRL.previousActiveSelection = PCTRL.editor.active; PCTRL.editor.active = 'image'"
                                            type="button"
                                            class="btn btn-labeled btn-xlg"
                                            ng-class="PCTRL.editor.active == 'image' ? 'btn-success' : 'btn-primary'">
                                        <b><i
                                                    class="icon-image2"></i></b> Image
                                    </button>
                                    <button ng-click="PCTRL.previousActiveSelection = PCTRL.editor.active; PCTRL.editor.active = 'embed'"
                                            type="button"
                                            class="btn btn-labeled btn-xlg"
                                            ng-class="PCTRL.editor.active == 'embed' ? 'btn-success' : 'btn-primary'">
                                        <b><i
                                                    class="icon-embed2"></i></b> Embed
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-right" ng-show="PCTRL.editor.active == 'text'">
                                        <i class="icon-typography"></i> Text: Write a paragraph, heading or a quote
                                    </p>
                                    <p class="text-right" ng-show="PCTRL.editor.active == 'image'">
                                        <i class="icon-image2"></i> Image: Enter URL to an image or upload one
                                    </p>
                                    <p class="text-right" ng-show="PCTRL.editor.active == 'embed'">
                                        <i class="icon-embed2"></i> Embed: Embed Youtube video, Ad code, etc.
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">

                                <div class="col-md-12" ng-show="PCTRL.editor.active == 'text'">
                                    <text-angular
                                            ta-toolbar="[['p'], ['h1','h2','h3'], ['bold','italics'], ['insertLink', 'quote'], ['wordcount', 'charcount']]"
                                            ng-model="PCTRL.editor.text.content"></text-angular>
                                    <div class="panel-group panel-group-control panel-group-control-right content-group-lg"
                                         id="accordion-control-right">
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse"
                                                       data-parent="#accordion-control-right"
                                                       href="#accordion-control-right-group1">HTML Code Preview</a>
                                                </h6>
                                            </div>
                                            <div id="accordion-control-right-group1" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    @{{ PCTRL.editor.text.content ? PCTRL.editor.text.content : '//start typing in the editor above' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" ng-show="PCTRL.editor.active == 'image'">
                                    <div class="tabbable">
                                        <ul class="nav nav-tabs nav-tabs-highlight">
                                            <li class="active"><a href="#left-tab1" data-toggle="tab"><i
                                                            class="icon-upload4 position-left"></i> Upload Images</a>
                                            </li>
                                            <li><a href="#left-tab2" data-toggle="tab"><i
                                                            class="icon-link2 position-left"></i> Link Images</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane active has-padding" id="left-tab1">
                                                <input id="editorFileInput" type="file" class="file-input"
                                                       multiple="multiple" data-show-caption="false">
                                                <br><br>
                                                <div ng-repeat="(i, t) in PCTRL.editor.imageUpload.files" class="row">
                                                    <div class="row">
                                                        <div class="col-md-2"><img ng-src="@{{ PCTRL.imagePreview[i] }}"
                                                                                   ng-show="PCTRL.imagePreview[i]"
                                                                                   class="img-responsive"></div>
                                                        <div class="col-md-2">
                                                            <input class="form-control"
                                                                   placeholder="Image Title (optional)"
                                                                   ng-model="t.title">
                                                        </div>
                                                        <div class="col-md-3">
                                                        <textarea class="form-control"
                                                                  placeholder="Image Description (optional)" rows="5"
                                                                  ng-model="t.description"></textarea>
                                                        </div>
                                                        <div class="col-md-2"><input class="form-control"
                                                                                     placeholder="Source Name (e.g. Diply, Buzzfeed, Tumblr, Reddit/FunkyDog)"
                                                                                     ng-model="t.source"></div>
                                                        <div class="col-md-3"><input class="form-control"
                                                                                     placeholder="Link to source (e.g. http://website.com/cats-are-funny)"
                                                                                     ng-model="t.sourceurl"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane has-padding" id="left-tab2">
                                                <div ng-repeat="(i, t) in PCTRL.editor.imageLink.links" class="row">
                                                    <div class="col-md-1">
                                                        <img ng-src="@{{ t.url }}" ng-show="t.url"
                                                             class="img-responsive">
                                                    </div>
                                                    <div class="col-md-1"><input class="form-control"
                                                                                 placeholder="Link to image"
                                                                                 ng-model="t.url"
                                                                                 ng-change="PCTRL.autoAddImageSourceName(t)">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <input class="form-control" placeholder="Image Title (optional)"
                                                               ng-model="t.title">
                                                    </div>
                                                    <div class="col-md-3">
                                                    <textarea class="form-control"
                                                              placeholder="Image Description (optional)" rows="5"
                                                              ng-model="t.description"></textarea>
                                                    </div>
                                                    <div class="col-md-2"><input class="form-control"
                                                                                 placeholder="Source"
                                                                                 ng-model="t.source"></div>
                                                    <div class="col-md-2"><input class="form-control"
                                                                                 placeholder="Link to source"
                                                                                 ng-model="t.sourceurl"></div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-danger"
                                                                type="button"
                                                                ng-click="PCTRL.editor.imageLink.links.splice(i, 1)"><i
                                                                    class="icon-cancel-circle2"></i></button>
                                                    </div>

                                                </div>
                                                <br>
                                                <button type="button" class="btn btn-primary"
                                                        ng-click="PCTRL.addLinkImage()">Add New
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" ng-show="PCTRL.editor.active == 'embed'">
                                <textarea class="form-control embedTextarea"
                                          ng-model="PCTRL.editor.embed.content"></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn bg-teal-400 btn-labeled insertContentButton"
                                            ng-click="PCTRL.insertBlock()"><b><i class="icon-pencil4"></i></b> <span>Insert Content Block</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Page Breaks</h5>
                        </div>
                        <div class="panel-body">

                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn bg-teal-400 btn-labeled insertContentButton"
                                            ng-click="PCTRL.editor.active = 'pagebreak'; PCTRL.insertBlock()"><b><i
                                                    class="icon-pencil4"></i></b> <span>Insert Page Break</span>
                                    </button>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="input-group bootstrap-touchspin">
                                            <span class="input-group-addon bootstrap-touchspin-prefix">Set Number of Images On Each Page:</span>
                                            <input type="text"
                                                   class="form-control touchspin-button-group ng-pristine ng-untouched ng-valid ng-not-empty"
                                                   style="display: block;" ng-model="PCTRL.multiplePageBreakCount">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default bootstrap-touchspin-down legitRipple"
                                                        type="button"
                                                        ng-click="PCTRL.multiplePageBreakCount = PCTRL.multiplePageBreakCount - 1">
                                                    -
                                                </button>
                                                <button class="btn btn-default bootstrap-touchspin-up legitRipple"
                                                        type="button"
                                                        ng-click="PCTRL.multiplePageBreakCount = PCTRL.multiplePageBreakCount + 1">
                                                    +
                                                </button>
                                                <button type="button"
                                                        class="btn bg-teal-400 btn-labeled insertContentButton"
                                                        ng-click="PCTRL.editor.active = 'pagebreak'; PCTRL.insertPageBreaks()">
                                                    <b><i class="icon-pencil4"></i></b> <span>Insert Page Breaks</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" ng-repeat="(i, block) in PCTRL.blocks">
                <div class="col-md-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h5 class="panel-title">
                                    <span ng-show="block.type == 'text'">
                                        <i class="icon-typography"></i> @{{i+1}}. Text Block
                                    </span>
                                    <span class="text-right" ng-show="block.type == 'image'">
                                        <i class="icon-image2"></i> @{{i+1}}. Image Block
                                    </span>
                                    <span class="text-right" ng-show="block.type == 'embed'">
                                        <i class="icon-embed2"></i> @{{i+1}}. Embed Block
                                    </span>
                                    <span ng-show="block.type == 'pagebreak'">
                                        <i class="icon-typography"></i> @{{i+1}}. Page Break Block
                                    </span>
                                    </h5>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button type="button" class="btn btn-primary btn-labeled"
                                            ng-click="PCTRL.moveDown(i)">
                                        <b><i class="icon-chevron-down"></i></b> Move Down
                                    </button>
                                    <button type="button" class="btn btn-primary btn-labeled"
                                            ng-click="PCTRL.moveUp(i)"><b><i
                                                    class="icon-chevron-up"></i></b> Move Up
                                    </button>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="input-group bootstrap-touchspin">
                                            <span class="input-group-btn"></span>
                                            <span class="input-group-addon bootstrap-touchspin-prefix">Position:</span>
                                            <input type="text" class="form-control touchspin-button-group" value="50"
                                                   style="display: block;" ng-model="block.position">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default bootstrap-touchspin-down" type="button"
                                                        ng-click="PCTRL.minusPosition(i)">-
                                                </button>
                                                <button class="btn btn-default bootstrap-touchspin-up" type="button"
                                                        ng-click="PCTRL.plusPosition(i)">+
                                                </button>
                                                <button type="button" class="btn btn-primary"
                                                        ng-click="PCTRL.moveToPosition(i)">Move
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-danger btn-labeled"
                                            ng-click="PCTRL.removeBlock(i)"><b><i
                                                    class="icon-cancel-circle2"></i></b> Remove Block
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row" ng-if="block.type == 'text'">
                                <div class="col-md-6">
                                    <h4>Edit</h4>
                                    <hr>
                                    <text-angular
                                            ta-toolbar="[['p'], ['h1','h2','h3'], ['bold','italics'], ['insertLink', 'quote']]"
                                            ng-model="block.content"></text-angular>
                                    <h4>Word count: @{{block.content.split(" ").length}}</h4>
                                </div>
                                <div class="col-md-6">
                                    <h4>Preview</h4>
                                    <hr>
                                    <div ng-bind-html="PCTRL.trustedHTML(block.content)"></div>
                                </div>
                            </div>
                            <div class="row" ng-if="block.type == 'image'">
                                <div class="col-md-6">
                                    <h4>Edit</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label>Title:</label>
                                        <input type="text" class="form-control" ng-model="block.title">
                                    </div>
                                    <div class="form-group">
                                        <label>Description:</label>
                                        <textarea class="form-control" ng-model="block.description"
                                                  rows="5"> </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Image link:</label>
                                        <input type="text" class="form-control" ng-model="block.url">
                                    </div>
                                    <div class="form-group">
                                        <label>Source</label>
                                        <input type="text" class="form-control" ng-model="block.source">
                                    </div>
                                    <div class="form-group">
                                        <label>Source link:</label>
                                        <input type="text" class="form-control" ng-model="block.sourceurl">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Preview</h4>
                                    <hr>
                                    <h2>@{{ block.title }}</h2>
                                    <p>@{{ block.description }}</p>
                                    <img class="img-responsive" style="max-height: 220px;" ng-src="@{{ block.url }}">
                                <span ng-show="block.source && block.sourceurl">via: <a href="@{{block.sourceurl}}"
                                                                                        target="_blank">@{{ block.source }}</a></span>
                                    <span ng-show="block.source && !block.sourceurl">via: @{{ block.source }}</span>
                                <span ng-show="!block.source && block.sourceurl">via: <a href="@{{block.sourceurl}}"
                                                                                         target="_blank">source</a></span>
                                </div>
                            </div>
                            <div class="row" ng-if="block.type == 'embed'">
                                <div class="col-md-6">
                                    <h4>Edit</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label>Content:</label>
                                        <textarea class="form-control embedTextarea"
                                                  ng-model="block.content"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Preview</h4>
                                    <hr>
                                    <div ng-bind-html="PCTRL.trustedHTML(block.content)"></div>
                                </div>
                            </div>
                            <div class="row" ng-if="block.type == 'pagebreak'">
                                <div class="col-md-12">
                                    <div class="alert alert-info alert-styled-left alert-bordered">
                                        <span class="text-semibold">This is a Page Break block, a "Next Page" button will be shown on the website to split the article into multiple pages.</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalRevertSave" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@{{ PCTRL.asModalTitle }}</h4>
                        </div>
                        <div class="modal-body">
                            <div ng-repeat="block in PCTRL.asModalBlocks">
                                <div ng-if="block.type == 'text'" ng-bind-html="PCTRL.trustedHTML(block.content)">

                                </div>
                                <div ng-if="block.type == 'image'">
                                    <img class="img-responsive" style="max-height: 220px;" ng-src="@{{ block.url }}">
                                <span ng-show="block.source && block.sourceurl">via: <a href="@{{block.sourceurl}}"
                                                                                        target="_blank">@{{ block.source }}</a></span>
                                    <span ng-show="block.source && !block.sourceurl">via: @{{ block.source }}</span>
                                <span ng-show="!block.source && block.sourceurl">via: <a href="@{{block.sourceurl}}"
                                                                                         target="_blank">source</a></span>
                                </div>
                                <div ng-if="block.type == 'embed'" ng-bind-html="PCTRL.trustedHTML(block.content)">

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" ng-click="PCTRL.loadAutosave()">Revert Version
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
        <input type="hidden" name="blocks" id="blocks" value="">
        <button type="submit" class="btn btn-primary legitRipple">Save Post<i
                    class="icon-arrow-right14 position-right"></i></button>

    </form>
@endsection

@section('js-bottom')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.5.0/fabric.min.js"></script>
    <script src="{{ asset('assets/dashboard/js/plugins/uploaders/fileinput.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/plugins/notifications/noty.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/plugins/notifications/jgrowl.min.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
    <script src="{{ asset('assets/plugins/editors/textangular/textAngular-rangy.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/editors/textangular/textAngular-sanitize.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/editors/textangular/textAngular.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/methodize-editor.js?v1.1.3') }}"></script>

    <script>
        $(document).ready(function () {
            $('.preview-thumbnail-image').click(function (e) {
                e.preventDefault();
                $('.preview-thumbnail-image').css('border', '2px solid black');
                $('.preview-thumbnail-default').css('border', '2px solid black');
                $(this).css('border', '5px solid #6AF780');
                $('#preview_thumbnail_url').val($(this).attr('src'));
            });

            $('.preview-thumbnail-default').click(function (e) {
                e.preventDefault();
                $('.preview-thumbnail-image').css('border', '2px solid black');
                $('.preview-thumbnail-default').css('border', '2px solid black');
                $(this).css('border', '5px solid #6AF780');
                $('#preview_thumbnail_url').val($(this).attr('src'));
            });

            $('.preview-thumbnail-image-large').click(function (e) {
                $('.preview-thumbnail-image').css('border', '2px solid black');
                $('.preview-thumbnail-default').css('border', '2px solid black');
                $(this).css('border', '5px solid #6AF780');
                $(this).parent().parent().children('.preview-thumbnail-image').css('border', '5px solid #6AF780');
                $('#preview_thumbnail_url').val($(this).attr('src'));
            });

            $("#preview-thumbnail-upload-clear").click(function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to discard this file?')) {
                    $("#preview-thumbnail").val("");
                }
            });
        });
    </script>
@endsection