import ThirdParty from './third-party'
import Config from "./config";

export default class LegacyForm extends ThirdParty {
    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-form`
    }

    display() {
        $(this.wrapperSelector)
            .find('.submit-form-recaptcha')
            .removeAttr('disabled')
    }

    block() {
        let $captcha = $(this.wrapperSelector).find('div.captcha')
        if ($captcha.length === 0) {
            return;
        }
        $(this.wrapperSelector)
            .find('[type=submit]')
            .addClass('submit-form-recaptcha')
            .attr('disabled', 'disabled')
    }
}