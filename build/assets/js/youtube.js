class Youtube {
    constructor($element, options) {
        const my = this
        my.$element = $element
        my.$iframe = my.$element.find('iframe')
        my.src = my.$iframe.data('src')
        my.alt = my.$iframe.data('alt') || 'Accept Third Party'
        my.active = my.$iframe.data('activate') || 0
        my.buttonTemplate = '<button class="btn btn-info center-block display-cookies-disclaimer-popup">' + my.alt + '</button>'
        if (!my.active) {
            my.blockVideo()
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
        this.$iframe.prop('src', this.src);
    }

    blockVideo() {
        this.$element.append(this.buttonTemplate);
    }
}

$.fn.xwACCYoutube = function(options) {
    return $.each($(this), function(i, obj) {
        new Youtube($(this), options)
    })
}

// Setup AutoStart
$(function () {
    const youtubePlayer = $('div.youtubeBlock');
    if (youtubePlayer.length > 0) {
        youtubePlayer.xwACCYoutube()
    }
})
