angular.module('MethodizeEditor', ['textAngular']);

angular.module('MethodizeEditor').controller('MethodizeController', function ($scope, $sce, $interval) {
    var vm = this;
    var autosaveIntervalHolder = null;
    var previousActiveSelection = null;
    vm.multiplePageBreakCount = 4; // Sensible default, industry standard
    var confirmOnPageExit = function (e)
    {
        // If we haven't been passed the event get the window.event
        e = e || window.event;

        var message = 'You will lose your changes';

        // For IE6-8 and Firefox prior to version 4
        if (e)
        {
            e.returnValue = message;
        }

        // For Chrome, Safari, IE8+ and Opera 12+
        return message;
    };

    vm.init = function () {
        vm.imagePreview = [];
        //passed from laravel, ManagePostController@postAddEditPost
        vm.post = Methodize.post ? Methodize.post : {title: "", description: ""};
        vm.blocks = Methodize.blocks;
        vm.editor = {
            active: 'text',
            text: {
                content: ""
            },
            imageUpload: {
                files: []
            },
            imageLink: {
                links: [{title: vm.imageBlockCount() + 1 + ". ", description: "", url: "", source: "", sourceurl: ""}]
            },
            embed: {
                content: "",
            }
        };

        // file upload
        jQuery('#editorFileInput').fileinput({
            maxFileCount: 20,
            initialPreview: [],
            showUpload: false,
            fileActionSettings: {
                removeIcon: '<i class="icon-bin"></i>',
                removeClass: 'btn btn-link btn-xs btn-icon',
                uploadIcon: '<i class="icon-upload"></i>',
                uploadClass: 'btn btn-link btn-xs btn-icon',
                indicatorNew: '<i class="icon-file-plus text-slate"></i>',
                indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
                indicatorError: '<i class="icon-cross2 text-danger"></i>',
                indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>',
            }
        });

        jQuery('#editorFileInput').on('change fileclear', function () {
            $scope.$apply(function () {
                vm.editor.imageUpload.files = document.getElementById('editorFileInput').files;
                for (var i=0; i< vm.editor.imageUpload.files.length; i++) {
                    var reader = new FileReader();
                    reader.IMGNR = i;

                    reader.onload = function (e) {
                        var i = this.IMGNR;
                        $scope.$apply(function () {
                            vm.imagePreview[i] = e.target.result;
                        });
                    };

                    reader.readAsDataURL(vm.editor.imageUpload.files[i]);
                }
            });
        });

        jQuery("#addEditForm").submit(function(e) {
            e.preventDefault();
            if (!vm.post.title || !vm.post.description) {
                jQuery.jGrowl('Please enter post title and description', {
                    header: 'Error',
                    theme: 'bg-danger'
                });
                return false;
            }

            if(document.getElementById('status') != null && document.getElementById('status').value == 2) {
                if(!confirm("Are you sure you wish to delete this post?"))
                    return false;
            }

            var thumbnail = ThumbnailGenerator.getCanvasData();
            if (!vm.post.id && !thumbnail) {
                jQuery.jGrowl('Please generate a thumbnail image', {
                    header: 'Error',
                    theme: 'bg-danger'
                });
                return false;
            }

            if (vm.blocks.length == 0) {
                jQuery.jGrowl('Please add at least one content block', {
                    header: 'Error',
                    theme: 'bg-danger'
                });
                return false;
            }

            //fill in the vals
            jQuery("#blocks").val(angular.toJson(vm.blocks));
            if (vm.showThmbEditor || !vm.post.id)
            jQuery("#thumbnail_output").val(thumbnail);
            window.onbeforeunload = null;
            this.submit();
            return true;
        });

        jQuery('.dropdown-toggle').on('click', function (event) {
            jQuery(this).parent().toggleClass('open');
        });

        vm.totalWordCount = 0;
        $scope.$watch(angular.bind(vm, function () {
            return vm.blocks;
        }), function (newVal) {
            vm.totalWordCount = 0;
            if(vm.blocks != null) {
                for (var i = 0; i < vm.blocks.length; i++) {
                    if (vm.blocks[i].type == 'text')
                        vm.totalWordCount += vm.blocks[i].content.split(" ").length;
                    else if (vm.blocks[i].type == 'image') {
                        if (typeof vm.blocks[i].title != 'undefined')
                            vm.totalWordCount += vm.blocks[i].title.split(" ").length;
                        if (typeof vm.blocks[i].description != 'undefined')
                            vm.totalWordCount += vm.blocks[i].description.split(" ").length;
                    }
                }
            }
        }, true);

        previousActiveSelection = vm.editor.active;

        $scope.$watch('')

        vm.initAutosave();
    };

    vm.initCanvas = function () {
        vm.cropThumbnail = false;
        ThumbnailGenerator.init();
    };

    vm.undoCanvas = function() {
        vm.cropThumbnail = false;
        ThumbnailGenerator.undo();
    };

    vm.redoCanvas = function() {
        vm.cropThumbnail = false;
        ThumbnailGenerator.redo();
    };

    vm.deleteFromCanvas = ThumbnailGenerator.delete;
    vm.toFrontCanvas = ThumbnailGenerator.toFront;
    vm.toBackCanvas = ThumbnailGenerator.toBack;
    vm.cropSelected = function() {
        ThumbnailGenerator.cropSelected();
        vm.cropThumbnail = false;
    };
    vm.cropMode = function() {
        vm.cropThumbnail = ThumbnailGenerator.cropMode(!vm.cropThumbnail);
    };

    vm.arrangeCanvas = function() {
        vm.cropThumbnail = false;
        ThumbnailGenerator.automagic();
    };

    vm.insertBlock = function () {
        if (vm.editor.active == 'text' || vm.editor.active == 'pagebreak') {
            if (vm.editor.active == 'text') {
                //text block creating
                if (!vm.editor.text.content) {
                    jQuery.jGrowl('Please enter some text first', {
                        header: 'Invalid',
                        theme: 'bg-danger'
                    });
                    return;
                }

                var txt = angular.copy(vm.editor.text.content);
            }
            else {
                var txt = '<!!--nextpage--!!>';
            }

            vm.blocks.push({
                type: vm.editor.active,
                content: txt
            });
            //attach order, for manual order changing
            var len = vm.blocks.length;
            vm.blocks[len - 1].position = len;
            vm.blocks[len - 1].content += "";
            vm.editor.text.content = "";

            if(vm.editor.active == 'pagebreak')
                vm.editor.active = previousActiveSelection;
        } else if (vm.editor.active == 'image') {
            //image block creating
            if (jQuery("#left-tab1").hasClass('active')) {
                //upload images
                if (vm.editor.imageUpload.files.length == 0) {
                    jQuery.jGrowl('Upload at least one image', {
                        header: 'Invalid',
                        theme: 'bg-danger'
                    });
                    return;
                }
                for (var i = 0; i < vm.editor.imageUpload.files.length; i++) {
                    /*if (!vm.editor.imageUpload.files[i].source || !vm.editor.imageUpload.files[i].sourceurl)
                     {
                     jQuery.jGrowl('You must enter source name and valid source link for each image', {
                     header: 'Invalid',
                     theme: 'bg-danger'
                     });
                     return;
                     }*/

                    if (vm.editor.imageUpload.files[i].sourceurl && vm.editor.imageUpload.files[i].sourceurl.substr(0, 4) != 'http')
                        vm.editor.imageUpload.files[i].sourceurl = "http://" + vm.editor.imageUpload.files[i].sourceurl;
                }

                jQuery('.insertContentButton').attr('disabled', true);
                jQuery('.insertContentButton span').html('Uploading Images...');
                var all_files = [];
                var xhrCount = 0;
                for (var i = 0; i < vm.editor.imageUpload.files.length; i++) {
                    var file = vm.editor.imageUpload.files[i];
                    all_files.push(file);
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", document.location.origin + "/dashboard/post/uploadimage", true);
                    xhr.uniqueid = i;
                    xhr.setRequestHeader("X_FILENAME", all_files[i].name);
                    var formData = new FormData();
                    formData.append("imagecontent", all_files[i]);
                    xhr.send(formData);
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4) {
                            var status = String(this.status);
                            var response = JSON.parse(this.responseText);
                            var uid = this.uniqueid;
                            if (status[0] == '2') {
                                $scope.$apply(function() {

                                    vm.blocks.push({
                                        type: "image",
                                        url: response.url,
                                        title: all_files[uid].title,
                                        description: all_files[uid].description,
                                        source: all_files[uid].source,
                                        sourceurl: all_files[uid].sourceurl,
                                    });
                                    //attach order, for manual order changing
                                    var len = vm.blocks.length;
                                    vm.blocks[len - 1].position = len;
                                });
                            }
                            xhrCount++;

                            //check if last xHR
                            if (xhrCount == all_files.length) {
                                $scope.$apply(function() {
                                    jQuery('.insertContentButton').attr('disabled', false);
                                    jQuery('.insertContentButton span').html('Insert Content');
                                    jQuery('#editorFileInput').fileinput('reset');
                                    vm.editor.imageUpload.files = [];
                                });
                            }
                        }
                    }


                }
            } else {
                //link images
                if (vm.editor.imageLink.links.length == 0) {
                    jQuery.jGrowl('Enter at least one image', {
                        header: 'Invalid',
                        theme: 'bg-danger'
                    });
                    return;
                }

                for (var i = 0; i < vm.editor.imageLink.links.length; i++) {
                    if (!vm.editor.imageLink.links[i].url) {
                        jQuery.jGrowl('You must enter image link', {
                            header: 'Invalid',
                            theme: 'bg-danger'
                        });
                        return;
                    }

                    if (vm.editor.imageLink.links[i].sourceurl && vm.editor.imageLink.links[i].sourceurl.substr(0, 4) != 'http')
                        vm.editor.imageLink.links[i].sourceurl = "http://" + vm.editor.imageLink.links[i].sourceurl;

                    if (vm.editor.imageLink.links[i].url.substr(0, 4) != 'http')
                        vm.editor.imageLink.links[i].url = "http://" + vm.editor.imageLink.links[i].url;
                }

                for (var i = 0; i < vm.editor.imageLink.links.length; i++) {
                    vm.blocks.push({
                        type: "image",
                        url: vm.editor.imageLink.links[i].url,
                        title: vm.editor.imageLink.links[i].title,
                        description: vm.editor.imageLink.links[i].description,
                        source: vm.editor.imageLink.links[i].source,
                        sourceurl: vm.editor.imageLink.links[i].sourceurl
                    });
                }

                vm.editor.imageLink.links = [{url: "", source: "", sourceurl: ""}];
                //attach order, for manual order changing
                var len = vm.blocks.length;
                vm.blocks[len - 1].position = len;
            }
        } else if (vm.editor.active == 'embed') {
            if (!vm.editor.embed.content) {
                jQuery.jGrowl('Please enter some text first', {
                    header: 'Invalid',
                    theme: 'bg-danger'
                });
                return;
            }
            var YTEmbed = vm.convertYoutubeLink(vm.editor.embed.content);
            if (YTEmbed) {
                vm.editor.embed.content = '<iframe width="560" height="315" src="//www.youtube.com/embed/' + YTEmbed + '" frameborder="0" allowfullscreen></iframe>';
            }
            var txt = angular.copy(vm.editor.embed.content);
            vm.blocks.push({
                type: "embed",
                content: txt
            });

            vm.editor.embed.content = "";
            //attach order, for manual order changing
            var len = vm.blocks.length;
            vm.blocks[len - 1].position = len;
        }

        vm.handleDirtyForm();
    };

    vm.handleDirtyForm = function() {
        if (vm.post && vm.blocks.length > 0)
            window.onbeforeunload = confirmOnPageExit;
        else
            window.onbeforeunload = null;
    };

    vm.addLinkImage = function () {
        vm.editor.imageLink.links.push({url: "", title: vm.imageBlockCount() + 1 + ". ", source: "", sourceurl: ""});
    };

    vm.convertYoutubeLink = function (url) {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);

        if (match && match[2].length == 11) {
            return match[2];
        } else {
            return false;
        }
    };

    vm.moveUp = function (i) {
        if (i == 0)
            return;

        //synch order if user messed with +/-
        vm.blocks[i - 1].position = i;
        vm.blocks[i].position = i + 1;

        var toReplace = angular.copy(vm.blocks[i]);
        vm.blocks[i] = angular.copy(vm.blocks[i - 1]);
        vm.blocks[i - 1] = toReplace;

        vm.blocks[i - 1].position = i;
        vm.blocks[i].position = i + 1;
    };

    vm.moveDown = function (i) {
        if (i == (vm.blocks.length - 1))
            return;

        //synch order if user messed with +/-
        vm.blocks[i].position = i + 1;
        vm.blocks[i + 1].position = i + 2;

        var toReplace = angular.copy(vm.blocks[i]);
        vm.blocks[i] = angular.copy(vm.blocks[i + 1]);
        vm.blocks[i + 1] = toReplace;

        vm.blocks[i].position = i + 1;
        vm.blocks[i + 1].position = i + 2;
    };

    vm.minusPosition = function (i) {
        if (vm.blocks[i].position == 1)
            return;
        vm.blocks[i].position--;
    };

    vm.plusPosition = function (i) {
        if (vm.blocks[i].position == vm.blocks.length)
            return;

        vm.blocks[i].position++;
    };

    vm.moveToPosition = function (i) {
        if (vm.blocks[i].position < 1 || vm.blocks[i].position > vm.blocks.length)
            return;

        var toReplace = angular.copy(vm.blocks[i]);
        vm.blocks.splice(i, 1);
        vm.blocks.splice(toReplace.position - 1, 0, toReplace);

        for (var j = 0; j < vm.blocks.length; j++)
            vm.blocks[j].position = j + 1;
    };

    vm.removeBlock = function(i) {
        vm.blocks.splice(i, 1);
        for (var j = 0; j < vm.blocks.length; j++)
            vm.blocks[j].position = j + 1;


        if (!vm.post && vm.blocks.length > 0)
            window.onbeforeunload = confirmOnPageExit;
        else
            window.onbeforeunload = null;
    };

    vm.trustedHTML = function(html) {
        return $sce.trustAsHtml(html);
    };

    vm.initAutosave = function() {
        if (localStorage.getItem("autosavestate") === null)
            localStorage.setItem("autosavestate", true);


        if (vm.post && vm.post.id) {
            vm.autosaves = localStorage.getItem("post-" + vm.post.id)
        } else {
            vm.autosaves = localStorage.getItem("newpost")
        }

        vm.autosaves = vm.autosaves && vm.autosaves != 'null' ? JSON.parse(vm.autosaves) : [];
        vm.autosavestate = localStorage.getItem("autosavestate") == 'true' ? true : false;

        if (vm.autosavestate)
            autosaveIntervalHolder = $interval(vm.autosaveInterval, 60000);

    };

    vm.autosaveInterval = function() {
        if (vm.blocks.length == 0 && ThumbnailGenerator.images.length == 0)
            return;

        vm.autosave();
    };

    vm.clickAutosave = function() {
        $interval.cancel(autosaveIntervalHolder);
        vm.autosave(true);
        autosaveIntervalHolder = $interval(vm.autosaveInterval, 60000)
    };

    vm.toggleAutosaveState = function() {
        vm.autosavestate = !vm.autosavestate;
        localStorage.setItem("autosavestate", vm.autosavestate );
        if (vm.autosavestate) {
            $interval.cancel(autosaveIntervalHolder);
            autosaveIntervalHolder = $interval(vm.autosaveInterval, 60000);
        } else {
            $interval.cancel(autosaveIntervalHolder);
        }
    };

    vm.autosave = function(clicked) {
        ThumbnailGenerator.getData().then(function(TGImages) {
            jQuery.jGrowl(clicked ? 'Saving...' : 'Autosaving...', {
                header: '',
                theme: 'bg-success'
            });
            console.log("Autosaving...");
            var nowtime = new Date();
            var hh = nowtime.getHours() < 10 ? "0" + nowtime.getHours() : nowtime.getHours();
            var mm = nowtime.getMinutes() < 10 ? "0" + nowtime.getMinutes() : nowtime.getMinutes();
            var toSave = {
                name: hh + ":" + mm,
                editor: angular.copy(vm.editor),
                blocks: angular.copy(vm.blocks),
                title: vm.post ? angular.copy(vm.post.title) : "",
                desc: vm.post ? angular.copy(vm.post.description) : "",
                canvas: TGImages
            };


            if (vm.autosaves.length >= 5)
                vm.autosaves.splice(0, 1);

            $scope.$apply(function() {
                vm.autosaves.push(toSave);
            });

            var str = angular.toJson(vm.autosaves);

            if (vm.post && vm.post.id) {
                localStorage.setItem("post-" + vm.post.id, str);
            } else {
                localStorage.setItem("newpost", str);
            }
        });
    };

    vm.loadAutosaveModal = function(i) {
        vm.asModalTitle = "Version " + (i+1) + " (" + vm.autosaves[i].name + ")";
        vm.asModalBlocks = vm.autosaves[i].blocks;
        vm.verToRevert = i;
        jQuery("#modalRevertSave").modal();
    };

    vm.loadAutosave = function() {
        jQuery("#modalRevertSave").modal('toggle');
        var toReturn = angular.copy(vm.autosaves[vm.verToRevert]);

        vm.editor = toReturn.editor;
        vm.blocks = toReturn.blocks;
        vm.post.title = toReturn.title;
        vm.post.description = toReturn.desc;
        vm.cropThumbnail = false;
        ThumbnailGenerator.setData(toReturn.canvas);
        jQuery.jGrowl('Reverted to saved version', {
            header: '',
            theme: 'bg-success'
        });
    };

    vm.clearSavedVersions = function() {
        vm.autosaves = [];

        if (vm.post && vm.post.id) {
            localStorage.setItem("post-" + vm.post.id, null);
        } else {
            localStorage.setItem("newpost", null);
        }
    };

    vm.imageBlockCount = function() {
        var count = 0;
        if(vm.blocks != null) {
            for (var i = 0; i < vm.blocks.length; i++) {
                if (vm.blocks[i].type == 'image') {
                    count++;
                }
            }
        }

        if(typeof vm.editor != 'undefined') {
            for (var i = 0; i < vm.editor.imageLink.links.length; i++) {
                count++;
            }

            for (var i = 0; i < vm.editor.imageUpload.files.length; i++) {
                count++;
            }
        }

        return count;
    };

    vm.pageCount = function() {
        var count = 1;
        if(vm.blocks != null) {
            for (var i = 0; i < vm.blocks.length; i++) {
                if (vm.blocks[i].type == 'pagebreak') {
                    count++;
                }
            }
        }

        return count;
    };

    vm.insertPageBreaks = function() {
        if(vm.multiplePageBreakCount == 0) return;

        var imageBlocksSinceLastPageBreak = 0;
        for(var i = 0; i < vm.blocks.length; i++) {
            if(vm.blocks[i].type == 'image') {
                imageBlocksSinceLastPageBreak++;
            }

            if(i < (vm.blocks.length - 1) && imageBlocksSinceLastPageBreak == vm.multiplePageBreakCount) {
                var pageBreakBlock = {
                    type: 'pagebreak',
                    content: '<!!--nextpage--!!>',
                    position: i + 2
                };

                vm.blocks.splice(i + 1, 0, pageBreakBlock);

                // Reorder all blocks after the inserted one
                for (var j = i + 1; j < vm.blocks.length; j++)
                    vm.blocks[j].position = j + 1;

                imageBlocksSinceLastPageBreak = 0;
            }
        }
        if(vm.editor.active == 'pagebreak') {
            vm.editor.active = previousActiveSelection;
        }

        vm.handleDirtyForm();
    };

    vm.autoAddImageSourceName = function(t) {
        var host = parseUrl(t.url).host;
        t.source = host.charAt(0).toUpperCase() + host.slice(1);
    }
});

