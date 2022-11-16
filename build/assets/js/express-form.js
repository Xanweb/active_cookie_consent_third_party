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
        $('script[data-lead=googlerecaptcha]').each(function() {
            $(this).attr('type', 'text/javascript')
        })

        $(this.wrapperSelector)
            .find('div.captcha')
            .removeClass('acc-opt-out')
            .addClass('acc-opt-in')
            .find(`.${Config.overlayClass}`).remove()
    }

    block() {
        $(this.wrapperSelector)
            .find('div.captcha')
            .append(Config.getOverlayHTML())
            .addClass('acc-opt-out')
            .removeClass('acc-opt-in')
    }
}