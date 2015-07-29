// Changes the src-code value in a hardcoded block based on the src parameter passed in the URL.
(function ($) {
  Drupal.behaviors.sample_custom = {
    attach: function (context, settings) {
      ...

      if (settings.sample_custom != undefined && settings.sample_custom.src_code != undefined && $('.block.get-started-block').length > 0) {
        var src_code = settings.sample_custom.src_code;
        var button_href = $('.block.get-started-block .field-item a.button').attr('href');
        if (button_href.indexOf('&') > -1) {
          button_href = button_href.replace(/src=.+?&/, 'src='+src_code+'&');
        } else {
          button_href = button_href.replace(/src=.+/, 'src='+src_code);
        }
        $('.block.get-started-block .field-item a.button').attr('href', button_href);
      }
    }
  };
})(jQuery);
