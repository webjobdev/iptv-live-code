'use strict';

var playlistController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval) {
    var self = this;
    this.info = {};
    this.playlist = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    this.showplaylist = true;
    this.playlist.is_image_updated = 0;
    this.video_order_error = false;
    this.noRecords = true;
    scope.errors = {};

    this.languages = {};
    this.seasonTranslation = {};
    this.group_translation = {};
    this.groupTranslation = {};
    scope.translationError = false;


    scope.singlePreload = [];
    scope.singleConfig= {
        create: false,
        valueField: 'id',
        labelField: 'title',
        searchField: 'title',
        plugins: {
            'remove_button': {},
            'no_results': {'message': 'Video not available. You can upload the video in Manage Videos section.'}
        },
        placeholder: 'Search video by title and select...',
        preload: true,
        maxItems: 100,
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: $('meta[name="base-api-url"]').attr('content') + '/videos/playlist/searchvideos?search=' + query,
                beforeSend: function(request){request.setRequestHeader('Authorization', 'Bearer '+requestFactory.access_token)},
                type: 'GET',
                dataType: 'json',
                data: {
                    name: query,
                },
                error: function() {
                    callback('test');
                },
                success: function(res) {
                    callback(res.response.search_videos.data)
                }
            });
        },
   };


    requestFactory.setThisArgument(this);
    angular.element('.alert-success').fadeIn(1000).delay(5000).fadeOut(1000);

    this.fillError = function (response) {
        if (response.status == 422 && response.data.hasOwnProperty('message')) {
            angular.forEach(response.data.message, function (message, key) {
                if (typeof message == 'object' && message.length > 0) {
                    scope.errors[key] = {
                        has: true,
                        message: message[0]
                    };
                }
            });
        }
    };

    this.closePlaylistEdit = function () {
        classie.remove(document.getElementById('st-container'), 'st-menu-open');
    };


    this.deletePlaylistImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('videos/playlists/delete-playlist-image/' + this.playlist.id), this.playlist, function (response) {
            requestFactory.toggleLoader(1);
            self.responseMessage = response.message;
            self.showResponseMessage = true;
            scope.getRecords(true);
            self.closePlaylistEdit();
            self.resetPlaylistImageUpload();
        }, function () { });
    };

    this.resetPlaylistImageUpload = function () {
        if (typeof window.PlaylistImageUploadHandler == 'object') {
            $timeout(function () {
                angular.element('[data-dismiss="fileupload"]').trigger("click");
            }, 0, true);
            self.playlist.image = '';
            self.playlist.image_url = '';
        }
    };
    this.changeCategory = function () {

        if (self.allSeries.indexOf(parseInt(scope.selectedVideo.category)) != -1) {
          scope.showSeasons = true;
        }
        else {
          scope.showSeasons = false;
          scope.selectedVideo.season = '';
        }
      }
    

    this.defineProperties = function (data) {
        this.info = data.info;
        this.allCollection = data.info.allCollection;
        this.allCategories = data.info.allCategories;
        requestFactory.toggleLoader(1);
        baseValidator.setRules(data.info.rules);

    this.formatCategories = angular.copy(this.allCategories);

    var result = []; 
    this.formatCategories.forEach(function(item, index) { 
        if(item.id) {
          if(item.child_category.length > 0) {
            item.child_category.forEach(function(child, i) { 
              var newIndex = result.length;
              result[newIndex]         = {};
              result[newIndex].id      = child.id;
              result[newIndex].title   = child.title;
              result[newIndex].parent  = item.title;
            });
          }
          else {
            var newIndex = result.length;
            result[newIndex]         = {};
            result[newIndex].id      = '';
            result[newIndex].title   = '';
            result[newIndex].parent  = item.title;
          }
        }
      }
    );

    this.formatCategories = result;
    };

    this.defineLanguage = function (data) {
        this.languages = data.info.language;
        // console.log(data);
    };

    this.fetchInfo = function () {
        requestFactory.get(requestFactory.getUrl('videos/playlists/info'), this.defineProperties, function () { });
    };

    this.fetchLanguage = function () {
        requestFactory.get(requestFactory.getUrl('season/info'), this.defineLanguage, function (response) {
            rootScope.redirectUnauthenticated(response);
        });
    };

    this.fetchLanguage();
    this.fetchInfo();
    window.PlaylistImageUploadHandler = new uploadHandler;
    window.PlaylistImageUploadHandler.initate({
        file: 'playlist-image',
        previewer: 'playlist-image-preview',
        deleteIcon: 'playlist-image-delete',
        progress: 'playlist-image-progress',
        resolution: '200*200',
        beforeUpload: function () {
            if (!scope.$$phase) {
                scope.$apply();
            }
            self.playlist.module = 'playlist-image';
        },
        afterUpload: function (response) {
            self.playlist.image = response.info;
            self.playlist.module = 'playlist-image';
            self.playlist.is_image_updated = 1;
        },
        onError: function(err){
            
        }
    });

    this.removeThumbnailProperty = function () {
        $('#playlist-image-progress').html('');
        self.playlist.image = '';
       // self.artist.artist_image = '';
       self.playlist.is_image_updated = 0;
    }
    /**
     *  Function is used to add the playlist
     *  
     *  @param  $event
     */


    this.addPlaylist = function (event) {
         $(".sidepanel").addClass("in"); 
        self.resetPlaylistImageUpload();
        this.playlist = {};
        this.playlist.id = '';
        this.playlist.playlist_name = '';
        this.playlist.order = '';
        this.playlist.is_active = 1;
        this.playlist.playlist_image = '';
        scope.errors = {};
        $("#seasonForm").css('display', 'block');
        $("#seasonTranslationForm").css('display', "none");
        $("#hidevideo_order").css('display', "block");
        $('.errormsg').text('');
    }

    /**
     *  Function is used to edit the playlist
     *  
     *  @param array records
     */

    this.editPlaylist = function (records) {
        $(".sidepanel").addClass("in"); 
        self.resetPlaylistImageUpload();
        this.playlist.id = records.id;
        this.playlist.name = records.name;
        this.playlist.description = records.description;
        // this.playlist.is_active = records.is_active;
        this.playlist.is_active = records.is_active
        ? true
        : false;
        this.playlist.order = records.playlist_order;
        this.playlist.playlist_image = records.playlist_image.length > 0 ?records.playlist_image:"";
        this.playlist.is_image_updated = 0;
        this.playlist.group = records.group;
        this.playlist.category = records.category;
        this.playlist.presenter = records.presenter;


        // this.playlist.name_hindi = records.playlist_translation.name;

        var videos = []; 
        scope.singlePreload = [];
        var i = 0;
        angular.forEach(records.playlist_videos, function(value, key) {
            var valueToPush = { };
            valueToPush.id = value.id;
            valueToPush.title = value.title;
            valueToPush.video_order = records.playlist_videos_order[i]['video_order'];//value.video_order;
            scope.singlePreload.push(valueToPush);
            videos.push(value.id);
            i++;
        });
        this.playlist.video = videos;
        this.playlist.video_order = scope.singlePreload;
        scope.errors = {};
        $('.errormsg').text('');
        self.resetPlaylistImageUpload();

        // console.log(records);
        console.log(records.playlist_translation);
        // console.log(records.playlist_translation.name);
        // console.log(this.playlist);
        this.group_translation = records.playlist_translation;
        this.seasonTranslation.language = parseInt(this.languages[0].id);
        $("#seasonForm").css('display', 'block');
        $("#seasonTranslationForm").css('display', "none");
        $("#hidevideo_order").css('display', "block");
        console.log(self.seasonTranslation);
        if(records.playlist_translation === null) {
            self.seasonTranslation.name = records.name;
        } else {
            self.seasonTranslation.name = records.playlist_translation.name;
        }
    }


    this.languageChange = function () {
        scope.errors = [];
        self.groupTranslation;
        self.group_translation;
        console.log(this.seasonTranslation.language);
        console.log(this.languages[0].id);
        if (this.seasonTranslation.language == this.languages[0].id) {
            $("#seasonForm").css('display', 'block');
            $("#seasonTranslationForm").css('display', "none");
            $("#hidevideo_order").css('display', "block");
        } else {
            self.seasonTranslation.title = '';
            angular.forEach(this.group_translation, function (value) {
                if (value.language_id == self.seasonTranslation.language) {
                    console.log(value);
                    self.seasonTranslation.languageCode = value.language_id;
                    self.seasonTranslation.title = value.name ?value.name:value.title;
                   
                }
            });
            $("#seasonForm").css('display', 'none');
            $("#seasonTranslationForm").css('display', 'block');
            $("#hidevideo_order").css('display', "none");
        }
    }

    this.seasonTranslationSave = function (event, id) {
        this.seasonTranslation.languageCode = this.seasonTranslation.language;
        requestFactory.post(requestFactory.getUrl('playlist/addLanguage/' + id), this.seasonTranslation, function (response) {           
            requestFactory.setToaster('success', response.message);
            requestFactory.getToaster();
            var myIndex = scope.records.map(function (obj) {
                return obj.id;
            }).indexOf(id);
            // console.log(scope.records[myIndex].playlist_translation);
            // var langIndex = scope.records[myIndex].playlist_translation.map(function (obj) {
            //     return obj.language_id;
            // }).indexOf(parseInt(this.seasonTranslation.language));
            // langIndex = (langIndex >= 0) ? langIndex : 0;
            // scope.records[myIndex].playlist_translation[langIndex] = {
            //     language_id: this.seasonTranslation.language,
            //     name: this.seasonTranslation.title
            // };

            scope.getRecords(true);
            self.resetPlaylistImageUpload();
            scope.closesidePanelForm();
            setInterval(function(){ 
                location.reload(); 
            }, 3000);

          
        }, function (e) {
            scope.translationError = true;
            this.fillError(e);
        });
    };



    /**
     *  Function is used to save the playlist
     *  
     *  @param  $event, id
     */

    this.playlistSave = function ($event, id) {
        // if (baseValidator.validateAngularForm($event.target, scope)) {
            if(!this.videoPrderValidation()){
                if (id) {
                    requestFactory.post(requestFactory.getUrl('videos/playlists/edit/' + id), this.playlist, function (response) {
                        scope.responseMessage = response.message;
                        scope.showResponseMessage = true;
                        scope.getRecords(true);
                        self.resetPlaylistImageUpload();
                        scope.closesidePanelForm();
        
                    }, this.fillError);
                } else {
                    requestFactory.post(requestFactory.getUrl('videos/playlists/add'), this.playlist, function (response) {
                        scope.responseMessage = response.message;
                        scope.showResponseMessage = true;
                        scope.getRecords(true);
                        scope.closesidePanelForm();
                        self.resetPlaylistImageUpload();
                    }, this.fillError);
                }
            }
    // }


    }

    /**
     * Function to update status of a playlist
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function (record) {
        scope.routeName = 'playlists';
        scope.updateStatus(record);
    };

    /**
     * update the video list order
     */
    this.updateVideoList = function(e){
        
        let video_ids = this.playlist.video;
        if(typeof video_ids === 'string'){
            video_ids = video_ids.split(",");
        }
        requestFactory.post(requestFactory.getUrl('videos/playlist-videos'), {'video_ids':video_ids,'playlist_id':this.playlist.id}, function (response) {
            console.log(response);
            
            if(response.playlist_videos.length>0){
                console.log("Length greater than zero");
                this.noRecords=false;
            }
            response.playlist_videos.map((v,i) => {

                if(this.playlist.video_order && this.playlist.video_order[i]){
                    if(v.id == this.playlist.video_order[i].id){
                        response.playlist_videos[i].video_order = this.playlist.video_order[i].video_order;
                    }
                }
            });
            this.playlist.video_order = response.playlist_videos;
            
        });
    };

    // $('body').on('change','.video-order-val', function(e){
    this.videoPrderValidation = function(){ 
        $('.errormsg').text('');
        this.video_order_error = false;
        let tempArr = this.playlist.video_order;
        this.playlist.video_order && this.playlist.video_order.map((v,i) => {
            tempArr.map((t,i) => {
                // if(t.video_order == v.video_order && t.id != v.id){

                //     $("input[data-video-id='"+t.id+"']" ).parents('.video_order').find('.errormsg').text('Please enter Valid Video Order'); 
                //     this.video_order_error = true;
                // } else if(!t.video_order){
                //     $("input[data-video-id='"+t.id+"']").parents('.video_order').find('.errormsg').text('Please enter Valid Video Order');
                //     this.video_order_error = true;
                // }
            });
        });
        return this.video_order_error;
    }
    // });

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on('afterGetRecords', function (e, data) {
        if (angular.isUndefined(scope.searchRecords.is_active)) {
            scope.searchRecords.is_active = 'all';
        }
    });
}];
window.gridInitApp = angular.module('grid',['ng-selectize']);
window.gridControllers = {
    playlistController: playlistController
};
window.gridDirectives = {
    baseValidator: validatorDirective,
    intializeSidebar: intializeSidebar
};