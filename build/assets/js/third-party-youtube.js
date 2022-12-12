import ThirdParty from './third-party'

export default class ThirdPartyYoutube extends ThirdParty {

    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-third_party_youtube`
    }

    display() {
        $(this.wrapperSelector).each(function () {
            const $iframe = $(this).find('iframe')
            $iframe.attr('src', $iframe.attr('data-src'))
        })

        super.display()
    }
}
