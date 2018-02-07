$(document).ready(function() {
  $("#collections").click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "GET",
      url: base_url + "member-collections.html",
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#result').empty().append(data);
        }
      },
      beforeSend: function() {
        $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    });
  });
  $(document).on('change', '#favorite_date', function(e) {
    var date_search = this.value;
    if (date_search == null || date_search == "") {
      $.ajax({
        type: "GET",
        url: base_url + "member-collections.html",
        success: function(data) {
          if (data == 0) {
            $('#myModal').modal('show');
          } else {
            $('#result').empty().append(data);
          }
        },
        beforeSend: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
        },
        complete: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
        }
      });
    } else {
      $.ajax({
        type: "GET",
        url: base_url + "member-collections.html&date=" + date_search + "&keyword=all",
        success: function(data) {
          if (data == 0) {
            $('#myModal').modal('show');
          } else {
            $('#result').empty().append(data);
          }
        },
        beforeSend: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
        },
        complete: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
        }
      });
    }
  })
  $('#keyword_search_favorite').keyup(function() {
    var keywords = $(this).val();
    if (keywords == null || keywords == "") {
      $.ajax({
        type: "GET",
        url: base_url + "member-collections.html",
        success: function(data) {
          if (data == 0) {
            $('#myModal').modal('show');
          } else {
            $('#result').empty().append(data);
          }
        },
        beforeSend: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
        },
        complete: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
        }
      });
    } else {
      $.ajax({
        type: "GET",
        url: base_url + "member-collections.html&date=all&keyword=" + keywords,
        success: function(data) {
          if (data == 0) {
            $('#myModal').modal('show');
          } else {
            $('#result').empty().append(data);
          }
        },
        beforeSend: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
        },
        complete: function() {
          $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
        }
      });
    }
  })
  $(document).on('click', '#page_navigation ul li a', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    getpage(page);
  });

  function getpage(page) {
    $.ajax({
      url: base_url + "member-collections.html?page=" + page,
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#data').empty().html(data)
        }
      },
      beforeSend: function() {
        $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  }
  $("#channel").click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "GET",
      url: base_url + "member-subscribe.html",
      success: function(data) {
        $('#subscribe').empty().append(data);
      },
      beforeSend: function() {
        $('#subscribe-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#subscribe-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      },
      error: function(ajaxContext) {
        $('#myModal').modal('show');
      }
    });
  });
  $(document).on('click', '#member-subscribe ul li a', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    getpagechannel(page);
  });

  function getpagechannel(page) {
    $.ajax({
      url: base_url + "member-subscribe.html?page=" + page,
      success: function(data) {
        $('#data-channel').empty().html(data)
      },
      beforeSend: function() {
        $('#subscribe-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#subscribe-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  }
  $("#overview").click(function(e) {
    e.preventDefault();    
    $(".member-content h2 a").removeClass("active");
    $(this).addClass('active');
    $.ajax({
      type: "GET",
      url: base_url + "member-overview.html",
      success: function(data) {
        $('#setting').empty().append(data)
      },
      error: function(data) {
        $('#myModal').modal('show');
      }
    });
  });
  $("#changepassword").click(function(e) {
    e.preventDefault();
    $(".member-content h2 a").removeClass("active");
    $(this).addClass('active');
    $.ajax({
      type: "GET",
      url: base_url + "member-change-password.html",
      success: function(data) {
        $('#setting').empty().append(data)
      },
      beforeSend: function() {
        $('#setting-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#setting-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      },
      error: function(data) {
        $('#myModal').modal('show');
      }
    });
  });
  $("#paymenthistory").click(function(e) {
    e.preventDefault();    
    $(".member-content h2 a").removeClass("active");
    $(this).addClass('active');
    $.ajax({
      type: "GET",
      url: base_url + "member-payment-history.html",
      success: function(data) {
        $('#setting').empty().append(data)
      },
      error: function(data) {
        $('#myModal').modal('show');
      }
    });
  });
  $(document).on('click', '#messagemember', function(e) {
    e.preventDefault();
    $.ajax({
      url: base_url + "message-member.html",
      success: function(data) {
        $('#resultmessage').empty().append(data)
      },
      beforeSend: function() {
        $('#loadingmessges').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> loading Messges").show();
      },
      complete: function() {
        $('#loadingmessges').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> loading Messges").hide();
      },
      error: function(data) {
        $('#myModal').modal('show');
      }
    });
  });
  $(document).on('click', '#friendmember', function(e) {
    e.preventDefault();
    $.ajax({
      url: base_url + "member-friend.html",
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#resultmessage').empty().append(data)
        }
      },
      beforeSend: function() {
        $('#loadingmessges').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> loading Your Friend").show();
      },
      complete: function() {
        $('#loadingmessges').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> loading Your Friend").hide();
      }
    });
  });
  $(document).on('click', '#edit', function(e) {
    e.preventDefault();
    $.ajax({
      url: base_url + "member-edit.html",
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#setting').empty().append(data)
        }
      },
      beforeSend: function() {
        $('#setting-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#setting-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    });
  });
  $(document).on('click', '#change-block', function(e) {
    e.preventDefault();
    var memberfriend = $(this).attr('friend');
    $.ajax({
      url: base_url + "member-friend-update-status/" + memberfriend + ".html",
      success: function(data) {
        $('#friend-result').load(base_url + 'member-friend.html');
      }
    });
  });
  $(document).on('click', '#send-reply', function(e) {
    e.preventDefault();
    var friendmember = $(this).attr('friend');
    var messageid = $(this).attr('message');
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyy = today.getFullYear();
    var hh = today.getHours();
    var ii = today.getMinutes();
    var ss = today.getSeconds();
    $.ajax({
      url: base_url + "send-reply.html",
      type: "POST",
      data: {
        'friendmember': friendmember,
        'reply_text': $('input[name=reply-text]').val(),
        '_token': $('input[name=_token]').val(),
        'messageid': messageid
      },
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#box-message').append('<div class="message-content">You: ' + $('input[name=reply-text]').val() + '<span class="pull-right"><small>' + yyy + '/' + mm + '/' + dd + ' ' + hh + ':' + ii + ':' + ss + '</small></span></div> ');
          $('input[name=reply-text]').val("");
        }
      }
    });
  });
  $(document).on('click', '#add-friend', function(e) {
    e.preventDefault();
    $('#addmodal').modal('show');
  });
  $(document).on('click', '#un-friend-modal', function(e) {
    e.preventDefault();
    $('#unmodal').modal('show');
  });
  $(document).on('click', '#send-message-to-member', function(e) {
    var memberid = $(this).attr('member-id')
    e.preventDefault();
    $.ajax({
      url: base_url + "send-message-to-member.html&member=" + memberid,
      type: "GET",
      success: function(data) {
        $('#member-view-result-profile').empty().append(data);
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  });
  $(document).on('click', '#send-to-member', function(e) {
    var sendto = $(this).attr("friendid");
    console.log($('input[name=txt_message]').val());
    e.preventDefault();
    $.ajax({
      url: base_url + "send-message-to-member.html&member=" + sendto,
      type: "POST",
      data: {
        'message': $('input[name=txt_message]').val(),
        '_token': $('input[name=_token]').val()
      },
      success: function(data) {
        if (data == 0) {
          $('#msg').html("WARNING !: Please input some text for message")
        }
        if (data == 1) {
          $('#msg').html('<div class="alert alert-success"><span  class="glyphicon glyphicon-remove"></span><strong> Send message successfully !</strong></div>').fadeIn();
          $('input[name=txt_message]').val("");
          $('input[name=txt_message]').focus();
        }
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  })
  $(document).on('click', '#view-member-video', function() {
    var member = $(this).attr('member-id');
    $.ajax({
      url: base_url + "loadingvideo&member=" + member,
      success: function(data) {
        $('#member-view-result-profile').empty().append(data);
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  })
  $(document).on('click', '#page-navigation-member-profile ul li a', function(e) {
    e.preventDefault();
    var memberid = $('#memberIdProfile').attr('member-id');
    var page = $(this).attr('href').split('page=')[1];
    getpage_video_profile(memberid, page);
  });

  function getpage_video_profile(memberid, page) {
    $.ajax({
      url: base_url + "loadingvideo&member=" + memberid + "?page=" + page,
      success: function(data) {
        $('#member-view-result-profile').empty().html(data)
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  }
  $(document).on('click', '#view-member-subscribe', function() {
    var member = $(this).attr('member-id');
    $.ajax({
      url: base_url + "loadingsubscribe&member=" + member,
      success: function(data) {
        $('#member-view-result-profile').empty().append(data);
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  })
  $(document).on('click', '#member-subscribe-page-navigation ul li a', function(e) {
    e.preventDefault();
    var memberid = $(this).attr('member-id');
    var page = $(this).attr('href').split('page=')[1];
    getpage_subscribe_profile(memberid, page);
  });

  function getpage_subscribe_profile(memberid, page) {
    $.ajax({
      url: base_url + "loadingsubscribe&member=" + memberid + "?page=" + page,
      success: function(data) {
        $('#member-view-result-profile').empty().append(data)
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  }
  $(document).on('click', '#view-member-firend', function(e) {
    e.preventDefault();
    var memberuser = $(this).attr('member-id');
    $.ajax({
      url: base_url + "friend-memner&id=" + memberuser,
      success: function(data) {
        $('#member-view-result-profile').empty().append(data);
      },
      beforeSend: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#member-load-result').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  })
  $(document).on('click', '#block-user', function(e) {
    e.preventDefault();
    var user = $(this).attr('data-user')
    $('#block-user-modal').modal('show');
  });
  $('.block-check').click(function(e) {
    e.preventDefault();
    $(this).checked = true;
    console.log($('input:radio[name=block]').val())
  })
  $(document).on('click', '#report-user', function(e) {
    e.preventDefault();
    var user = $(this).attr('data-user')
    $('#report-user-modal').modal('show');
  })
  $(document).on('click', '#report-user-send', function(e) {
    e.preventDefault();
    var userid = $(this).attr('data');
    $.ajax({
      url: base_url + "report-user",
      type: "post",
      data: {
        'report_content': $('textarea[name=report_content]').val(),
        '_token': $('input[name=_token]').val(),
        'memberid': userid,
      },
      success: function(data) {
        if (data == 1) {
          $('#msg-report').html('Your message has been sent !')
          $('textarea[name=report_content]').val("")
        }
        if (data == 2) {
          $('#msg-report').html('Your content must be not null!')
        }
        if (data == 0) {
          window.location.reload();
        }
        if (data == 3) {
          $('#myModal').modal('show');
        }
        if (data == 4) {
          $('#msg-report').html('You have to send reports. if there are five people and sending reports to this user. the system will automatically block')
        }
      }
    });
  });
  $(document).on('click', '#block-user-send', function(e) {
    e.preventDefault();
    var userid = $(this).attr('data');
    $.ajax({
      url: base_url + "block-user",
      type: "post",
      data: {
        'block_content': $('textarea[name=block_content]').val(),
        '_token': $('input[name=_token]').val(),
        'memberid': userid,
      },
      success: function(data) {
        if (data == 1) {
          $('#msg-block').html('Your message has been sent !')
          $('textarea[name=block_content]').val("")
        }
        if (data == 2) {
          $('#msg-block').html('Your content must be not null!')
        }
        if (data == 0) {
          window.location.reload();
        }
        if (data == 3) {
          $('#myModal').modal('show');
        }
        if (data == 4) {
          $('#msg-block').html('You have to send reports. if there are five people and sending reports to this user. the system will automatically block')
        }
      }
    });
  });
  $(document).on('click', '#fillter li a', function(e) {
    e.preventDefault();
    var key = $(this).attr('data');
    $('#fillter li a').removeClass('filter-active');
    $(this).addClass('filter-active');
    $.ajax({
      url: base_url + "filter-channel.html&key=" + key,
      success: function(data) {
        $('#result-filter').empty().html(data).fadeIn('fast');
      }
    })
  })
  $(document).on('click', '#pornstars-fillter li a', function(e) {
    e.preventDefault();
    var key = $(this).attr('data');
    $('#pornstars-fillter li a').removeClass('filter-active');
    $('#pornstars-fillter-horizontal li a').removeClass('filter-active');
    $(this).addClass('filter-active');
    $.ajax({
      url: base_url + "filter-porn-stars.html&key=" + key,
      success: function(data) {
        $('#result-filter-porn-star').empty().html(data).fadeIn();
      }
    })
  })
  $(document).on('click', '#pornstars-fillter-horizontal li a', function(e) {
    e.preventDefault();
    var key = $(this).attr('data');
    $('#pornstars-fillter-horizontal li a').removeClass('filter-active');
    $('#pornstars-fillter li a').removeClass('filter-active');
    $(this).addClass('filter-active');
    $.ajax({
      url: base_url + "filter-porn-stars.html&key=" + key,
      success: function(data) {
        $('#result-filter-porn-star').empty().html(data).fadeIn();
      }
    })
  })
  $(document).on('click', '#channel-filter-page ul li a', function(e) {
    e.preventDefault();
    var key = $('#channel-page-data').attr('data-key');
    var page = $(this).attr('href').split('page=')[1];
    get_channel_filter(page, key);
  })

  function get_channel_filter(page, key) {
    $.ajax({
      url: base_url + "filter-channel.html&key=" + key + "?page=" + page,
      success: function(data) {
        console.log(data);
        $('#result-filter').empty().html(data).fadeIn();
      }
    })
  }
  $(document).on('click', '#porn-star-filter-page ul li a', function(e) {
    e.preventDefault();
    var key = $(this).attr('data-key');
    var page = $(this).attr('href').split('page=')[1];
    get_porn_star_filter(page, key);
  })

  function get_porn_star_filter(page, key) {
    $.ajax({
      url: base_url + "filter-porn-stars.html&key=" + key + "?page=" + page,
      success: function(data) {
        console.log(data);
        $('#result-filter-porn-star').empty().append(data);
      }
    })
  }
  $(document).on('click', '#on-subscriber', function(e) {
    e.preventDefault();
    var channel_id = $(this).attr('channel-data');
    $.ajax({
      url: base_url + "subscribe&chanel=" + channel_id,
      success: function(data) {
        if (data == 1) {
          $('#channel-msg').empty().append('Thank you for subscribed!');
          $('#channel-subscriber-success').modal('show');
          $('#txt_subscriber').empty().text('Subscribed');
        }
        if (data == 3) {
          $('#channel-msg').empty().append('Thank you for subscribed!');
          $('#channel-subscriber-success').modal('show');
          $('#txt_subscriber').empty().text('Subscribed');
        }
        if (data == 2) {
          $('#channel-msg').empty().append('You already subscribe this channel!');
          $('#channel-subscriber-success').modal('show');
        }
        if (data == 0) {
          $('#channel-msg').empty().append('You must first login to your account to subscribe to this channel. If you do not yet have an account <a id="subscribe-show-login" data-toggle="modal" data-target="#signup" href="#">click here</a> to create one');
          $('#channel-subscriber-success').modal('show');
        }
      }
    })
  })
  $('#channel-subscriber-success').click(function(e) {
    $('#channel-subscriber-success').modal('hide');
  })
  $(document).on('click', '#send-mail-box', function() {
    $('#modal-msg-box').modal('show');
  });
  $(document).on('click', '#send-msg', function(e) {
    e.preventDefault();
    $.ajax({
      url: base_url + "private-msg",
      type: "POST",
      data: {
        'string_id': $('input[name=string_id]').val(),
        'msg_email': $('input[name=msg-email]').val(),
        'msg_content': $('textarea[name=msg-content]').val(),
        '_token': $('input[name=_token]').val()
      },
      success: function(data) {
        if (data == 0) {
          $('#share_video_response').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong> Invalid Email must be not null !</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 1) {
          $('#share_video_response').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong> Invalid Email format !</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 2) {
          $('#share_video_response').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong> Invalid Content Only text success !</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 3) {
          $('#share_video_response').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong> Invalid Content must be not null !</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 4) {
          $('#share_video_response').html('<div class="alert alert-success"><span  class="glyphicon glyphicon-ok"></span><strong> Messges has been sent successfully !</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 5) {
          $('#share_video_response').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong>You sent the message one time before. Please wait for reply</strong></div>').fadeIn().delay(10000).fadeOut();
        }
      }
    })
  })
  $(document).on('click', '#profile-send-comment', function(e) {
    e.preventDefault();
    $.ajax({
      url: base_url + "profile-comment",
      type: "POST",
      data: {
        'profile_comment_text': $('input[name=profile-comment-text]').val(),
        'profileid': $('input[name=id]').val(),
        '_token': $('input[name=_token]').val()
      },
      success: function(data) {
        console.log(data);
        $('#update-comment').prepend(data);
        $('input[name=profile-comment-text]').val("");
      }
    });
  });
  $(document).on('click', '#close-ads', function(e) {
    e.preventDefault();
    $('#text_ads').fadeOut();
  })
  $(document).on('click', '#upload-video', function(e) {
    e.preventDefault();
    $(this).addClass('active');
    $('#all-video').removeClass('active');
    $.ajax({
      url: base_url + "upload-video.html&action=get_temp",
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#result-upload').empty().html(data).fadeIn();
        }
      },
      beforeSend: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  })
  $(document).on('click', '#member-upload', function(e) {
    $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>Processing...").show();
  })
  $(document).on('click', '#all-video', function(e) {
    $('#upload-video').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
    $.ajax({
      url: base_url + "show-video-upload.html",
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        } else {
          $('#result-upload').empty().html(data).fadeIn();
        }
      },
      beforeSend: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/").hide();
      }
    })
  })
  $(document).on('click', '#subscription', function() {
    $(this).addClass('active');
    $('#channel').removeClass('active')
    $.ajax({
      url: base_url + "show-payment",
      success: function(data) {
        if (data == 0) {
          $('#myModal').modal('show');
        }
        if (data == 1) {
          $('#subscribe').html('<span>You currently are not subscribed to any channels</span>').fadeIn();
        }
        if (data.length > 10) {
          $('#subscribe').html(data).fadeIn();
        }
      },
      beforeSend: function() {
        $('#subscribe-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#subscribe-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    })
  })
  $(document).on('click', '#edit-video', function(e) {
    e.preventDefault();
    var video_id = $(this).attr('data-id');
    $.ajax({
      url: base_url + "member-delete-video&id=" + video_id,
      success: function(data) {
        if (data == 1) {
          $('#loading-upload').html('<div class="alert alert-success"><span  class="glyphicon glyphicon-ok"></span><strong> ' + language.DELETED_SUCCESSFULLY + '</strong></div>').fadeIn().delay(10000).fadeOut();
          $('#result-upload').load(base_url + 'show-video-upload.html');
        }
        if (data == 0) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> ' + language.VIDEO_NOT_FOUND + '</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 2) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> ' + language.DELETED_UNSUCCESSFULLY + '</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 3) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> ' + language.DELETED_UNSUCCESSFULLY + '</strong></div>').fadeIn().delay(10000).fadeOut();
        }
      }
    })
  })
  $(document).on('click', '#edit-thumbnail', function(e) {
    e.preventDefault();
    var video_id = $(this).attr('data-id');
    $.ajax({
      url: base_url + "member-edit-thumbnail-video&id=" + video_id,
      success: function(data) {
        if (data != 2 && data != 0 && data != 3) {
          $('#edit-video-upload').empty().append(data);
        }
        if (data == 2) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> File not exites!</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 0) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> File not found !</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 3) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> Please login your account !</strong></div>').fadeIn().delay(10000).fadeOut();
          window.location.reload();
        }
      },
      beforeSend: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> Please waiting...").show();
      },
      complete: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> Please waiting...").hide();
      }
    })
  })
  $(document).on('click', '.chose_thumb', function(e) {
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    var data_id_split = data_id.split('_');
    var video_id = data_id_split[0];
    var thumb_id = data_id_split[1];
    $('#show-thumb span').removeClass('chose');
    $('#chose_complete_' + thumb_id + '').addClass('chose');
    $('#save-thumb').fadeIn();
    $('#save-thumb').attr('data-id', data_id);
  })
  $(document).on('click', '#save-thumb', function(e) {
    e.preventDefault();
    var data = $(this).attr('data-id');
    $.ajax({
      url: base_url + "save-thumb&t=" + data,
      success: function(data) {
        if (data == 1) {
          $('#loading-upload').html('<div class="alert alert-success"><span  class="glyphicon glyphicon-ok"></span><strong> Thumbnail has been update successfully !</strong></div>').fadeIn().delay(10000).fadeOut();
          $('#result-upload').load(base_url + '/show-video-upload.html');
        }
        if (data == 2) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> Please login your account !</strong></div>').fadeIn().delay(10000).fadeOut();
          window.location.reload();
        }
        if (data == 4) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> Request not found!</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 3) {
          $('#loading-upload').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> Request not found!</strong></div>').fadeIn().delay(10000).fadeOut();
        }
      },
      beforeSend: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> Please waiting...").show();
      },
      complete: function() {
        $('#loading-upload').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> Please waiting...").hide();
      }
    })
  })
  $(document).on('click', '#resgist-channel', function(e) {
    e.preventDefault();
    $.ajax({
      url: base_url + "channel-regist",
      type: "POST",
      data: {
        'user_mail': $('input[name=user_mail]').val(),
        'channel_name': $('input[name=channel_name]').val(),
        'channel_description': $('textarea[name=channel_description]').val(),
        'channel_logo': $('input[name=channel_logo]').val(),
        '_token': $('input[name=_token]').val()
      },
      success: function(data) {
        if (data == 1) {
          $('#msg-channel').html('<div class="alert alert-success"><span  class="glyphicon glyphicon-ok"></span><strong> Register successfully. Please wait for approve by Administrator!</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 0) {
          $('#msg-channel').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-ok"></span><strong> You are already register. Please wait to check by Administrator!</strong></div>').fadeIn().delay(10000).fadeOut();
        }
        if (data == 2) {
          $('#myModal').modal('show');
        }
      },
      beforeSend: function() {
        $('#load-channel').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> Please waiting...").show();
      },
      complete: function() {
        $('#load-channel').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/> Please waiting...").hide();
      }
    })
  })
  $("#channel-overview").click(function(e) {
    e.preventDefault();
    $(this).addClass('active');
    $('#channel-dashboard').removeClass('active')
    $.ajax({
      type: "GET",
      url: base_url + "change-channel.html",
      success: function(data) {
        if (data == 1) {
          console.log("asd")
        }
        if (data == 0) {
          $('#myModal').modal('show');
        }
        if (data.length > 10) {
          $('#setting-channel').empty().append(data)
        }
      },
      beforeSend: function() {
        $('#setting-load-channel').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#setting-load-channel').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    });
  });
  $("#channel-dashboard").click(function(e) {
    e.preventDefault();
    $(this).addClass('active');
    $('#channel-overview').removeClass('active')
    $.ajax({
      type: "GET",
      url: base_url + "channel-dashboard.html",
      success: function(data) {
        if (data == 1) {
          console.log("asd")
        }
        if (data == 0) {
          $('#myModal').modal('show');
        }
        if (data.length > 10) {
          $('#setting-channel').empty().append(data)
        }
      },
      beforeSend: function() {
        $('#setting-load-channel').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
      },
      complete: function() {
        $('#setting-load-channel').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
      }
    });
  });
  $(document).on('click', '#video-filter li a', function(e) {
    e.preventDefault();
    var url = $(this).attr("data-value");
    window.location.href = base_url + "" + url + "";
  })
  $(document).on('click', '#chose-date li a', function(e) {
    var data_date = $(this).attr('role');
    var full_text = $(this).attr('full-text');
    $('#txt-date').empty().text(full_text);
    $('#date-sort').attr('data-date', data_date);
    var time = $('#set-time').attr('data-time');
    var url = base_url + "top-rate.html&date=" + data_date + "&time=" + time;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        console.log(data);
        $('#top-rate-fillter').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  })
  $(document).on('click', '#chose-time li a', function(e) {
    var data_date = $('#date-sort').attr('data-date');
    var time = $(this).attr('role');
    $('#set-time').attr('data-time', time);
    var url = base_url + "top-rate.html&date=" + data_date + "&time=" + time;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#top-rate-fillter').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  })
  $(document).on('click', '#page_toprate_fileter ul li a', function(e) {
    e.preventDefault();
    var data_date = $('#hiden-data-date-time').attr('data-date');
    var data_time = $('#hiden-data-date-time').attr('data-time');
    var page = $(this).attr('href').split('page=')[1];
    get_top_rate_filter(page, data_date, data_time);
  })

  function get_top_rate_filter(page, data_date, data_time) {
    var url = base_url + "top-rate.html&date=" + data_date + "&time=" + data_time + "?page=" + page;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#top-rate-fillter').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  }
  $(document).on('click', '#chose-date-view li a', function(e) {
    var data_date = $(this).attr('role');
    var full_text = $(this).attr('full-text');
    $('#txt-date-view').empty().text(full_text);
    $('#date-sort-view').attr('data-date', data_date);
    var time = $('#set-view-time').attr('data-time');
    var url = base_url + "most-view.html&date=" + data_date + "&time=" + time;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        console.log(data);
        $('#most-view-fillter').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  })
  $(document).on('click', '#chose-time-view li a', function(e) {
    var data_date = $('#date-sort-view').attr('data-date');
    var full_text = $(this).attr('full-text');
    $('#txt-time-view').empty().text(full_text);
    var time = $(this).attr('role');
    $('#set-view-time').attr('data-time', time);
    var url = base_url + "most-view.html&date=" + data_date + "&time=" + time;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#most-view-fillter').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  })
  $(document).on('click', '#page_mostview_fileter ul li a', function(e) {
    e.preventDefault();
    var data_date = $('#hiden-data-mostview-time').attr('data-date');
    var data_time = $('#hiden-data-mostview-time').attr('data-time');
    var page = $(this).attr('href').split('page=')[1];
    get_top_view_filter(page, data_date, data_time);
  })

  function get_top_view_filter(page, data_date, data_time) {
    var url = base_url + "most-view.html&date=" + data_date + "&time=" + data_time + "?page=" + page;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#most-view-fillter').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  }
  $(document).on('click', '.categories-sort', function(e) {
    e.preventDefault();
    var action = $(this).attr('data-action');
    var cat = $(this).attr('data-categories');
    var name = $(this).attr('data-name');
    var time = $('#hidden-action').attr('data-time')
    if (typeof time == 'undefined') {
      time = 'all';
    }
    window.location.href = base_url + 'categories/' + cat + '.' + name + '.html&sort=' + action + '&time=' + time;
  });
  $(document).on('click', '#chose-time-cat li a', function(e) {
    e.preventDefault();
    var cat = $(this).attr('data-categories');
    var name = $(this).attr('data-name');
    var action = $('#hidden-action').attr('data-action');
    var time = $(this).attr('role');
    var full_text = $(this).attr('full-text');
    window.location.href = base_url + 'categories/' + cat + '.' + name + '.html&sort=' + action + '&time=' + time;
  })
  $(document).on('click', '#sort_by li a', function(e) {
    e.preventDefault();
    var full_text = $(this).attr('full-text');
    $("#sort_by li a").removeClass('active');
    $("#close-sort").removeClass('open');
    $("#txt-sort-by").empty().text(full_text);
    $(this).addClass('active');
    var sort_by = $(this).attr('data-sort');
    var sort_date = $('#sort_date_default').attr('value');
    var sort_time = $('#sort_time_default').attr('value');
    $('#sort_by_default').attr('value', sort_by);
    var keywork = $('#keyword').attr('value');
    var url = base_url + "search.html&keyword=" + keywork + "&sort=" + sort_by + "&date=" + sort_date + "&duration=" + sort_time + "";
    $.ajax({
      url: url,
      success: function(data) {
        $('#ajax-result-content').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    });
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    return false;
  })
  $(document).on('click', '#sort_date li a', function(e) {
    e.preventDefault();
    $("#sort_date li a").removeClass('active');
    $(this).addClass('active');
    $("#close-date").removeClass('open');
    var full_text = $(this).attr('full-text');
    $("#txt-date-sort").empty().text(full_text);
    var sort_date = $(this).attr('data-sort-date');
    var sort_by = $('#sort_by_default').attr('value');
    var sort_time = $('#sort_time_default').attr('value');
    $('#sort_date_default').attr('value', sort_date);
    var keywork = $('#keyword').attr('value');
    var url = base_url + "search.html&keyword=" + keywork + "&sort=" + sort_by + "&date=" + sort_date + "&duration=" + sort_time + "";
    $.ajax({
      url: url,
      success: function(data) {
        $('#ajax-result-content').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    });
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    return false;
  })
  $(document).on('click', '#sort_time li a', function(e) {
    e.preventDefault();
    $("#sort_time li a").removeClass('active');
    $(this).addClass('active');
    $("#close-duration").removeClass('open');
    var full_text = $(this).attr('full-text');
    $("#txt-duration-sort").empty().text(full_text);
    var sort_date = $('#sort_date_default').attr('value');
    var sort_by = $('#sort_by_default').attr('value');
    var sort_time = $(this).attr('data-sort-time');
    $('#sort_time_default').attr('value', sort_time);
    var keywork = $('#keyword').attr('value');
    var url = base_url + "search.html&keyword=" + keywork + "&sort=" + sort_by + "&date=" + sort_date + "&duration=" + sort_time + "";
    $.ajax({
      url: url,
      success: function(data) {
        $('#ajax-result-content').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    });
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    return false;
  })
  $(document).on('click', '#search-filter-page ul li a', function(e) {
    e.preventDefault();
    var data_date = $(this).attr('data-date');
    var data_time = $(this).attr('data-time');
    var href = $(this).attr('href').split('?page=')[0];
    var page = $(this).attr('href').split('page=')[1];
    get_search_page_filter(href, page);
  })

  function get_search_page_filter(href, page) {
    var full_uri = href + "?page=" + page;
    $.ajax({
      url: href + "?page=" + page,
      success: function(data) {
        $('#ajax-result-content').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
    if (full_uri != window.location) {
      window.history.pushState({
        path: full_uri
      }, '', full_uri);
    }
    return false;
  }
  $(document).on('click', '#sort-premium li', function(e) {
    e.preventDefault();
    var action = $(this).attr('data-action');
    var url = base_url + "premium-hd.html&sort=" + action + "";
    window.location.href = "" + url + "";
  })
  $(document).on('click', '#chose_local li a', function(e) {
    var lang = $(this).attr('lang');
    var url = "" + base_url + "/" + lang;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
  })
  $(document).on('click', '#chose-date-video li a', function(e) {
    var action = $('#chose-date-video').attr('data-action');
    var data_date = $(this).attr('role');
    var full_text = $(this).attr('full-text');
    $('#txt-date-video').empty().text(full_text);
    $('#date-sort-video').attr('data-date', data_date);
    var time = $('#set-time-video').attr('data-time');
    var url = base_url + "video.html&action=" + action + "&date=" + data_date + "&time=" + time;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#result-video-filter-page').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  })
  $(document).on('click', '#chose-time-video li a', function(e) {
    var action = $('#chose-time-video').attr('data-action');
    var data_date = $('#date-sort-video').attr('data-date');
    var time = $(this).attr('role');
    var full_text = $(this).attr('full-text');
    $('#txt-time-video').empty().text(full_text);
    $('#set-time-video').attr('data-time', time);
    var url = base_url + "video.html&action=" + action + "&date=" + data_date + "&time=" + time;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#result-video-filter-page').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  })
  $(document).on('click', '#result-video-filter-paginate ul li a', function(e) {
    e.preventDefault();
    var action = $('#date-sort-video').attr('data-action')
    var data_date = $('#date-sort-video').attr('data-date');
    var data_time = $('#set-time-video').attr('data-time');
    var page = $(this).attr('href').split('page=')[1];
    get_video_paginate_filter(page, action, data_date, data_time);
  })

  function get_video_paginate_filter(page, action, data_date, data_time) {
    url = base_url + "video.html&action=" + action + "&date=" + data_date + "&time=" + data_time + "?page=" + page;
    if (url != window.location) {
      window.history.pushState({
        path: url
      }, '', url);
    }
    $.ajax({
      url: url,
      success: function(data) {
        $('#result-video-filter-page').empty().append(data);
      },
      beforeSend: function() {
        $('#jax-loading').modal('show');
      },
      complete: function() {
        $('#jax-loading').modal('hide');
      }
    })
  }
  $("#show-about").click(function(e) {
    e.preventDefault();
    $(this).closest("div").toggleClass("expanded")
  });
  $(document).on('click', '[id*="pornvote-"]', function(event) {
    event.preventDefault();
    var vote_id = $(this).attr("id");
    var id_split = vote_id.split('-');
    var vote = id_split[1];
    var item_id = id_split[2];
    $.ajax({
      url: base_url + 'pornstar_rating&type=' + vote + '&id=' + item_id,
      success: function(data) {
        if (data['msg'] !== '') {
          $("#msg-rating").animate({
            'opacity': 0
          }, 200, function() {
            $(this).html('<center class="text-danger">' + data['msg'] + '</center>');
          }).animate({
            'opacity': 1
          }, 200);
          $("#msg-rating").delay(5000).animate({
            'opacity': 0
          }, 200, function() {
            $(this).html('<a href="javascript:void(0);" id="pornvote-like-' + item_id + '"  class="tt vUp" title="Like"><i class="fa fa-thumbs-o-up"></i><span>' + data['like'] + '</span></a><a href="javascript:void(0);" id="pornvote-dislike-' + item_id + '" class="tt vDn" title="Dislike"><i class="fa fa-thumbs-o-down"></i><span>' + data['dislike'] + '</span></a><span class="result"><span  style="width:' + data['percent_like'] + '%;"></span></span>')
          }).animate({
            'opacity': 1
          }, 200);
        } else {
          if (data['like'] != 0 || data['dislikes'] != 0) {
            $("#msg-rating .result span").css("width", data['percent_like'] + "%");
            if (data['like'] != $("#pornvote-like-" + item_id + " span").text()) {
              $("#pornvote-like-" + item_id + " span").animate({
                'opacity': 0
              }, 200, function() {
                $(this).text(data['like']);
              }).animate({
                'opacity': 1
              }, 200);
            }
            if (data['dislike'] != $("#pornvote-dislike-" + item_id + " span").text()) {
              $("#pornvote-dislike-" + item_id + " span").animate({
                'opacity': 0
              }, 200, function() {
                $(this).text(data['dislike']);
              }).animate({
                'opacity': 1
              }, 200);
            }
          } else {
            $("#msg-rating").animate({
              'opacity': 0
            }, 200, function() {
              $(this).html('<center class="text-danger"><a data-toggle="modal" data-target="#myModal" href="#">Login to vote</a></center>');
            }).animate({
              'opacity': 1
            }, 200);
            $("#msg-rating").delay(5000).animate({
              'opacity': 0
            }, 200, function() {
              $('#pornvote-like-' + item_id + ' span').html(data['like']);
              $('#pornvote-dislikelike-' + item_id + 'span').html(data['dislike']);
            }).animate({
              'opacity': 1
            }, 200);
          }
        }
      }
    })
  });
  $(document).on('click', '[id*="pornmenu-"]', function(event) {
    event.preventDefault();
    var vote_id = $(this).attr("id");
    var id_split = vote_id.split('-');
    var porn_menu = id_split[1];
    var item_id = id_split[2];
    if (porn_menu == "video") {
      $('#pornmenu-video-' + item_id + '').addClass('active')
      $('#pornmenu-photo-' + item_id + '').removeClass('active')
      var href = $(this).attr('data-href');
      if (href != window.location) {
        window.history.pushState({
          path: href
        }, '', href);
      }
      $.ajax({
        url: href,
        success: function(data) {
          $('#menu_result').empty().append(data);
        },
        beforeSend: function() {
          $('#jax-loading').modal('show');
        },
        complete: function() {
          $('#jax-loading').modal('hide');
        }
      });
    } else {
      $('#pornmenu-video-' + item_id + '').removeClass('active')
      $('#pornmenu-photo-' + item_id + '').addClass('active')
      var href = $(this).attr('data-href');
      var href = $(this).attr('data-href');
      if (href != window.location) {
        window.history.pushState({
          path: href
        }, '', href);
      }
      $.ajax({
        url: href,
        success: function(data) {
          $('#menu_result').empty().append(data);
        },
        beforeSend: function() {
          $('#jax-loading').modal('show');
        },
        complete: function() {
          $('#jax-loading').modal('hide');
        }
      });
    }
  });
});
