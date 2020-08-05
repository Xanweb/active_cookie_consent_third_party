class GoogleMap {
    constructor(element, options) {
        const my = this
        my.element = element
        my.$element = $(element)
        my.latitude = my.$element.data('latitude')
        my.longitude = my.$element.data('longitude')
        my.zoom = my.$element.data('zoom')
        my.scrollwheel = my.$element.data('scrollwheel')
        my.draggable = my.$element.data('draggable')
        my.latlng = new window.google.maps.LatLng(my.latitude, my.longitude)
        my.width = my.$element.data('width')
        my.height = my.$element.data('height')
        my.alt = my.$element.data('alt') || ''
        my.buttonText = my.$element.data('buttonText') || 'Accept Third Party'
        my.mapOptions = {
            zoom: my.zoom,
            center: my.latlng,
            mapTypeId: window.google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
            scrollwheel: my.scrollwheel,
            draggable: my.draggable,
            mapTypeControl: false
        }

        my.active = my.$element.data('active') || false
        my.blockGoogleMapTemplate = '<div class="block-google-map-template" style="width: ' + my.width + ';height: '+ my.height +';"><p>'+ my.alt +'</p><button class="btn btn-info center-block display-cookies-disclaimer-popup">'+ my.buttonText +'</button></div>'
        if (!my.active) {
            my.blockGoogleMap()
        } else {
            my.showGoogleMap()
        }

        window.ACC.registerThirdParty(this)
    }

    /**
     * Method Required by {ThirdPartyManager} class under ACC
     *
     * @returns {boolean}
     */
    isEnabled() {
        return this.active
    }

    /**
     * Method Required by {ThirdPartyManager} class under ACC
     */
    enable() {
        if (!this.active) {
            this.showGoogleMap()
            this.active = true
        }
    }

    /**
     * Method Required by {ThirdPartyManager} class under ACC
     */
    disable() {
        if (this.active) {
            this.blockGoogleMap()
            this.active = false
        }
    }

    isAccepted() {
        return !window.ACC.UserPrivacy.isOptedOut('thirdParty')
    }

    showGoogleMap() {
        const my = this
        try {
            const map = new window.google.maps.Map(my.element, my.mapOptions)
            new window.google.maps.Marker({
                position: my.latlng,
                map: map
            })
        } catch (e) {
            my.$element.replaceWith($('<p />').text(e.message))
        }
    }

    blockGoogleMap() {
        const my = this
        my.$element.append(my.blockGoogleMapTemplate)
    }
}

$.fn.xwACCGoogleMap = function (options) {
    return $.each($(this), function (i, obj) {
        new GoogleMap(this, options)
    })
}

// Setup AutoStart
$(function () {
    const gmapCanvas = $('div.googleMapCanvas')
    if (gmapCanvas.length > 0) {
        gmapCanvas.xwACCGoogleMap()
    }
})