/**
 * THUMBNAIL GENERATOR
 */
var ThumbnailGenerator = new function () {
    var self = this;
    self.canvas = null;
    self.canvasDimensions = {width: 1200, height: 630};
    self.state = [];
    self.mods = 0;
    self.images = [];
    self.lines = 0;
    //self.imageCount = 0;
    self.cropThumbnail = null;
    self.cropEl = null;
    self.undoredo = false;

    self.init = function () {
        jQuery("#thumbnailGenerator").attr("width", self.canvasDimensions.width + "px");
        jQuery("#thumbnailGenerator").attr("height", self.canvasDimensions.height + "px");
        self.canvas = new fabric.Canvas("thumbnailGenerator", {
            selection: true,
        });
        self.canvas.selection = false;

        //image upload
        document.getElementById('canvasImageUpload').onchange = function (e) {
            if (self.images.length + e.target.files.length > 4) {
                jQuery.jGrowl('You can have up to 4 images.', {
                    header: 'Error',
                    theme: 'bg-danger'
                });
                return;
            }
            for (var i=0; i<e.target.files.length;i++ ) {
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[i]);

                reader.onload = function (event) {
                    var imgObj = new Image();
                    imgObj.src = event.target.result;
                    imgObj.onload = function () {
                        //self.updateModifications(true);
                        var image = new fabric.Image(imgObj);
                        self.images.push(image);
                        self.canvas.add(image);
                        self.drawLines();
                    };
                };
            }
            document.getElementById('canvasImageUpload').value = "";
        };
        self.canvas.on('object:modified', function (ev) {
            if (ev.target != self.cropEl && self.undoredo != true) {
                self.updateModifications(true);
                self.mods = 0;
                console.log("modified");
            }
        });
        self.canvas.on('object:added', function (ev) {
            if (ev.target != self.cropEl && self.undoredo != true) {
                self.updateModifications(true);
                self.mods = 0;
                console.log("added");
            }
        });
        self.canvas.on('object:deleted', function (ev) {
            if (ev.target != self.cropEl && self.undoredo != true) {
                self.updateModifications(true);
                self.mods = 0;
                console.log("deleted");
            }
        });

        //crop stuff
        /*self.canvas.on("mouse:down", function (event) {
            if (self.cropDisabled) return;

            self.cropEl.left = event.e.pageX - self.pos[0];
            self.cropEl.top = event.e.pageY - self.pos[1];
            //el.selectable = false;
            self.cropEl.visible = true;
            self.mousex = event.e.pageX;
            self.mousey = event.e.pageY;
            self.cropInProgress = true;
            self.canvas.bringToFront(self.cropEl);
        });

        self.canvas.on("mouse:move", function (event) {
            if (self.cropInProgress && !self.cropDisabled) {
                console.log(self.cropEl.left);
                if (event.e.pageX - self.mousex > 0) {
                    self.cropEl.width = event.e.pageX - self.mousex;
                }

                if (event.e.pageY - self.mousey > 0) {
                    self.cropEl.height = event.e.pageY - self.mousey;
                }
            }
        });

        self.canvas.on("mouse:up", function (event) {
            self.cropInProgress = false;
        });
        var r = document.getElementById('thumbnailGenerator').getBoundingClientRect();
        self.pos[0] = r.left;
        self.pos[1] = r.top;
         */
        self.cropEl = new fabric.Rect({
            //left: 100,
            //top: 100,
            fill: "#ccc",
            originX: 'left',
            originY: 'top',
            stroke: '#000',
            strokeDashArray: [5, 5],
            opacity: 0.7,
            width: 150,
            height: 150,
        });

        self.cropEl.visible = false;
        self.cropEl.hasRotatingPoint = false;
        self.canvas.add(self.cropEl);
    };

    self.delete = function() {
        self.updateModifications(true);
        var selected = self.canvas.getActiveObject();
        if (selected) {
            for (var i = 0; i < self.images.length; i++) {
                if (selected == self.images[i]) {
                    selected.remove();
                    self.images.splice(i, 1);
                    break;
                }
            }
            self.drawLines();
        }
    };

    self.toFront = function() {
        self.canvas.getActiveObject() ? self.canvas.getActiveObject().bringToFront() : angular.noop();
        for (var i = 0; i < self.lines.length; i++) {
            self.lines[i].bringToFront();
        }
    };

    self.toBack = function() {
        self.canvas.getActiveObject() ? self.canvas.getActiveObject().sendToBack() : angular.noop();
    };

    self.drawLines = function() {
        for (var i = 0; i < self.lines.length; i++) {
            self.lines[i].remove();
        }
        self.lines = [];
        if (self.images.length == 1) {

        } else if (self.images.length == 2) {
            var rect = new fabric.Rect();
            rect.set({ width: 10, height: 630, fill: '#fff', opacity: 1.0, left: 595, top: 0 });
            rect.selectable = false;
            self.lines.push(rect);
        } else if (self.images.length == 3) {
            var rect = new fabric.Rect();
            rect.set({ width: 10, height: 630, fill: '#fff', opacity: 1.0, left: 715, top: 0 });
            rect.selectable = false;
            self.lines.push(rect);

            var rect = new fabric.Rect();
            rect.set({ width: 600, height: 10, fill: '#fff', opacity: 1.0, left: 715, top: 310 });
            rect.selectable = false;
            self.lines.push(rect);
        } else if (self.images.length == 4) {
            var rect = new fabric.Rect();
            rect.set({ width: 10, height: 630, fill: '#fff', opacity: 1.0, left: 595, top: 0 });
            rect.selectable = false;
            self.lines.push(rect);

            var rect = new fabric.Rect();
            rect.set({ width: 1200, height: 10, fill: '#fff', opacity: 1.0, left: 0, top: 310 });
            rect.selectable = false;
            self.lines.push(rect);
        }

        for (var i = 0; i < self.lines.length; i++) {
            self.canvas.add(self.lines[i]);
            self.lines[i].bringToFront();
        }
    };

    self.automagic = function() {
        if (self.images.length == 0)
            return;
        self.updateModifications(true);
        self.undoredo = true;
        self.cropEl.visible = false;
        self.cropThumbnail = false;

        if (self.images.length == 1) {
            self.images[0].left = 0;
            self.images[0].top = 0;
            self.images[0].setAngle(0);
            if (self.images[0].width / self.images[0].height < self.canvasDimensions.width / self.canvasDimensions.height)
                self.images[0].scaleToWidth(self.canvasDimensions.width);
            else
                self.images[0].scaleToHeight(self.canvasDimensions.height);

        } else if (self.images.length == 2) {
            self.images[0].left = 0;
            self.images[0].top = 0;
            self.images[0].setAngle(0);
            if (self.images[0].width / self.images[0].height < (self.canvasDimensions.width/2) / self.canvasDimensions.height)
                self.images[0].scaleToWidth(self.canvasDimensions.width / 2);
            else
                self.images[0].scaleToHeight(self.canvasDimensions.height);

            self.images[1].left = self.canvasDimensions.width/2;
            self.images[1].top = 0;
            self.images[1].setAngle(0);
            if (self.images[1].width / self.images[1].height < (self.canvasDimensions.width/2) / self.canvasDimensions.height)
                self.images[1].scaleToWidth(self.canvasDimensions.width / 2);
            else
                self.images[1].scaleToHeight(self.canvasDimensions.height);
            self.images[1].bringToFront();
        } else if (self.images.length == 3) {
            self.images[0].left = 0;
            self.images[0].top = 0;
            self.images[0].setAngle(0);
            if (self.images[0].width / self.images[0].height < (self.canvasDimensions.width * 0.6) / self.canvasDimensions.height)
                self.images[0].scaleToWidth(self.canvasDimensions.width * 0.6);
            else
                self.images[0].scaleToHeight(self.canvasDimensions.height);

            self.images[1].left = self.canvasDimensions.width * 0.6;
            self.images[1].top = 0;
            self.images[1].setAngle(0);
            if (self.images[1].width / self.images[1].height < (self.canvasDimensions.width * 0.4) / (self.canvasDimensions.height * 0.5))
                self.images[1].scaleToWidth(self.canvasDimensions.width * 0.4);
            else
                self.images[1].scaleToHeight(self.canvasDimensions.height * 0.5);

            self.images[1].bringToFront();

            self.images[2].left = self.canvasDimensions.width * 0.6;
            self.images[2].top = self.canvasDimensions.height/2;
            self.images[2].setAngle(0);
            if (self.images[2].width / self.images[2].height < (self.canvasDimensions.width * 0.4) / (self.canvasDimensions.height * 0.5))
                self.images[2].scaleToWidth(self.canvasDimensions.width * 0.4);
            else
                self.images[2].scaleToHeight(self.canvasDimensions.height * 0.5);
            self.images[2].bringToFront();
        } else if (self.images.length == 4) {
            self.images[0].left = 0;
            self.images[0].top = 0;
            self.images[0].setAngle(0);
            if (self.images[0].width / self.images[0].height < (self.canvasDimensions.width * 0.5) / (self.canvasDimensions.height * 0.5))
                self.images[0].scaleToWidth(self.canvasDimensions.width * 0.5);
            else
                self.images[0].scaleToHeight(self.canvasDimensions.height * 0.5);

            self.images[1].left = self.canvasDimensions.width * 0.5;
            self.images[1].top = 0;
            self.images[1].setAngle(0);
            if (self.images[1].width / self.images[1].height < (self.canvasDimensions.width * 0.5) / (self.canvasDimensions.height * 0.5))
                self.images[1].scaleToWidth(self.canvasDimensions.width * 0.5);
            else
                self.images[1].scaleToHeight(self.canvasDimensions.height * 0.5);
            self.images[1].bringToFront();

            self.images[2].left = 0;
            self.images[2].top =  self.canvasDimensions.height * 0.5;
            self.images[2].setAngle(0);
            if (self.images[2].width / self.images[2].height < (self.canvasDimensions.width * 0.5) / (self.canvasDimensions.height * 0.5))
                self.images[2].scaleToWidth(self.canvasDimensions.width * 0.5);
            else
                self.images[2].scaleToHeight(self.canvasDimensions.height * 0.5);
            self.images[2].bringToFront();

            self.images[3].left = self.canvasDimensions.width * 0.5;
            self.images[3].top =  self.canvasDimensions.height * 0.5;
            self.images[3].setAngle(0);
            if (self.images[3].width / self.images[3].height < (self.canvasDimensions.width * 0.5) / (self.canvasDimensions.height * 0.5))
                self.images[3].scaleToWidth(self.canvasDimensions.width * 0.5);
            else
                self.images[3].scaleToHeight(self.canvasDimensions.height * 0.5);
            self.images[3].bringToFront();
        }
        for (var i = 0; i < self.lines.length; i++) {
            self.lines[i].bringToFront();
        }

        for (var i = 0; i < self.images.length; i++) {
            self.images[i].selectable = true;
        }
        self.canvas.renderAll();
        self.undoredo = false;
    };

    self.cropMode = function(toCrop) {
        self.cropEl.visible = false;
        self.canvas.renderAll();
        if (toCrop) {
            self.cropThumbnail = self.canvas.getActiveObject();
            if (!self.cropThumbnail) {
                jQuery.jGrowl('Please select image that you want to crop first.', {
                    header: 'Error',
                    theme: 'bg-danger'
                });
                return false;
            }
            for (var i = 0; i < self.images.length; i++) {
                self.images[i].selectable = false;
            }
            jQuery.jGrowl('Now position the square over the area that you want to crop', {
                header: 'Tips',
                theme: 'bg-success'
            });
            self.cropEl.visible = true;
            self.cropEl.bringToFront();
            return true;

        } else {
            for (var i = 0; i < self.images.length; i++) {
                self.images[i].selectable = true;
            }
            self.cropThumbnail = null;
            return false;
        }
    };

    self.cropSelected = function() {
        if (!self.cropThumbnail)
            return;

        self.updateModifications(true);
        var left = self.cropEl.getLeft() - (self.cropThumbnail.getWidth()/2) - self.cropThumbnail.getLeft();
        var top = self.cropEl.getTop() - (self.cropThumbnail.getHeight()/2) - self.cropThumbnail.getTop();

        var width = self.cropEl.getWidth();
        var height = self.cropEl.getHeight();
        self.cropThumbnail.clipTo = function (ctx) {
            ctx.rect(left, top, width, height);
        };
        self.cropThumbnail = false;
        self.cropEl.visible = false;
        for (var i = 0; i < self.images.length; i++) {
            self.images[i].selectable = true;
        }
        self.canvas.deactivateAll().renderAll();
    };

    /**
     * undo/redo - not working yet, not sure if possible like this
     * @param savehistory
     */
    self.updateModifications = function (savehistory) {

        if (savehistory === true && !self.undoredo) {
            var newstate = {
                images: [],
                lines: []
            };
            for (var i = 0; i < self.images.length; i++) {
                self.images[i].clone(function(cl) {
                    newstate.images.push(cl);
                })
            }
            for (var i = 0; i < self.lines.length; i++) {
                self.lines[i].clone(function(cl) {
                    newstate.lines.push(cl);
                });
            }
            if (self.state.length > 15)
                self.state.splice(0, 1);
            self.state.push(newstate);
        }
    };

    self.undo = function() {
        if (self.mods < self.state.length) {
            self.undoredo = true;
            self.cropEl.visible = false;
            self.cropThumbnail = false;
            self.canvas.clear().renderAll();
            var toReturn = self.state[self.state.length - 1 - self.mods - 1];
            if (!toReturn) {
                self.undoredo = false;
                return;
            }

            self.images = toReturn.images;
            self.lines = toReturn.lines;

            self.redraw();
            self.mods++;
        }
    };

    self.redo = function() {
        if (self.mods > 0) {
            self.undoredo = true;
            self.cropEl.visible = false;
            self.cropThumbnail = false;
            self.canvas.clear().renderAll();
            var toReturn = self.state[self.state.length - 1 - self.mods + 1];
            if (!toReturn) {
                self.undoredo = false;
                return;
            }
            self.images = toReturn.images;
            self.lines = toReturn.lines;

            self.redraw();
            self.mods--;
        }
    };

    self.redraw = function(enliven) {
        if (enliven) {
            var toEnliven = self.images;
            self.images = [];
            fabric.util.enlivenObjects(toEnliven, function(objects) {
                objects.forEach(function(o) {
                    o.selectable.true;
                    self.canvas.add(o);
                    self.images.push(o);
                });

                self.drawLines();
            });
        } else {
            for (var i = 0; i < self.images.length; i++) {
                self.canvas.add(self.images[i]);
                self.images[i].selectable = true;
            }

            self.drawLines();
        }

        self.canvas.add(self.cropEl);
        self.canvas.renderAll();
        self.undoredo = false;
    };

    self.getCanvasData = function() {
        if (self.images.length == 0)
            return null;

        return self.canvas.toDataURL();
    };

    self.getData = function() {
        var climages = [];
        var promise = new Promise(function(resolve, reject) {
            if (self.images.length == 0)
                resolve([]);
            for (var i = 0; i < self.images.length; i++) {
                self.images[i].clone(function(cl) {
                    climages.push(cl);

                    if (climages.length == self.images.length)
                        resolve(climages)
                })
            }

        });

        return promise;
    };

    self.setData = function(images) {
        self.undoredo = true;
        self.cropEl.visible = false;
        self.cropThumbnail = false;
        self.mods = 0;
        self.state = [];
        self.canvas.clear().renderAll();
        self.images = images;
        self.redraw(true);
    };
};

