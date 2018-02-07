function is_int(x) {
    return x % 1 === 0;
}
$(document).ready(function() {
    var rating_text = $('#rating_text').html();
    var rating_current = $("input[id='current_rating']").val();
    $("#share_video a").click(function(event) {
        event.preventDefault();
        if ($("#share_video_box").is(':hidden')) {
            $("#share_video_box").fadeIn();
        } else {
            $("#share_video_box").hide();
        }
    });
    $("#flag_video a").click(function(event) {
        event.preventDefault();
        if ($("#flag_video_box").is(':hidden')) {
            $("#flag_video_box").fadeIn();
        } else {
            $("#flag_video_box").hide();
        }
    });
    $("#embed_video a").click(function(event) {
        event.preventDefault();
        if ($("#embed_video_box").is(':hidden')) {
            $("#embed_video_box").fadeIn();
        } else {
            $("#embed_video_box").hide();
        }
    });
    $("#close_flag").click(function(event) {
        event.preventDefault();
        $("#flag_video_box").hide();
    });
    $("#close_share").click(function(event) {
        event.preventDefault();
        $("#share_video_box").hide();
    });
    $("#close_embed").click(function(event) {
        event.preventDefault();
        $("#embed_video_box").hide();
    });
    $("#close_favorite").click(function(event) {
        event.preventDefault();
        $("#favorite_video_box").hide();
    });
    $("textarea[id='video_embed_code']").click(function() {
        $(this).select();
        $(this).focus();
    });
    $("a[id*='favorite_video_']").click(function(event) {
        event.preventDefault();
        var fav_id = $(this).attr('id');
        var id_split = fav_id.split('_');
        var video_id = id_split[2];
        user_posting('#response_message', lang_favoriting);
        $.post(base_url + '/ajax/favorite_video', {
            video_id: video_id
        }, function(response) {
            if (response.status == 0) {
                user_posting('#response_message', response.msg);
            } else {
                user_response('#response_message', response.msg);
            }
        }, 'json');
    });
    $("[id*='star_']").click(function(event) {
        event.preventDefault();
        var star_id = $(this).attr("id");
        var id_split = star_id.split('_');
        var rating = id_split[2];
        var video_id = id_split[3];
        $("#rating_text").html(lang_thanks);
        $.post(base_url + '/ajax/rate_video', {
            video_id: video_id,
            rating: rating
        }, function(response) {
            $("#rating").html(response.rating_code);
            $("#rating_text").html(response.msg);
        }, "json");
    });
    $("[id*='star_']").mouseover(function() {
        var star_id = $(this).attr('id');
        var id_split = star_id.split('_');
        var rating = id_split[2];
        var video_id = id_split[3];
        for (var i = 1; i <= 5; i++) {
            var star_sel = $("a[id='star_video_" + i + "_" + video_id + "']");
            if (i <= rating)
                $(star_sel).removeClass().addClass('full');
            else
                $(star_sel).removeClass();
        }
        if (rating == 1) {
            $('#rating_text').html(lang_lame);
        } else if (rating == 2) {
            $('#rating_text').html(lang_bleh);
        } else if (rating == 3) {
            $('#rating_text').html(lang_alright);
        } else if (rating == 4) {
            $('#rating_text').html(lang_good);
        } else if (rating == 5) {
            $('#rating_text').html(lang_awesome);
        }
    });
    $("ul[id='rating_container_video']").mouseout(function() {
        var star_id = $("[id*='star_video_1_']").attr('id');
        var id_split = star_id.split('_');
        var video_id = id_split[3];
        for (var i = 0; i < 5; i++) {
            var star = i + 1;
            var star_sel = $("a[id='star_video_" + star + "_" + video_id + "']");
            if (rating_current >= i + 1) {
                $(star_sel).removeClass().addClass('full');
            } else if (rating_current >= i + 0.5) {
                $(star_sel).removeClass().addClass('half');
            } else {
                $(star_sel).removeClass();
            }
        }
        $('#rating_text').html(rating_text);
    });
    $("a#show_related_videos").click(function(event) {
        event.preventDefault();
        $("#video_comments").hide();
        $("#related_videos").show();
    });
    $("a#show_comments").click(function(event) {
        event.preventDefault();
        $("#related_videos").hide();
        $("#video_comments").show();
    });
    $("input[id*='post_video_comment_']").click(function() {
        var video_msg = $("#post_message");
        var input_id = $(this).attr('id');
        var id_split = input_id.split('_');
        var video_id = id_split[3];
        var comment = $("textarea[id='video_comment']").val();
        if (comment == '') {
            video_msg.show();
            return false;
        }
        video_msg.hide();
        user_posting_load('#video_response', lang_posting, 1);
        reset_chars_counter();
        $.post(base_url + '/ajax/video_comment', {
            video_id: video_id,
            comment: comment
        }, function(response) {
            if (response.status == '0') {
                $("textarea[id='video_comment']").val('');
                user_posting('#video_response', response.msg);
            } else {
                $(".no_comments").hide();
                $("textarea[id='video_comment']").val('');
                var bDIV = $("#comments_delimiter");
                var cDIV = document.createElement("div");
                $(cDIV).html(response.code);
                $(bDIV).after(cDIV);
                user_response('#video_response', response.msg);
                $('#end_num').html(parseInt($('#end_num').html(), 10) + 1);
                $('#total_comments').html(parseInt($('#total_comments').html(), 10) + 1);
                $('#total_video_comments').html(parseInt($('#total_video_comments').html(), 10) + 1);
            }
        }, "json");
    });
    $("body").on('click', "a[id*='p_video_comments_']", function(event) {
        event.preventDefault();
        var page_id = $(this).attr('id');
        var id_split = page_id.split('_');
        var video_id = id_split[3];
        var page = id_split[4];
        $.post(base_url + '/ajax/video_pagination', {
            video_id: video_id,
            page: page
        }, function(response) {
            if (response != '') {
                var comments_id = $('#video_comments_' + video_id);
                $(comments_id).hide();
                $(comments_id).html(response);
                $(comments_id).show();
            }
        });
    });
    $("body").on('click', "a[id*='_related_videos_']", function(event) {
        event.preventDefault();
        var bar_id = $(this).attr('id');
        var id_split = bar_id.split('_');
        var move = id_split[0];
        var video_id = id_split[3];
        var page = $("input[id='current_page_related_videos']").val();
        var prev_bar = $('#prev_related_videos_' + video_id);
        var next_bar = $('#next_related_videos_' + video_id);
        $('.center_related').show();
        $.post(base_url + '/ajax/related_videos', {
            video_id: video_id,
            page: page,
            move: move
        }, function(response) {
            if (response.status == '1') {
                var related_div = $('#related_videos_container_' + page);
                if (response.move == 'next') {
                    $(related_div).hide();
                    $(related_div).html(response.videos);
                    $(related_div).fadeIn();
                } else {
                    related_div = $('#related_videos_container_' + response.page);
                    related_div.fadeOut();
                }
                $("input[id='current_page_related_videos']").val(response.page);
                if (response.pages <= 1) {
                    $(prev_bar).hide();
                    $(next_bar).hide();
                }
                if (response.page > 1) {
                    $(prev_bar).show();
                } else {
                    $(prev_bar).hide();
                }
                if (response.page >= response.pages) {
                    $(next_bar).hide();
                } else {
                    $(next_bar).show();
                }
            }
            $('.center_related').hide();
        }, 'json');
    });
    $("#custom_width").change(function() {
        var cw = $("#custom_width").val();
        var src = $(this).attr('data-src');
        var string = $(this).attr('data-string');
        var poster = $(this).attr('data-poster');
        if (is_int(cw) && cw >= 320) {
            if ($("#custom_size").hasClass("has-error")) {
                $("#custom_size").removeClass("has-error");
            }
            var ch = Math.round(cw / (video_width / video_height));
            $("#custom_height").val(ch);
            var embed_code = '<iframe allowfullscreen src="' + base_url + '/embedframe/' + string + '" frameborder="0" width="' + cw + '" height="' + ch + '" scrolling="no"></iframe>'
            $("#video_embed_code").val(embed_code);
        } else {
            $("#custom_size").addClass("has-error");
        }
        if (cw == '' && $("#custom_height").val() == '') {
            if ($("#custom_size").hasClass("has-error")) {
                $("#custom_size").removeClass("has-error");
            }
        }
    });
    $("#custom_height").change(function() {
        var cw = $("#custom_width").val();
        var ch = $("#custom_height").val();
        var string = $(this).attr('data-string');
        var poster = $(this).attr('data-poster');
        var src = $(this).attr('data-src');
        if (is_int(cw) && cw >= 320 && is_int(ch) && ch >= 180) {
            if ($("#custom_size").hasClass("has-error")) {
                $("#custom_size").removeClass("has-error");
            }
            var embed_code = '<iframe allowfullscreen src="' + base_url + '/embedframe/' + string + '" frameborder="0" width="' + cw + '" height="' + ch + '" scrolling="no"></iframe>'
            $("#video_embed_code").val(embed_code);
        } else {
            $("#custom_size").addClass("has-error");
        }
        if (cw == '' && ch == '') {
            if ($("#custom_size").hasClass("has-error")) {
                $("#custom_size").removeClass("has-error");
            }
        }
    });
});
$(document).ready(function() {
    $("[id*='vote_']").click(function(event) {
        event.preventDefault();
        var vote_id = $(this).attr("id");
        var id_split = vote_id.split('_');
        var vote = id_split[1];
        var item_id = id_split[2];
        $.ajax({
            url: '' + base_url + 'rating&type=' + vote + '&id=' + item_id,
            success: function(data) {
                if (data['msg'] != '') {
                    $("#vote_msg").animate({
                        'opacity': 0
                    }, 200, function() {
                        $(this).html('<center class="text-danger">' + data['msg'] + '</center>');
                    }).animate({
                        'opacity': 1
                    }, 200);
                    $("#vote_msg").delay(5000).animate({
                        'opacity': 0
                    }, 200, function() {
                        $(this).html('<div class="pull-left"><i class="glyphicon glyphicon-thumbs-up"></i> <span id="video_likes" class="text-white">' + data['like'] + '</span></div><div class="pull-right"><i class="glyphicon glyphicon-thumbs-down"></i> <span id="video_dislikes" class="text-white">' + data['dislike'] + '</span></div><div class="clearfix"></div>');
                    }).animate({
                        'opacity': 1
                    }, 200);
                } else {
                    if (data['like'] != 0 || data['dislikes'] != 0) {
                        if ($(".dislikes").hasClass("not-voted")) {
                            $(".dislikes").removeClass("not-voted");
                        }
                        $("#video_rate").css("width", data['percent_like'] + "%");
                        if (data['like'] != $("#video_likes").text()) {
                            $("#video_likes").animate({
                                'opacity': 0
                            }, 200, function() {
                                $(this).text(data['like']);
                            }).animate({
                                'opacity': 1
                            }, 200);
                        }
                        if (data['dislike'] != $("#video_dislikes").text()) {
                            $("#video_dislikes").animate({
                                'opacity': 0
                            }, 200, function() {
                                $(this).text(data['dislike']);
                            }).animate({
                                'opacity': 1
                            }, 200);
                        }
                    } else {
                        $("#vote_msg").animate({
                            'opacity': 0
                        }, 200, function() {
                            $(this).html('<center class="text-danger"><a data-toggle="modal" data-target="#myModal" href="#">Login to vote</a></center>');
                        }).animate({
                            'opacity': 1
                        }, 200);
                        $("#vote_msg").delay(5000).animate({
                            'opacity': 0
                        }, 200, function() {
                            $(this).html('<div class="pull-left"><i class="glyphicon glyphicon-thumbs-up"></i> <span id="video_likes" class="text-white">' + data['like'] + '</span></div><div class="pull-right"><i class="glyphicon glyphicon-thumbs-down"></i> <span id="video_dislikes" class="text-white">' + data['dislike'] + '</span></div><div class="clearfix"></div>');
                        }).animate({
                            'opacity': 1
                        }, 200);
                    }
                }
            }
        })
    });

    function stopError() {
        return true;
    }
    $('img.js-videoThumbFlip').each(function(i, e) {
        var el = $(this);
        var server = el.data('from')
        var digitsSuffix = el.data('digitssuffix');
        var digitsPreffix = el.data('digitspreffix');
        var preview = el.data('preview');
        var mainSrc = el.data('src');
        var flipbook = new MG_Flipbook();
        if (server === 'www.pornhub.com') {
            flipbook.params = {
                thumbnailsSets: [{
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'js-videoThumbFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: false,
                    firstThumbnail: '1',
                    digitsSuffix: '.jpg',
                    digitsPreffix: '/',
                    incrementer: 1,
                    setLength: 16,
                }, {
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'ourFriendsFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: true,
                }]
            };
            el.mouseenter(function() {
                flipbook.startFlip(el[0]);
            });
            el.mouseout(function() {
                flipbook.endFlip();
            });
        }
        if (server === 'pornfun.com') {
            flipbook.params = {
                thumbnailsSets: [{
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'js-videoThumbFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: false,
                    firstThumbnail: '1',
                    digitsSuffix: '.jpg',
                    digitsPreffix: '/',
                    incrementer: 1,
                    setLength: 16,
                }, {
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'ourFriendsFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: true,
                }]
            };
            el.mouseenter(function() {
                flipbook.startFlip(el[0]);
            });
            el.mouseout(function() {
                flipbook.endFlip();
            });
        }
        if (server === 'www.txxx.com') {
            flipbook.params = {
                thumbnailsSets: [{
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'js-videoThumbFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: false,
                    firstThumbnail: '1',
                    digitsSuffix: '.jpg',
                    digitsPreffix: '/',
                    incrementer: 1,
                    setLength: 10,
                }, {
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'ourFriendsFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: true,
                }]
            };
            el.mouseenter(function() {
                flipbook.startFlip(el[0]);
            });
            el.mouseout(function() {
                flipbook.endFlip();
            });
        }
        if (server === 'www.xvideos.com') {
            if (digitsSuffix !== '' && digitsPreffix !== '') {
                flipbook.params = {
                    thumbnailsSets: [{
                        thumbnailsContainer: '.videos',
                        thumbnailsClassName: 'js-videoThumbFlip',
                        excludeContainer: false,
                        interval: 500,
                        cover: false,
                        firstThumbnail: '1',
                        digitsSuffix: '.' + digitsSuffix,
                        digitsPreffix: '.' + digitsPreffix,
                        incrementer: 1,
                        setLength: 16,
                    }, {
                        thumbnailsContainer: '.videos',
                        thumbnailsClassName: 'ourFriendsFlip',
                        excludeContainer: false,
                        interval: 500,
                        cover: true,
                    }]
                };
                el.mouseenter(function() {
                    flipbook.startFlip(el[0]);
                });
                el.mouseout(function() {
                    flipbook.endFlip();
                });
            }
        }
        if (server === 'h2porn.com') {
            flipbook.params = {
                thumbnailsSets: [{
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'js-videoThumbFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: false,
                    firstThumbnail: '1',
                    digitsSuffix: '.jpg',
                    digitsPreffix: '/',
                    incrementer: 1,
                    setLength: 11,
                }, {
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'ourFriendsFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: true,
                }]
            };
            el.mouseenter(function() {
                flipbook.startFlip(el[0]);
            });
            el.mouseout(function() {
                flipbook.endFlip();
            });
        }
        if (server === 'fapbox.com') {
            flipbook.params = {
                thumbnailsSets: [{
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'js-videoThumbFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: false,
                    firstThumbnail: '1',
                    digitsSuffix: '.jpg',
                    digitsPreffix: '-3b',
                    incrementer: 1,
                    setLength: 10,
                }, {
                    thumbnailsContainer: '.videos',
                    thumbnailsClassName: 'ourFriendsFlip',
                    excludeContainer: false,
                    interval: 500,
                    cover: true,
                }]
            };
            el.mouseenter(function() {
                flipbook.startFlip(el[0]);
            });
            el.mouseout(function() {
                flipbook.endFlip();
            });
        }
        if (server === 'www.youporn.com') {
            if (digitsSuffix !== '' && digitsPreffix !== '') {
                flipbook.params = {
                    thumbnailsSets: [{
                        thumbnailsContainer: '.videos',
                        thumbnailsClassName: 'js-videoThumbFlip',
                        excludeContainer: false,
                        interval: 500,
                        cover: false,
                        firstThumbnail: '1',
                        digitsSuffix: '/' + digitsSuffix,
                        digitsPreffix: '/' + digitsPreffix,
                        incrementer: 1,
                        setLength: 16,
                    }, {
                        thumbnailsContainer: '.videos',
                        thumbnailsClassName: 'ourFriendsFlip',
                        excludeContainer: false,
                        interval: 500,
                        cover: true,
                    }]
                };
                el.mouseenter(function() {
                    flipbook.startFlip(el[0]);
                });
                el.mouseout(function() {
                    flipbook.endFlip();
                });
            }
        }
        if (server === 'xhamster.com') {
            if (preview !== '') {
                var timer = 0;
                var next = 0;
                var xframe = 0;

                function defaultThumb() {
                    el.attr('src', mainSrc);
                }
                el.mouseenter(function() {
                    el.css({
                        'width': '2600px',
                        'max-width': 'none',
                        'height': '177px'
                    });
                    el.attr('src', preview);
                    timer = setInterval(function() {
                        next += 260;
                        xframe += 1;
                        el.css('margin-left', -Math.floor(next));
                        if (xframe === 9) {
                            next = 0
                            xframe = 0
                        }
                    }, 1000);
                });
                el.mouseleave(function() {
                    clearInterval(timer);
                    defaultThumb();
                    el.attr('style', '');
                });
            }
        }
        if (server === 'upload') {
            if (preview !== '') {
                var timer = 0;
                var next = 0;
                var xframe = 0;

                function defaultThumb() {
                    el.attr('src', mainSrc);
                }
                el.mouseenter(function() {
                    console.log(preview)
                    el.css({
                        'width': 'auto',
                        'max-width': 'none',
                        'height': '177px',
                        'overflow': 'hidden'
                    });
                    el.attr('src', preview);
                    timer = setInterval(function() {
                        next += 315;
                        xframe += 1;
                        el.css('margin-left', -Math.floor(next));
                        if (xframe === 9) {
                            next = 0
                            xframe = 0
                        }
                    }, 1000);
                });
                el.mouseleave(function() {
                    clearInterval(timer);
                    defaultThumb();
                    el.attr('style', '');
                });
            }
        }
    });
    window.onerror = stopError;
    $('a[href="#toggle-search"], .navbar-bootsnipp .bootsnipp-search .input-group-btn > .btn[type="reset"]').on('click', function(event) {
        event.preventDefault();
        $('.navbar-bootsnipp .bootsnipp-search .input-group > input').val('');
        $('.navbar-bootsnipp .bootsnipp-search').toggleClass('open');
        $('a[href="#toggle-search"]').closest('li').toggleClass('active');
        if ($('.navbar-bootsnipp .bootsnipp-search').hasClass('open')) {
            setTimeout(function() {
                $('.navbar-bootsnipp .bootsnipp-search .form-control').focus();
            }, 100);
        }
    });
    $(document).on('keyup', function(event) {
        if (event.which == 27 && $('.navbar-bootsnipp .bootsnipp-search').hasClass('open')) {
            $('a[href="#toggle-search"]').trigger('click');
        }
    });
});

function setCookie(key, value) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}

function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}
var loadWarning = true;
if (getCookie('warning18') !== null) {
    loadWarning = false;
}
$(window).load(function() {
    // if (loadWarning) {
    //     $('#warningPopup').modal('show');
    // }
    // $('#warningPopup').on('hidden.bs.modal', function(e) {
    //     setCookie('warning18', 'successfull')
    // })
    $(document).on('click', '.warning-exit', function() {
        window.open('', '_blank');
        window.close();
    })
});
