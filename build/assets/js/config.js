const defaults = {
    i18n: {
        third_party: {
            accept_btn: 'Please accept third party cookies',
            popup_msg: ''
        }
    },
    third_party: {
        accept_btn_action: 'show_popup'
    }
}

export default class Config {

    constructor() {
        this._data = $.extend(defaults, window['ACC_CONF'] || {})
    }

    static get overlayClass() {
        return 'acc-block-overlay'
    }

    static getPopupMessageText() {
        return Config._get()._data.i18n.third_party.popup_msg
    }

    static getAcceptButtonText() {
        return Config._get()._data.i18n.third_party.accept_btn
    }

    static getAcceptButtonAction() {
        return Config._get()._data.third_party.accept_btn_action
    }

    static getAcceptButtonHTML() {
        return `<button class="btn btn-info center-block display-cookies-disclaimer-popup" data-accept-function="${Config.getAcceptButtonAction()}">${Config.getAcceptButtonText()}</button>`
    }

    static getOverlayHTML() {
        return `<div class="${Config.overlayClass}"><div class="popup-message">${Config.getPopupMessageText()} ${Config.getAcceptButtonHTML()}</div></div>`
    }

    /**
     * @return {Config}
     * @private
     */
    static _get() {
        return Config._instance || (Config._instance = new Config())
    }
}
