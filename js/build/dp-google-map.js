!function(global, $) {
    'use strict';

    var CookieConsent = global.ACC.CookieConsent;

    function XWACCGoogleMap(element, options) {
        var my = this;
        my.element = element;
        my.$element = $(element);
        my.latitude = my.$element.data('latitude');
        my.longitude = my.$element.data('longitude');
        my.zoom = my.$element.data('zoom');
        my.scrollwheel = my.$element.data('scrollwheel');
        my.draggable = my.$element.data('draggable');
        my.latlng = new google.maps.LatLng(my.latitude, my.longitude);
        my.width = my.$element.data('width');
        my.height = my.$element.data('height');
        my.alt = my.$element.data('alt') || '';
        my.buttonText = my.$element.data('buttonText') || 'Accept Third Party';
        my.mapOptions = {
            zoom: my.zoom,
            center: my.latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
            scrollwheel: my.scrollwheel,
            draggable: my.draggable,
            mapTypeControl: false
        };
        my.active = my.$element.data('active') || false;
        my.blockGoogleMapTemplate = '<div class="block-google-map-template" style="width: ' + my.width + ';height: '+ my.height +';"><p>'+ my.alt +'</p><button class="btn btn-info center-block display-cookies-disclaimer-popup">'+ my.buttonText +'</button></div>';

        my.init();
    }

    XWACCGoogleMap.prototype = {
        init: function () {
            var my = this;
            if (my.isAccepted()) {
                my.showGoogleMap();
            } else {
                my.blockGoogleMap();
            }
        },
        isAccepted: function () {
            if (this.active) {
                return true;
            }

            return CookieConsent.get('thirdParty') === 1
        },
        showGoogleMap: function () {
            var my = this;
            try {
                var map = new google.maps.Map(my.element, my.mapOptions);
                new google.maps.Marker({
                    position: my.latlng,
                    map: map
                });
            } catch (e) {
                my.$element.replaceWith($('<p />').text(e.message));
            }
        },
        blockGoogleMap: function () {
            var my = this;
            my.$element.replaceWith(my.blockGoogleMapTemplate);
        }
    };

    $.fn.xwACCGoogleMap = function (options) {
        return $.each($(this), function (i, obj) {
            new XWACCGoogleMap(this, options);
        });
    };

    // Setup AutoStart
    $(function () {
        var gmapCanvas = $('div.googleMapCanvas');
        if (gmapCanvas.length > 0) {
            gmapCanvas.xwACCGoogleMap();
        }
    });

}(window, jQuery);
