class GorwthcurveVimeoVideo {
    constructor($element, options) {
        const my = this
        my.$element = $element
        my.$iframe = my.$element.find('iframe')
        my.src = my.$iframe.data('src')
        my.buttonText = my.$element.data('buttonText') || 'Please accept third party cookies'

        my.buttonTemplate = '<button class="btn btn-info center-block display-cookies-disclaimer-popup">' + my.buttonText + '</button>'
        if (!my.isAccepted()) {
            my.blockVideo()
            my.active = false
        } else {
            my.showVideo()
            my.active = true
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
            this.showVideo()
            this.active = true
        }
    }

    /**
     * Method Required by {ThirdPartyManager} class under ACC
     */
    disable() {
        if (this.active) {
            this.blockVideo()
            this.active = false
        }
    }

    isAccepted() {
        return !window.ACC.UserPrivacy.isOptedOut('thirdParty')
    }

    showVideo() {
        this.$iframe.prop('src', this.src)
        this.$element.find('button.display-cookies-disclaimer-popup').remove()
        this.$element.find('.vvResponsive').fitVids()
    }

    blockVideo() {
        this.$element.append(this.buttonTemplate)
    }
}

$.fn.xwACCVimeo = function(options) {
    return $.each($(this), function(i, obj) {
        new GorwthcurveVimeoVideo($(this), options)
    })
}

// Setup AutoStart
$(function () {
    const vimeo = $('div.vimeoVidWrap')
    if (vimeo.length > 0) {
        vimeo.xwACCVimeo()
    }
})
