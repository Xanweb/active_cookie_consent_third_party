!function(global, $) {
    'use strict';
    var CookieConsent = global.XDP.CookieConsent;

    function XWACCYoutube($element, options) {
        var my = this;
        my.$element = $element;
        my.$iframe = my.$element.find('iframe');
        my.src = my.$iframe.data('src');
        my.alt = my.$iframe.data('alt') || 'Accept Third Party';
        my.activate = my.$iframe.data('activate') || 0;
        my.buttonTemplate = '<button class="btn btn-info center-block display-cookies-disclaimer-popup">' + my.alt + '</button>';
        my.init();
    }

    XWACCYoutube.prototype = {
        init: function () {
            var my = this;
            if (my.isAccepted()) {
                my.showVideo();
            }else{
                my.blockVideo();
            }
        },
        isAccepted: function () {
            if (this.activate) {
                return true;
            }
            return CookieConsent.get('thirdParty') === 1
        },
        showVideo: function () {
            var my = this;
            my.$iframe.prop('src', my.src);
        },
        blockVideo: function () {
            var my = this;
            my.$element.append(my.buttonTemplate);
        }
    };

    $.fn.xwACCYoutube = function(options) {
        return $.each($(this), function(i, obj) {
            new DataProtectionYoutube($(this), options);
        });
    };

    // Setup AutoStart
    $(function () {
        var youtubePlayer = $('div.youtubeBlock');
        if (youtubePlayer.length > 0) {
            youtubePlayer.xwACCYoutube();
        }
    });
}(window, jQuery);