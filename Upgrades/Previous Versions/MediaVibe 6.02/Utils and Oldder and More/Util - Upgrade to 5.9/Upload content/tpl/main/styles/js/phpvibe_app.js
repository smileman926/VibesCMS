/*!
 * phpVibe v5
 *
 * Copyright Media Vibe Solutions
 * http://www.phpvibe.com
 * phpVibe IS NOT FREE SOFTWARE
 * If you have downloaded this CMS from a website other
 * than www.phpvibe.com or www.phpRevolution.com or if you have received
 * this CMS from someone who is not a representative of phpVibe, you are involved in an illegal activity.
 * The phpVibe team takes actions against all unlincensed websites using Google, local authorities and 3rd party agencies.
 * Designed and built exclusively for sale @ phpVibe.com & phpRevolution.com.
 */
//Initialize
jQuery(function($) {
    /*Detect touch device*/
    var tryTouch;
    try {
        document.createEvent("TouchEvent");
        tryTouch = 1;
    } catch (e) {
        tryTouch = 0;
    }
    /*Browser detection*/
    var $is_mobile = false;
    var $is_tablet = false;
    var $is_pc = false;
    if ($(window).width() < 500) {
        $is_mobile = true;
    } else if ($(window).width() < 1000) {
        $is_tablet = true;
    } else {
        $is_pc = true;
    }
    $('.tags').tagsInput({
        width: '100%',
        height: '50px'
    });
    $(".select").minimalect();
 	$(".pv_pop ,[rel=popover], .doPop").popover(); 
	$('.pv_tip, .tipN, .tipS, .tipW, .tipE').tooltip({
    'trigger' : 'hover'
    }); 
    $('#share-embed-code, #share-embed-code-small, #share-embed-code-large, #share-this-link').tooltip({
        'trigger': 'focus'
    });
	$('#myTab a,#myTabs a').click(function (e) {
     e.preventDefault();
     $(this).tab('show');
    });
  
    $('.dropdown-toggle').dropdown();
    var bodyHeight = $(window).height();
    var convBody = bodyHeight - 178;
    $('.app-message-chats').slimScroll({
        height: convBody,
        size: 4,
        start: 'bottom',
        color: '#a3afb7',
        railOpacity: 0.2,
        wheelStep: 10,
        allowPageScroll: false
    });
    $('.page-aside-inner ').slimScroll({
        height: convBody,
        size: 4,
        start: 'top',
        color: '#a3afb7',
        railOpacity: 0.2
    });
	
    var vh = $("#video-content").height() - 3;
    if ($is_mobile) {
        $('.scroll-items').slimScroll({
            height: 186,
			wheelStep : 10
        });
        $('.items').slimScroll({
            height: 180,
			start: $('li#playingNow'),
			wheelStep : 10
        });
    } else {
        $('.scroll-items').slimScroll({
            height: 340,
			wheelStep : 10
        });
        $('.items').slimScroll({
            height: vh,
			start: $('li#playingNow'),
			wheelStep : 10
        });
    }
	  var sum = 0;
	   $('li#playingNow').prevAll().each(function() {
	   sum += 66;
	   }); 
	   sum = sum - 190; 
	   $('.items').animate({scrollTop: sum}, 'slow');

	   
	var sidebarsh = screen.height - 67;
    $('.sidescroll').slimScroll({
        height: sidebarsh,
        position: 'left',
        size: 1,
        railOpacity: '0.001',
        color: '#fff',
        railColor: '#fff',
		wheelStep : 10
    });
    /* Ajax forms */
    $('.ajax-form').ajaxForm({
        target: '.ajax-form-result',
        success: function(data) {
            $('.ajax-form').hide();
        }
    });
    $('.ajax-form-video').ajaxForm({
        target: '.ajax-form-result',
        success: function(data) {}
    });
    /* Infinite scroll for videos */
    var $container = $('.loop-content:last');
    if ($('#page_nav').html()) {
        $container.infinitescroll({
                navSelector: '#page_nav', // selector for the paged navigation 
                nextSelector: '#page_nav a', // selector for the NEXT link (to page 2)
                itemSelector: '.video', // selector for all items you'll retrieve
                bufferPx: 60,
                loading: {
                    msgText: 'Loading next',
                    finishedMsg: 'The End.',
                    img: site_url + 'tpl/main/images/load.gif'
                }
            },
            function(newElements) {
                var $newElems = jQuery(newElements).hide(); // hide to begin with
                // ensure that images load before adding to layout
                $newElems.imagesLoaded(function() {
                    $newElems.fadeIn(); // fade in when ready	
                });
            });
    };
    /* Infinite scroll for music */
    var $mcontainer = $('ul.songs');
    if ($('#page_nav').html()) {
        $mcontainer.infinitescroll({
                navSelector: '#page_nav', // selector for the paged navigation 
                nextSelector: '#page_nav a', // selector for the NEXT link (to page 2)
                itemSelector: 'li.song', // selector for all items you'll retrieve
                bufferPx: 60,
                loading: {
                    msgText: 'Loading next',
                    finishedMsg: 'The End.',
                    img: site_url + 'tpl/main/images/load.gif'
                }
            },
            function(newmElements) {
                var $newmElems = jQuery(newmElements).hide(); // hide to begin with
                // ensure that images load before adding to layout
                $newmElems.imagesLoaded(function() {
                    $newmElems.fadeIn(); // fade in when ready	
                });
            });
    };
    if ($(window).width() < 990) {		
        /* Move related */
        var $mobiR = $('.related').clone();
        $('.related-mobi').prepend($mobiR);
        $('.sharing-icos').addClass('hide');
        $('.rur').remove();
		$('.related li:nth-child(8)').nextAll().addClass('hide');
       var $listH = $('.playlistvibe').clone();
	   $('#ListRelated').prepend($listH);
	   $('#LH, .fullit').remove();
	   $('.next-an').html('');
	   $('.next-an').html('<a href="javascript:void(0)" class="vlist-pull"><i class="icon icon-angle-up"></i></a>');
       $(".vlist-pull").click(function() {
	   $("#ListRelated > .video-player-sidebar").toggleClass("hide");
       $(".vlist-pull > i").toggleClass("icon-angle-up").toggleClass("icon-angle-down");
	   });	
	}
    $('.related').imagesLoaded(function() {
        $('.related').removeClass('hide');
        $('.relatedLoader').hide();
    });
    $('.video-player-sidebar').imagesLoaded(function() {
        $('.video-player-sidebar').removeClass('hide');
        $('.vpLoader').hide();
    });
    if ($('#home-content').html()) {
        setTimeout(function() {
            $('.loop-content, .gfluid').removeClass('hide');
            $('.homeLoader').hide();
        }, 600);
    }
    if ($('.gfluid').html()) {
        /* Gallery grid */
        var $gitem = 0;
        var $gcontainer = $(".gfluid").parent().width();
        if ($gcontainer < 500) {
            $gitem = $gcontainer / 1;
        } else if ($gcontainer < 900) {
            $gitem = $gcontainer / 2;
        } else if ($gcontainer < 1200) {
            $gitem = $gcontainer / 3;
        } else {
            $gitem = $gcontainer / 4;
        }
        $(".gfluid").gridalicious({
            gutter: 4,
            width: $gitem,
            animate: true,
            animationOptions: {
                speed: 200,
                duration: 300
            },
        });
        /* Infinite scroll for image gallery */
        var $igcontainer = $('.gfluid');
        if ($('#page_nav').html()) {
            $igcontainer.infinitescroll({
                    navSelector: '#page_nav', // selector for the paged navigation 
                    nextSelector: '#page_nav a', // selector for the NEXT link (to page 2)
                    itemSelector: '.image-item', // selector for all items you'll retrieve
                    bufferPx: 60,
                    loading: {
                        msgText: 'Loading next',
                        finishedMsg: 'The End.',
                        img: site_url + 'tpl/main/images/load.gif'
                    }
                },
                function(newmElements) {
                    var $newigElems = jQuery(newmElements).hide(); // hide to begin with
                    // ensure that images load before adding to layout
                    $newigElems.imagesLoaded(function() {
                        $newigElems.fadeIn(); // fade in when ready	
                        $('.gfluid').gridalicious('append', $newigElems);
                    });
                });
        };
        /* End gallery */
    }
    /* Size categories */
    if ($('.cats').html()) {
        if ($is_mobile || $is_tablet ) {
		var $size = parseInt($(".cats").width());	
		} else {
        var $size = parseInt($(".cats").width() - 37);
		}
        $('.cats').css({
            right: "-" + $size + "px"
        });
        $('.cats').prepend('<i class="icon icon-chevron-left cats-pull"></i>');
        //Cats bar
        $(".cats-pull").click(function() {
            $(".cats").toggleClass("cats-visible");
            if ($(".cats-pull").hasClass("icon-chevron-left")) {
                $(".cats").css({
                    right: 0
                });
            } else {
                $('.cats').css({
                    right: "-" + $size + "px"
                });
            }
            $(".cats-pull").toggleClass("icon-chevron-left").toggleClass("icon-chevron-right");
        });
        //Cats scroll
        var catsBody = parseInt($(".cats").height() - 2);
        $('.cats-inner').slimScroll({
            height: catsBody,
            size: 4,
            color: '#a3afb7',
            railOpacity: 0.2,
            wheelStep: 10,
            allowPageScroll: false
        });
    }
    $('#searchform input').blur(function()
    {
        if ($(this).val()) {
            $('#searchform input').removeClass('empty');
        }
        if (!$(this).val()) {
            $('#searchform input').addClass('empty');
        }
    });
    /* END */
});
$(window).resize(function() {
    if ($(window).width() < 1530) {
        $("#sidebar").addClass("hide");
		 $("#wrapper").removeClass('haside');
    }
    //Mobile search
    if ($(window).width() < 1000) {
        if (!$(".searchWidget").hasClass("hide")) {
            $(".searchWidget").addClass("hide");
        }
    }
    //Goes both ways
    if ($(window).width() > 1000) {
        if ($(".searchWidget").hasClass("hide")) {
            $(".searchWidget").removeClass("hide");
        }
    }
    //Chat app resize
    var bodyHeight = $(window).height();
    var convBody = bodyHeight - 178;
    $('.app-message-chats').slimScroll({
        height: convBody,
        start: 'bottom'
    });
    $('.page-aside-inner ').slimScroll({
        height: convBody,
        start: 'top'
    });
});
/* Doc ready*/
$(document).ready(function() {
    $('img').error(function() {
        $(this).attr('src', site_url + 'uploads/noimage.png');
    });
    $.getJSON(site_url + "api/noty/", function(data) {
        if (data) {
            if(data.msg) {
            $("li.my-inbox > a").append('<span class="badge badge-danger pull-right">'+ data.msg + '</span>');
			}	
            if(data.buzz) {
			$("a#notifs").append('<span class="badge badge-primary">'+ data.buzz + '</span>');
			}
			 var $da =  parseInt(data.msg);
			 var $db =  parseInt(data.buzz);
             var $dc = 	$da + $db;		
			if($dc > 0) {
			$("a#openusr").prepend('<span class="badge badge-success pull-right">'+ $dc + '</span>');
			}
        }
    }).error(function(jqXHR, textStatus, errorThrown) {
        console.log("error " + textStatus);
        console.log("incoming Text " + jqXHR.responseText);
    });
	
	// Hide owl on mobiles	
    $(".owl-carousel").owlCarousel({
	  responsiveClass:true,
        items: 1,
        navigation: true,
		navText: ["<i class='icon-chevron-left icon-white'></i>","<i class='icon-chevron-right icon-white'></i>"],
		loop : true,
		 lazyLoad:true,
       responsive:{
        0:{
            items:1,
            nav:true
        },
		460:{
            items:2,
            nav:true
        },
        668:{
            items:3,
            nav:true
        },
		920:{
            items:4,
            nav:true
        },
        1200:{
            items:5,
            nav:true,
            loop:true
        }
		,
        1600:{
            items:6,
            nav:true,
            loop:true
        }
    }
    });
    if ($(window).width() < 1530) {
        $("#sidebar").addClass("hide");
		 $("#wrapper").removeClass('haside');
    }
    //Emoticons
    $('.message .body').emotions();
    $('.chat .chat-content > p').emotions();
    $('.emoji-holder').emotions();
    //Chat
    $("#showEmoji").click(function() {
        $('.emoji-holder').toggleClass('hide');
    });
    $(".emoji-holder > img").click(function() {
        var emojiT = $(this).attr('title');
        var currentText = $('textarea#insertChat').val();
        $('textarea#insertChat').val(currentText + " :" + emojiT + ": ")
    });
    $("#sendChat").click(function() {
        if (!$('.emoji-holder').hasClass('hide')) {
            $('.emoji-holder').toggleClass('hide');
        }
        if ($('textarea#insertChat').val()) {
            var conv = $('.chats').attr("id");
            var fakebody = '<div class="chat chat-left animated rollIn">' + $(".dummy-chat").html() + '</div>';
            $('.chats').append(fakebody);
            $(".chat-content:last").html("<p>" + $('textarea#insertChat').val() + "</p>");
            $('.app-message-chats').slimscroll({
                scrollBy: '39px'
            });
            $('.chat-content:last > p').emotions();
            $('.tipS').tooltip();
            $.post(
                site_url + 'lib/ajax/reply.php', {
                    message: encodeURIComponent($('textarea#insertChat').val()),
                    conversation: conv
                },
                function(data) {
                    if (data.ok > 0) {
                        $('textarea#insertChat').val('');
                    } else {
                        $(".chat-content:last").addClass("errored");
                    }
                }, "json");
        } else {
            $('#insertChat').focus();
        }
        return false;
    });
    //Fill the screen
    $(".fullit").click(function() {
        $("#renderPlaylist").toggleClass('gofullscreen');
	    $("#flT").toggleClass('icon-television').toggleClass('icon-compress');
    });
	//Get next playlist
	//video
	if($("li#playingNow").html()) {	
	var nextPlayed = $("li#playingNow").next().find("a.clip-link");
	$('#ComingNext').attr('href',nextPlayed.attr("href"));
	$('#ComingNext').attr('data-original-title', nextPlayed.attr("title"));
	}
    //Kill ad
    $(".close-ad").click(function() {
        $(this).closest(".adx").hide();
    });
    //Add to
    $("#addtolist").click(function() {
        $("#bookit").slideToggle();
    });
    //Disabled comment
    $('textarea#addDisable').on("focus", function() {
        showLogin()
    });
    //Show more desc
    $("#revealDesc").click(function() {
        $("#longD,#smallD,li#vTags,#revealDesc > span").toggleClass('hide');
    });
	//Show more related
    $("#revealRelated").click(function() {
		$('.related li:nth-child(8)').nextAll().toggleClass('hide');
		$("#revealRelated > span").toggleClass('hide');
    });
    //Embed
    $("#embedit").click(function() {
        $(".video-share").toggleClass('hide');
    });
    //Mobi Share
    $("#social-share").click(function() {
        $(".sharing-icos").toggleClass('hide');
    });
    //Chat handler
    $(".page-aside-switch , page-aside-switch > i").click(function() {
        $(".page-aside").toggleClass('open');
    });
    //Sidebar 
    $("#show-sidebar").click(function() {
        $("#sidebar").toggleClass('hide');
        $("#wrapper").toggleClass('haside');
        if (!$("#sidebar").hasClass("hide")) {
            $("a#show-sidebar > i").removeClass('icon-bars').addClass('icon-minus');
        }
        if ($("#sidebar").hasClass("hide")) {
            $("a#show-sidebar > i").addClass('icon-bars').removeClass('icon-minus');
        }
        if (!$("#sidebar").hasClass("hide")) {
            var sideSpace = parseInt($("#wrapper").offset().left);
            if (sideSpace < 240) {
                $("#wrapper").css({
                    "margin-left": "240px",
                    "margin-right": "auto"
                });
            }
        } else {
            var sideSpace = $("#wrapper").offset().left;
            if (sideSpace == 240) {
                $("#wrapper").css({
                    "margin-left": "auto",
                    "margin-right": "auto"
                });
            }
        }
    });
    if (!$("#sidebar").hasClass("hide")) {
        var sideSpace = $("#wrapper").offset().left;
        if (sideSpace < 240) {
            $("#wrapper").css({
                "margin-left": "240px",
                "margin-right": "auto"
            }).addClass('haside');
        }
		 
    }
    if (!$("#sidebar").hasClass("hide")) {
        $("a#show-sidebar > i").removeClass('icon-bars').addClass('icon-minus');
    }
    //End sidebar
    //Mobile search
    if ($(window).width() < 1000) {
        $(".searchWidget").addClass("hide");
    }
    //VideoPlayer Container
    var vpWidth = $('.video-player').width();
    var vpHeight = Math.round((vpWidth / 16) * 9);
    $(".video-player").css("min-height", vpHeight);
    //End sidebar
	//Autoplay
	$('#autoplayHandler').change(function(){
		$.get( site_url + "api/autoplay/" );		
		if($(".PlayUP").is("#autoplay")) {
		$(".PlayUP").attr("id","NoAuto");	
		} else {
		$(".PlayUP").attr("id","autoplay");	
		}
	});
	//Table checks
	$('.table-checks .check-all').click(function(){
		var parentTable = $(this).parents('table');										   
		var ch = parentTable.find('tbody input[type=checkbox]');										 
		if($(this).is(':checked')) {
		
			//check all rows in table
			ch.each(function(){ 
				$(this).attr('checked',true);
				$(this).parent().addClass('checked');	//used for the custom checkbox style
				$(this).parents('tr').addClass('selected');
			});
						
			//check both table header and footer
			parentTable.find('.check-all').each(function(){ $(this).attr('checked',true); });
		
		} else {
			
			//uncheck all rows in table
			ch.each(function(){ 
				$(this).attr('checked',false); 
				$(this).parent().removeClass('checked');	//used for the custom checkbox style
				$(this).parents('tr').removeClass('selected');
			});	
			
			//uncheck both table header and footer
			parentTable.find('.check-all').each(function(){ $(this).attr('checked',false); });
		}
	});
    $(".backtotop").addClass("hidden");
    $(window).scroll(function() {
        if ($(this).scrollTop() === 0) {
            $(".backtotop").addClass("hidden")
        } else {
            $(".backtotop").removeClass("hidden")
        }
    });
    $('.backtotop').click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 1200);
        return false;
    });
    if ($(window).width() > 500) {
        var oh = $("#video-content").height() - 3;
        $('.items').parent().replaceWith($('.items'));
        $('.items').slimScroll({
            height: oh
        });
    }
    $('.form-material').each(function() {
        var $this = $(this);
        if ($this.data('material') === true) {
            return;
        }
        var $control = $this.find('.form-control');
        // Add hint label if required
        if ($control.attr("data-hint")) {
            $control.after("<div class=hint>" + $control.attr("data-hint") + "</div>");
        }
        if ($this.hasClass("floating")) {
            // Add floating label if required
            if ($control.hasClass("floating-label")) {
                var placeholder = $control.attr("placeholder");
                $control.attr("placeholder", null).removeClass("floating-label");
                $control.after("<div class=floating-label>" + placeholder + "</div>");
            }
            // Set as empty if is empty
            if ($control.val() === null || $control.val() == "undefined" || $control.val() === "") {
                $control.addClass("empty");
            }
        }
        // Support for file input
        if ($control.next().is("[type=file]")) {
            $this.addClass('form-material-file');
        }
        $this.data('material', true);
    });
    $('.form-material-file [type=file]').on("focus", function() {
        $(".form-material-file .form-control").addClass("focus");
    });
    $('.form-material-file [type=file]').on("blur", function() {
        $(".form-material-file .form-control").removeClass("focus");
    });
    $('.form-material-file [type=file]').on("change", function() {
        var value = "";
        $.each($(this)[0].files, function(i, file) {
            value += file.name + ", ";
        });
        value = value.substring(0, value.length - 2);
        if (value) {
            $(this).prev().removeClass("empty");
        } else {
            $(this).prev().addClass("empty");
        }
        $(this).prev().val(value);
    });
    $('.form-control').on("keyup", function() {
        var $this = $(this);
        if ($this.val() === "") {
            $this.addClass("empty");
        } else {
            $this.removeClass("empty");
        }
    });
	
