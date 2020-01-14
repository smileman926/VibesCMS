/*!
 * phpVibe v5
 *
 * Copyright Media Vibe Solutions
 * http://www.phpRevolution.com
 * phpVibe IS NOT FREE SOFTWARE
 * If you have downloaded this CMS from a website other
 * than www.phpvibe.com or www.phpRevolution.com or if you have received
 * this CMS from someone who is not a representative of phpVibe, you are involved in an illegal activity.
 * The phpVibe team takes actions against all unlincensed websites using Google, local authorities and 3rd party agencies.
 * Designed and built exclusively for sale @ phpVibe.com & phpRevolution.com.
 */

jQuery(function($){
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
	
//confirm
$('.confirm').click(function(){
    return confirm("Are you sure you want to delete this? This is permanent");
})

      // Disable # function
      $('a[href="#"]').click(function(e){
        e.preventDefault();
      });


    //-----  Menu functions -----//

    // slide menu out from the left 
    $('.slide_menu_left').click(function(e){
        e.preventDefault();
        if($(".navbar").hasClass('open_left')){
          sidemenu_close();
        }else{
            sidemenu_open();
            $('.main_container').bind('click', function(){
                sidemenu_close();
            });
        }
    });

    // slide menu out
    function sidemenu_close(){
        $(".main_container").stop().animate({
            'left': '0'
        }, 250, 'swing');

        $(".navbar").stop().animate({
            'left': '-200px'
        }, 250, 'swing', function(){
            $(this).css('left', '').removeClass('open_left');
            $(this).children('.sidebar-nav').css('height', '');
        });

        $('.main_container').unbind('click');

        if(typeof handler != 'undefined'){
            $(window).unbind('resize', handler);
        }
    }

    // slide menu in
    function sidemenu_open(){
        $(".main_container").stop().animate({
            'left': '200px'
        }, 250, 'swing');
        $(".navbar").stop().animate({
            'left': '0'
        }, 250, 'swing').addClass('open_left');
        $('.navbar').animate('slow', function(){
            marginLeft:0
        });
    }

    $('.accordion-toggle').removeClass('toggled');
    // fade to white when clicked on mobile
    $('.accordion-toggle').click(function(){
      $('.accordion-toggle').removeClass('toggled');
      $(this).addClass('toggled');
    });
$('.tipN').tipsy({gravity: 'n',fade: true, html:true});
	$('.tipS').tipsy({gravity: 's',fade: true, html:true});
	$('.tipW').tipsy({gravity: 'w',fade: true, html:true});
	$('.tipE').tipsy({gravity: 'e',fade: true, html:true});
	
	$('.auto').autosize();
	$('.tags').tagsInput({width:'100%'});
	//===== Select2 dropdowns =====//

	//$(".select").select2();
	 $(".select").minimalect();
				
	$('body').find('input[type=text]').each(function() {$(this).addClass('form-control');});	
	$('body').find('textarea').each(function() {$(this).addClass('form-control');});	
    $('body').find(':checkbox').each(function() {$(this).addClass('icheckbox-primary');});	
    $('body').find(':radio').each(function() {$(this).addClass('icheckbox-primary');});	
	$('input:not(.check-all,.check-all-notb)').iCheck({mode: 'default', checkboxClass: 'icheckbox_flat-blue',radioClass: 'iradio_flat-blue'});
	$('.pv_tip').tooltip();
	 // Custom scrollbar plugin
	 
	  $('.scroll-items').slimScroll({height:500});
     //$(".video-player").fitVids();
  /* Dual select boxes */	
	$.configureBoxes();
	/* Ajax forms */
		$('.ajax-form').ajaxForm({
			success: function(data) { 
           //alert(data);			
        }
        });
	$("#validate").validationEngine({promptPosition : "topRight:-122,-5"});  

	$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
	
 });  
 
 $( window ).resize(function() {
if ($( window ).width() < 1160) { 
$( "#wrap" ).addClass( "SActive" );
}	
});
$(document).ready(function(){
if ($( window ).width() < 1160) {
$('#wrap').toggleClass('SActive');	
}	
var sidebarsh = screen.height - 60;	
$('.sidescroll').slimScroll(
{
        height: sidebarsh,
        position: 'left',
        size: 1,
        railOpacity: '0.001',
        color: '#2c343f',
        railColor: '#2c343f',
		wheelStep : 5
    }
);	
// Initialize navgoco with default options
	var navmenu = $('.sidebar-nav > ul').first();
	$(navmenu).navgoco({
		caretHtml: '',
		accordion: true,
		openClass: 'open',
		save: true,
		cookie: {
			name: 'phpvibe-menu',
			expires: 1,
			path: '/'
		},
		slide: {
			duration: 400,
			easing: 'swing'
		},
		// Add Active class to clicked menu item
		onClickAfter: function(e, submenu) {
			e.preventDefault();
			$(navmenu).find('li').removeClass('active');
			var li =  $(this).parent();
			var lis = li.parents('li');
			li.addClass('active');
			lis.addClass('active');
		},
	});	
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

$('.toggle-btn').click(function(){
$('#wrap').toggleClass('SActive');	
});	
	
	$('.check-all-notb').click(function(){
		var parentTable = $(this).parents('form');										   
		var ch = parentTable.find('article input[type=checkbox]');										 
		if($(this).is(':checked')) {
		
			//check all rows in article
			ch.each(function(){ 
				$(this).attr('checked',true);
				$(this).parent().addClass('checked');	//used for the custom checkbox style
				$(this).parents('article').addClass('selected');
			});
						
			//check both article header and footer
			parentTable.find('.check-all-notb').each(function(){ $(this).attr('checked',true); });
		
		} else {
			
			//uncheck all rows in article
			ch.each(function(){ 
				$(this).attr('checked',false); 
				$(this).parent().removeClass('checked');	//used for the custom checkbox style
				$(this).parents('article').removeClass('selected');
			});	
			
			//uncheck both article header and footer
			parentTable.find('.check-all-notb').each(function(){ $(this).attr('checked',false); });
		}
	});
	
$("#easyhome ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings'; 
			
			$.post("sort.php", order, function(theResponse){
				$("#respo").html(theResponse).fadeIn(400).delay(800).slideUp(300);
			}); 															 
		}								  
		});	
	
});

