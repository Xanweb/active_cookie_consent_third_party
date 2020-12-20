export default class ThirdParty {

    constructor() {
        const my = this
        if (my.constructor === ThirdParty) {
            throw new TypeError('Cannot instantiate abstract class.')
        }

        my.init()

        if (!ThirdParty.isAccepted()) {
            my.display()
            my.active = false
        } else {
            my.show()
            my.active = true
        }
    }

    init() {

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
        throw new TypeError('Please implement abstract method display().')
    }

    block() {
        throw new TypeError('Please implement abstract method block().')
    }
}
