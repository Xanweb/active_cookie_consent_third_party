class GoogleMap {
    constructor(element) {
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
        my.buttonText = my.$iframe.data('buttonText') || 'Please accept third party cookies'
        my.buttonFunction = my.$iframe.data('acceptFunction') || 'show_popup'
        my.popupMessage = my.$iframe.data('popupMessage')
        my.buttonTemplate = '<button class="btn btn-info center-block display-cookies-disclaimer-popup" data-accept-function="' + my.buttonFunction + '">' + my.buttonText + '</button>'

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
        my.blockGoogleMapTemplate = '<div class="block-google-map-template" style="width: ' + my.width + ';height: ' + my.height + ';"><div class="block-googlemap-overlay"><div class="popup-message">' + my.popupMessage + my.buttonTemplate + '</div></div></div>'
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

window.concreteGoogleMapInit = () => {
    $('.googleMapCanvas').each(function () {
        new GoogleMap(this)
    })
}
