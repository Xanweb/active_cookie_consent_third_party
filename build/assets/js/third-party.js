import Config from './config'

export default class ThirdParty {

    constructor() {
        const my = this
        if (my.constructor === ThirdParty) {
            throw new TypeError('Cannot instantiate abstract class.')
        }

        my.init()

        if (ThirdParty.isAccepted()) {
            my.display()
            my.active = true
        } else {
            my.block()
            my.active = false
        }
    }

    init() {

    }

    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return '.acc-third-party-wrapper'
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
            this.display()
            this.active = true
        }
    }

    /**
     * Method Required by {ThirdPartyManager} class under ACC
     */
    disable() {
        if (this.active) {
            this.block()
            this.active = false
        }
    }

    static isAccepted() {
        return !window.ACC.UserPrivacy.isOptedOut('thirdParty')
    }

    display() {
        $(this.wrapperSelector)
            .removeClass('acc-opt-out')
            .addClass('acc-opt-in')
            .find(`.${Config.overlayClass}`).remove()
    }

    block() {
        $(this.wrapperSelector)
            .append(Config.getOverlayHTML())
            .addClass('acc-opt-out')
            .removeClass('acc-opt-in')
    }
}
