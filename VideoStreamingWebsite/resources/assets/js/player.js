$(document).ready(function() {
    if (typeof videoData == 'undefined') {
        return false;
    }
    var isMobile = false,
        xvideos = false,
        path = '';
    var ratio = window.devicePixelRatio || 1;
    var w = screen.width * ratio;
    var h = screen.height * ratio;
    if (videoData.isAdvertisement) {
        var media = videoData.serverAdspath.split('/')
        path = media[1] + '/' + media[2] + '/' + videoData.adsName + '.xml';
    }
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
    if (videoData.xvideoServer) {
        xvideos = true;
    }
    var firstLoad = true;
    if (!videoData.isBuy) {
        videojs.plugin('ads-setup', function(opts) {
            var player = this;
            var vastAd = player.vastClient({
                adTagUrl: base_asset + '/' + videoData.adsPath + '/' + videoData.adsName + '.xml',
                playAdAlways: true,
                vpaidFlashLoaderPath: base_asset + 'public/assets/js/plugins/videojs_ads/VPAIDFlash.swf',
                adCancelTimeout: videoData.skipAds === 0 ? 3000 : videoData.skipAds * 1000,
                adsEnabled: videoData.isAdvertisement,
                preferredTech: ['html', 'flash']
            });
        });
        var adPluginOpts = {
            "plugins": {
                "ads-setup": {
                    "adCancelTimeout": videoData.skipAds === 0 ? 3000 : videoData.skipAds * 1000,
                    "adsEnabled": videoData.isAdvertisement,
                    "verbosity": 4,
                    "vpaidFlashLoaderPath": base_asset + 'public/assets/js/plugins/videojs_ads/VPAIDFlash.swf',
                }
            }
        };
        var xPlayer = videojs('xstreamerPlayer', adPluginOpts);
        xPlayer.ready(function() {
            this.watermark({
                file: videoData.playerLogo,
                xrepeat: 0,
                opacity: 1,
                clickable: false,
                url: "",
                className: 'vjs-brand',
            });
            this.poster(videoData.videoPoster);
            if (videoData.videoServer === 'upload') {
                if (videoData.videoFile !== null) {
                    if (!isMobile) {
                        // this.src({
                        //     src: 'rtmp://xstreamer.info:1935/videos/' + videoData.videoFile.SD,
                        //     type: 'rtmp/mp4',
                        // });
                        this.src([
                            {
                                src: 'rtmp://xstreamer.info:1935/adv/' + videoData.videoFile.SD,
                                type: 'rtmp/mp4'
                            },
                            {
                                src: '/videos/' + (videoData.videoFile.SD || videoData.videoFile.HD),
                                type: videoData.videoType,
                            }
                        ]);
                    } else {
                        this.src({
                            src: (isMobile === true && xvideos === true) ? videoData.mobileVideo : videoData.videoUrl !== null ? videoData.videoUrl : videoData.videoSD !== null ? videoData.videoSD : videoData.videoHD,
                            type: videoData.videoType,
                        });
                    }
                }
            } else {
                this.src({
                    src: (isMobile === true && xvideos === true) ? videoData.mobileVideo : videoData.videoUrl !== null ? videoData.videoUrl : videoData.videoSD !== null ? videoData.videoSD : videoData.videoHD,
                    type: videoData.videoType,
                });
            }
            this.player().on('error', function(e) {
                if (videoData.embedCode !== null) {
                    var embedFrameVideo = '<iframe allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen src="' + videoData.embedCode + '" frameborder="0" width="100%" height="540" scrolling="no"></iframe>';
                    if ($('#video-player').length)
                        $('#video-player').empty().append(embedFrameVideo);
                    else if ($('#load-video').length)
                        $('#load-video').empty().append(embedFrameVideo);
                }
            });
            this.on('play', function() {
                console.log('play');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: base_url + 'add-view',
                    data: {'videoId':videoData.videoId},
                    success: function(data) {
                    console.log('viewed');
                    }
                });
                $('#xstreamerPlayer .videoRelate').fadeOut();
                $('#xstreamerPlayer .vda-iv').fadeOut();
                return false;
            });
            this.on('pause', function() {
                console.log('pause')
                $.ajax({
                    url: base_url + 'load-ads?token=' + token,
                    success: function(data) {
                        $('#xstreamerPlayer').append(data);
                    }
                });
                return false;
            });
            this.on('seeked', function() {
                if (firstLoad) {
                    $('#xstreamerPlayer').append('<div class="ads"></div>');
                    $('#xstreamerPlayer .ads').html(htmlAds)
                    $("#xstreamerPlayer .demo1").bootstrapNews({
                        newsPerPage: 1,
                        autoplay: true,
                        pauseOnHover: true,
                        direction: 'up',
                        newsTickerInterval: 4000,
                        onToDo: function() {}
                    });
                    firstLoad = false;
                }
            });
            this.on('error', function() {
                if (videoData.embedCode !== null) {
                    var embedFrameVideo = '<iframe allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen src="' + videoData.embedCode + '" frameborder="0" width="100%" height="540" scrolling="no"></iframe>';
                    if ($('#video-player').length)
                        $('#video-player').empty().append(embedFrameVideo);
                    else if ($('#load-video').length)
                        $('#load-video').empty().append(embedFrameVideo);
                }
            });
        });
    } else {
        videojs('xstreamerPlayer').ready(function() {
            this.watermark({
                file: videoData.playerLogo,
                xrepeat: 0,
                opacity: 1,
                clickable: false,
                url: "",
                className: 'vjs-brand',
            });
            this.poster(videoData.videoPoster);
            var video_container = $('#xstreamerPlayer').parent();
            if (videoData.isMember === null) {
                video_container.bind('click', function(event) {
                    $('#myModal').modal('show');
                    $('#myModal').on('hidden.bs.modal', function() {
                        $(this).find('form')[0].reset();
                    });
                    this.pause();
                });
                return false;
            }
            if (videoData.expriedPay.expriedDate < 0) {
                video_container.bind('click', function(event) {
                    $('#paymentDialog').modal('show')
                });
            } else {

                videojs.plugin('ads-setup', function(opts) {
                    var player = this;
                    var vastAd = player.vastClient({
                        adTagUrl: base_asset + '/' + videoData.adsPath + '/' + videoData.adsName + '.xml',
                        playAdAlways: true,
                        vpaidFlashLoaderPath: base_asset + 'public/assets/js/plugins/videojs_ads/VPAIDFlash.swf',
                        adCancelTimeout: videoData.skipAds === 0 ? 3000 : videoData.skipAds * 1000,
                        adsEnabled: videoData.isAdvertisement,
                        preferredTech: ['html', 'flash']
                    });
                });
                var adPluginOpts = {
                    "plugins": {
                        "ads-setup": {
                            "adCancelTimeout": videoData.skipAds === 0 ? 3000 : videoData.skipAds * 1000,
                            "adsEnabled": videoData.isAdvertisement,
                            "verbosity": 4,
                            "vpaidFlashLoaderPath": base_asset + 'public/assets/js/plugins/videojs_ads/VPAIDFlash.swf',
                        }
                    }
                };
                var xPlayer = videojs('xstreamerPlayer', adPluginOpts);

                xPlayer.ready(function() {
                    this.watermark({
                        file: videoData.playerLogo,
                        xrepeat: 0,
                        opacity: 1,
                        clickable: false,
                        url: "",
                        className: 'vjs-brand',
                    });
                    this.poster(videoData.videoPoster);
                    if (videoData.videoServer === 'upload') {
                        if (videoData.videoFile !== null) {
                            if (!isMobile) {
                                this.src({
                                    src: 'rtmp://xstreamer.adentdemo.m3server.com:1935/adv/' + videoData.videoFile.SD,
                                    type: 'rtmp/mp4',
                                });
                            } else {
                                this.src({
                                    src: (isMobile === true && xvideos === true) ? videoData.mobileVideo : videoData.videoUrl !== null ? videoData.videoUrl : videoData.videoSD !== null ? videoData.videoSD : videoData.videoHD,
                                    type: videoData.videoType,
                                });
                            }
                        }
                    } else {
                        this.src({
                            src: (isMobile === true && xvideos === true) ? videoData.mobileVideo : videoData.videoUrl !== null ? videoData.videoUrl : videoData.videoSD !== null ? videoData.videoSD : videoData.videoHD,
                            type: videoData.videoType,
                        });
                    }
                    this.player().on('error', function(e) {
                        if (videoData.embedCode !== null) {
                            var embedFrameVideo = '<iframe allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen src="' + videoData.embedCode + '" frameborder="0" width="100%" height="540" scrolling="no"></iframe>';
                            if ($('#video-player').length)
                                $('#video-player').empty().append(embedFrameVideo);
                            else if ($('#load-video').length)
                                $('#load-video').empty().append(embedFrameVideo);
                        }
                    });
                    this.poster(videoData.videoPoster);
                    if (videoData.isAdvertisement) {
                        if (!videoData.xvideoServer && videoData.serverAdspath !== '') {
                            this.preroll({
                                src: {
                                    src: base_asset + videoData.serverAdspath,
                                    type: "video/mp4"
                                },
                                href: videoData.adsLink,
                                adsOptions: {
                                    debug: true
                                }
                            });
                        }
                    }
                    this.on('play', function() {
                        console.log('play')
                        $('#xstreamerPlayer .videoRelate').fadeOut();
                        $('#xstreamerPlayer .vda-iv').fadeOut();
                    });
                    this.on('pause', function() {
                        console.log('pause')
                        $.ajax({
                            url: base_url + 'load-ads?token=' + token,
                            success: function(data) {
                                $('#xstreamerPlayer').append(data);
                            }
                        });
                    });
                    this.on('seeked', function() {
                        if (firstLoad) {
                            $('#xstreamerPlayer').append('<div class="ads"></div>');
                            $('#xstreamerPlayer .ads').html(htmlAds)
                            $("#xstreamerPlayer .demo1").bootstrapNews({
                                newsPerPage: 1,
                                autoplay: true,
                                pauseOnHover: true,
                                direction: 'up',
                                newsTickerInterval: 4000,
                                onToDo: function() {}
                            });
                            firstLoad = false;
                        }
                    });
                    this.on('error', function() {
                        if (videoData.embedCode !== null) {
                            var embedFrameVideo = '<iframe allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen src="' + videoData.embedCode + '" frameborder="0" width="100%" height="540" scrolling="no"></iframe>';
                            if ($('#video-player').length)
                                $('#video-player').empty().append(embedFrameVideo);
                            else if ($('#load-video').length)
                                $('#load-video').empty().append(embedFrameVideo);
                        }
                    });
                });
            }
        });
    }
    $(document).on('click', '#close-ads-text', function() {
        $('#xstreamerPlayer .ads').fadeOut();
    })
    $(document).on('click', '#closePlayerAds', function() {
        $('#xstreamerPlayer .vda-iv').fadeOut();
    })
})
