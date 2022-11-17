const defaults = {
    i18n: {
        third_party: {
            accept_btn: 'Please accept third party cookies',
            show_popup_btn: 'More information',
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
        return $('<div/>').html(Config._get()._data.i18n.third_party.popup_msg).text()
    }

    static getAcceptButtonText() {
        return Config._get()._data.i18n.third_party.accept_btn
    }

    static getShowPopupButtonText() {
        return Config._get()._data.i18n.third_party.show_popup_btn
    }

    static getAcceptButtonAction() {
        return Config._get()._data.third_party.accept_btn_action
    }

    static getAcceptButtonHTML() {
        let buttonsHtml = ''
        let acceptButtonAction = Config.getAcceptButtonAction()
        let advancedClasses = ''
        if (acceptButtonAction === 'both_action') {
            advancedClasses = 'w-100 mb-2'
        }
        if (acceptButtonAction === 'show_popup' || acceptButtonAction === 'both_action') {
            buttonsHtml = buttonsHtml.concat(Config.getThirdPartyButtonHTML('show_popup', Config.getShowPopupButtonText(), advancedClasses))
        }
        if (acceptButtonAction === 'accept_third_party' || acceptButtonAction === 'both_action') {
            buttonsHtml = buttonsHtml.concat(Config.getThirdPartyButtonHTML('accept_third_party', Config.getAcceptButtonText(), advancedClasses))
        }
        return buttonsHtml
    }

    static getThirdPartyButtonHTML(action = 'show_popup', message = '', advancedClasses = '') {
        return `<button type="button" class="btn btn-info center-block display-cookies-disclaimer-popup ${advancedClasses}" data-accept-function="${action}">${message}</button>`
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