$('#CustomWidth').keyup(function() {
modIframeW($(this).val());	
});	
$('#CustomHeight').keyup(function() {
modIframeH($(this).val());	
});	
$("input:radio[name=changeEmbed]").click(function() {
    var value = $(this).val();
	 switch (value) {
        case '1':
          modIframeW(1920);
          modIframeH(1080);
          break;
        case '2':
          modIframeW(1280);
          modIframeH(720);
          break;
        case '3':
         modIframeW(854);
         modIframeH(480);
          break;
        case '4':
         modIframeW(640);
         modIframeH(360);
          break;
        default:
          modIframeW(426);
          modIframeH(240);
      }
});	
	
	
    /* End document ready */
});
function iHeartThis(vid) {
    $.post(
        site_url + 'lib/ajax/heart.php', {
            video_id: vid,
            type: 1
        },
        function(data) {
            $('#i-like-it').addClass('done-like');
            var a = JSON.parse(data);
           toastr.success( a.text, a.title);
        });
}
function iLikeThis(vid) {
	if($('#i-like-it').hasClass('done-like')) {
	RemoveLike(vid);	
	} else {
    $.post(
        site_url + 'lib/ajax/like.php', {
            video_id: vid,
            type: 1
        },
        function(data) {
            $('#i-like-it').addClass('done-like');
            var a = JSON.parse(data);
            toastr.success( a.text);
			var likesNr = $('#i-like-it > span').text();
			$('#i-like-it > span').html(++likesNr)
        });
}
}
function DOtrackview(vid) {
    $.post(site_url + 'lib/ajax/track.php', {
            video_id: vid
        },
        function(data) {
            //console.log(data);	
        }
    );
}
function DOtrackviewIMG(vid) {
    $.post(site_url + 'lib/ajax/track-img.php', {
            video_id: $.trim(vid)
        },
        function(data) {
            console.log(data);	
        }
    );
}
function Padd(vid, pid) {
    $.post(
        site_url + 'lib/ajax/addto.php', {
            video_id: vid,
            playlist: pid
        },
        function(data) {
            var a = JSON.parse(data);
            toastr.success( a.text, a.title);
            if ($('li#PAdd-' + pid).html()) {
                $('li#PAdd-' + pid).remove();
            }
            if ($('#video-' + vid).html()) {
                $('#video-' + vid + ' a.laterit').remove();
            }
        });
}
function ReplyCom(cid) {
    $('li#' + cid).toggleClass('hide');
}
function RemoveLike(vid) {
    $.post(
        site_url + 'lib/ajax/dislike.php', {
            video_id: vid,
            type: 1
        },
        function(data) {
            $('#i-like-it').removeClass('isLiked').removeClass('done-like');
            var a = JSON.parse(data);
             toastr.warning( a.text, a.title);
			 var likesNr = $('#i-like-it > span').text();	
			 if(likesNr >= 1) {
			 $('#i-like-it > span').html(likesNr - 1);
			 }
        });
}
function iHateThis(vid) {
	if(!$('#i-dislike-it').hasClass('done-dislike')) {
    $.post(
        site_url + 'lib/ajax/like.php', {
            video_id: vid,
            type: 2
        },
        function(data) {
            $('#i-dislike-it').addClass('done-dislike');
            var a = JSON.parse(data);
            toastr.info( a.text);
			console.log(a);
			var likesNr = $('#i-dislike-it > span').text();
			if(typeof a.added != 'undefined') {
			var ahandler = a.added;
			} else {
			var ahandler = 1;
			}
			if(ahandler == "2") {
			$('#i-dislike-it > span').html(likesNr -1)	
			} else if (ahandler !== "0") {
			 $('#i-dislike-it > span').html(++likesNr)
			}
			
        });
}
}
function showLogin() {
    $('#login-now').modal('toggle');
}
function Subscribe(user, type) {
    $.post(
        site_url + 'lib/ajax/subscribe.php', {
            the_user: user,
            the_type: type
        },
        function(data) {
            var a = JSON.parse(data);
            toastr.success( a.text, a.title);
        });
}
function addEMComment(oid, toid) {
    if ($('textarea#addEmComment_' + toid).val()) {
        $.post(
            site_url + 'lib/ajax/addComment.php', {
                comment: encodeURIComponent($('textarea#addEmComment_' + toid).val()),
                object_id: oid,
                reply: toid
            },
            function(data) {
                $('#emContent_' + oid + '-' + toid + ' li:first').after('<li id="comment-' + data.id + '" class="left animated rollIn"><img class="avatar" src="' + data.image + '" /><div class="message"><span class="arrow"> </span><a class="name" href="' + data.url + '">' + data.name + '</a> <span class="date-time"> ' + data.date + ' </span> <div class="body"></div> </div></li>');
				var shtml = data.text;
				$('#comment-' + data.id).find('.body').html(shtml);
				$('textarea#addEmComment_' + toid).val('');
                $('.body').emotions();
            }, "json");
    } else {
        $('#addEmComment_' + toid).focus();
    }
    return false;
}
function iLikeThisComment(cid) {
    $.post(
        site_url + 'lib/ajax/likeComment.php', {
            comment_id: cid
        },
        function(data) {
            $('#iLikeThis_' + cid + '> a').remove();
            $('#iLikeThis_' + cid + '> .tooltip').remove();
            $('#iLikeThis_' + cid).prepend(data.text + '! &nbsp;');
        }, "json");
}
function processVid(file) {
    $('#vfile').val(file);
    $('#Subtn').prop('disabled', false).html('Save').addClass("btn-success");
}
function DOtrackview(vid) {
    $.post(site_url + 'lib/ajax/track.php', {
            video_id: vid
        },
        function(data) {
            //console.log(data);	
        }
    );
}
function modIframeW(w){
var str = $('#share-embed-code-small').val();                        
str = str.replace(/width="[\s\S]*?"/, 'width="'+ w +'"');
$('#share-embed-code-small').val(str);
}
function modIframeH(h){
var str = $('#share-embed-code-small').val();                        
str = str.replace(/height="[\s\S]*?"/, 'height="'+ h +'"');	
$('#share-embed-code-small').val(str);
}