$(function(){ // document ready
 
  if (!!$('#sidebar').offset()) { // make sure "#sidebar" element exists
 
    var stickyTop = $('#sidebar').offset().top; // returns number 
 
    $(window).scroll(function(){ // scroll event
 
      var windowTop = $(window).scrollTop(); // returns number 
 
      if (stickyTop < windowTop){
        $('#sidebar').css({ position: 'fixed', top: 0 });
      }
      else {
        $('#sidebar').css('position','static');
      }
 
    });
 
  }
 
});