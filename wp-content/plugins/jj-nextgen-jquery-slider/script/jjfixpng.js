/* Author: JJ Coder, wpjjcoder@gmail.com */
var JJFixPng = {
  
  backgroundIE6: function(selector) {
    if(jQuery.browser.msie && jQuery.browser.version.substr(0,1) < 7) {
      var obj = null;
      jQuery(selector).each(function(index) {
        obj = jQuery(this);
        var bg = jQuery.trim(obj.css("background-image"));
        if(bg != 'none' && bg != '') {
          bg = bg.replace("url(", '');
          bg = bg.replace(")", '');
          bg = bg.replace("'", '');
          bg = bg.replace("'", '');
          bg = bg.replace('"', '');
          bg = bg.replace('"', '');
          var ext = bg.substr(bg.lastIndexOf('.'));
          if(ext == '.png') {
            obj.css("background-image", "none");        
            obj.css( 'filter', 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' + bg + '",sizingMethod="scale")');
          }
        }
      });
    }
  }
  
}