import ThirdParty from './third-party'
import Config from "./config";

export default class ExpressForm extends ThirdParty {
    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-express_form`
    }

    display() {
        $('script[data-src*="www.google.com/recaptcha/api.js"]').each(function() {
            $(this).attr('src', $(this).attr('data-src'))
        })

        $('script[data-src*="js/captcha/recaptchav3.js"]').each(function() {
            $(this).attr('src', $(this).attr('data-src'))
        })

        $(this.wrapperSelector)
            .find('div.captcha')
            .removeClass('acc-opt-out')
            .addClass('acc-opt-in')
            .find(`.${ExpressForm.overlayClass}`).remove()
        $(this.wrapperSelector)
            .find('.submit-form-recaptcha')
            .removeAttr('disabled')
    }

    block() {
        $(this.wrapperSelector)
            .find('div.captcha')
            .append(ExpressForm.getOverlayHTML())
            .addClass('acc-opt-out')
            .removeClass('acc-opt-in')
        $(this.wrapperSelector)
            .find('button[type=submit]')
            .addClass('submit-form-recaptcha')
            .attr('disabled', 'disabled')
    }

    static get overlayClass() {
        return 'acc-block-message'
    }

    static getOverlayHTML() {
        return `<div class="${ExpressForm.overlayClass}"><div class="popup-message alert alert-warning">${ExpressForm.getPopupMessageText()} ${Config.getAcceptButtonHTML()}</div></div>`
    }

    static getPopupMessageText() {
        return $('<div/>').html(Config._get()._data.i18n.third_party.popup_msg).text()
    }
}