function parseUrl(url){
    parsed_url = {}

    if ( url == null || url.length == 0 )
        return parsed_url;

    protocol_i = url.indexOf('://');
    parsed_url.protocol = url.substr(0,protocol_i);

    remaining_url = url.substr(protocol_i + 3, url.length);
    domain_i = remaining_url.indexOf('/');
    domain_i = domain_i == -1 ? remaining_url.length - 1 : domain_i;
    parsed_url.domain = remaining_url.substr(0, domain_i);
    parsed_url.path = domain_i == -1 || domain_i + 1 == remaining_url.length ? null : remaining_url.substr(domain_i + 1, remaining_url.length);

    domain_parts = parsed_url.domain.split('.');
    switch ( domain_parts.length ){
        case 2:
            parsed_url.subdomain = null;
            parsed_url.host = domain_parts[0];
            parsed_url.tld = domain_parts[1];
            break;
        case 3:
            parsed_url.subdomain = domain_parts[0];
            parsed_url.host = domain_parts[1];
            parsed_url.tld = domain_parts[2];
            break;
        case 4:
            parsed_url.subdomain = domain_parts[0];
            parsed_url.host = domain_parts[1];
            parsed_url.tld = domain_parts[2] + '.' + domain_parts[3];
            break;
    }

    parsed_url.parent_domain = parsed_url.host + '.' + parsed_url.tld;

    return parsed_url;
}