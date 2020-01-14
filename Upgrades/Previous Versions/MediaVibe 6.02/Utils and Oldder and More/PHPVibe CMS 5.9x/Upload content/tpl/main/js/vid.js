if (typeof jQuery == 'undefined') {  
var jq = document.createElement('script'); jq.type = 'text/javascript';
  jq.src = '//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js';
  document.getElementsByTagName('head')[0].appendChild(jq);
}
$(document).on('ready', function(){
var ew = $(".vibe_embed").width();
var eh = Math.round((ew/16)*9) + 35;
$(".vibe_embed").height(eh);   
});