jQuery(function($) {

  var currentRequests = new Array();

  /** Flag/Unflag ***************************************************************/
  $(document).on('click', 'a.bpm-report-link',function(ev) {
    var link = $(this);
    var inner = link.children('.bpm-inner-text');

    var href = link.attr('href');
    var data = href.replace(/[^?]*\?(.*)/, '$1&action=bpmodfrontend');

    if (currentRequests[data]) {
      return false;
    } else {
        currentRequests[data] = true;
    }

    $.post(ajaxurl, data,
      function(response) {

        link.fadeOut(100, function() {
          switch (response.type) {
            case 'success':
            case 'fade warning':
              link.toggleClass('bpm-unflagged');
              link.toggleClass('bpm-flagged');

              if (link.hasClass('bpm-unflagged'))
                href = href.replace(/(.*)bpmod-action=[^&]*(.*)/, '$1bpmod-action=flag$2');
              else if (link.hasClass('bpm-flagged'))
                href = href.replace(/(.*)bpmod-action=[^&]*(.*)/, '$1bpmod-action=unflag$2');

              href = href.replace(/(.*)_wpnonce=[^&]*(.*)/, '$1_wpnonce=' + response.new_nonce + '$2');

              link.attr('href', href);

              if (!link.hasClass('bpm-no-text'))
                inner.html(response.msg);

              if ('fade warning' == response.type) {
                $was_no_text = link.hasClass('bpm-no-text');
                window.setTimeout(function() {
                  link.fadeOut(100, function() {
                    if ($was_no_text) {
                      inner.html('');
                      link.addClass('bpm-no-text');
                    } else
                      inner.html(response.msg);
                    link.fadeIn(100);
                  });
                }, 2500);
                inner.html(response.fade_msg);
              }
              break;

            case 'error':
            default:
              inner.html(response.msg);
              link.removeClass('bpm-no-text');

          }

          inner.removeClass('ajax-loader');
          $(this).fadeIn(100);

          currentRequests[data] = false;
        });

      }, 'json');

      inner.addClass('ajax-loader');

    return false; // stop propagation and prevent default
  });

});


