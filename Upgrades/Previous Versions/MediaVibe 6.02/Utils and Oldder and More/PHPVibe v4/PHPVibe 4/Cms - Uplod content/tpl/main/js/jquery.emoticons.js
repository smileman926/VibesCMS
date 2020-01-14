(function($){
  $.fn.emotions = function(options){ /**We have named our plugin 'emotions'**/

    var defaults = {
      enabled: true,
      speed: 100,
	  urlimg : site_url + "tpl/main/images/emoticons/",
      emo: new Array("angel","colonthree","confused","cry","devil","frown","gasp","glasses","grin","grumpy","heart","kiki","kiss","pacman","smile","squint","sunglasses","tongue","wink"),
      symbols: new Array("o:)",":3","o.O",":'(","3:)",":(",":O","8)",":D",">:(",":h","^_^",":*",":v",":)","-_-","8|",":p",";)")
    };
    var options = $.extend(defaults, options);

    var t = {};

    var $this = $(this);
    if(options.enabled){
      return $this.each(function(i,obj) {
        var x = $(obj);
        
        // Entites Encode 
        var encoded = [];
        for(j=0; j<options.symbols.length; j++){
          encoded[j] = String(options.symbols[j]).replace(/&/g, '&amp;').replace(/</g, '<').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
        for(i=0; i<options.symbols.length; i++){
          var htm = x.html();
          if (htm.indexOf(options.symbols[i]) !== -1 || htm.indexOf(encoded[i]) !== -1) {
            var elem = options.emo[i];
            var a = options.symbols[i];
            var b = encoded[i];
            var c = $("<img src='" + options.urlimg +elem+ ".gif' class='"+elem+"' />");
            var myHtm = c.clone().wrap('<p>').parent().html();
            htm = replaceAll(a, myHtm, htm);
            htm = replaceAll(b, myHtm, htm);

            x.html(htm);
            
            var k=0;
            $('.'+elem).each(function(){
              var new_id = elem+'_'+k;
              $(this).attr('id', new_id);

            
              
              k++;
            });
          }
        }
      });
    }

    
    function replaceAll(find, replace, str) {
      find = escapeRegExp(find);
      var regex = new RegExp(find, 'gi');
      return str.replace(regex, replace);
    }
    function escapeRegExp(str) {
      return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }

  };
})(jQuery);