import ThirdParty from './third-party'
import Config from "./config";

export default class ThirdPartyRecaptcha extends ThirdParty {
    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-third_party_recaptcha`
    }

    display() {
        $('script[data-src*="www.google.com/recaptcha/api.js"]').each(function() {
            $(this).attr('src', $(this).attr('data-src'))
        })

        $(this.wrapperSelector)
            .removeClass('acc-opt-out')
            .addClass('acc-opt-in')
            .find(`.${ThirdPartyRecaptcha.overlayClass}`).remove()
    }


    block() {
        $(this.wrapperSelector)
            .append(ThirdPartyRecaptcha.getOverlayHTML())
            .addClass('acc-opt-out')
            .removeClass('acc-opt-in')
    }

    static get overlayClass() {
        return 'acc-block-message'
    }

    static getOverlayHTML() {
        return `<div class="${ThirdPartyRecaptcha.overlayClass}"><div class="popup-message alert alert-warning">${ThirdPartyRecaptcha.getPopupMessageText()} ${Config.getAcceptButtonHTML()}</div></div>`
    }

    static getPopupMessageText() {
        return $('<div/>').html(Config._get()._data.i18n.third_party.popup_captcha_msg || '').text()
    }
}