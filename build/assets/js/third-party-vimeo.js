import ThirdParty from './third-party'

export default class ThirdPartyVimeo extends ThirdParty {

    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-third_party_vimeo`
    }

    display() {
        $(this.wrapperSelector).each(function () {
            const $iframe = $(this).find('iframe')
            $iframe.attr('src', $iframe.attr('data-src'))
        })

        super.display()

        if (typeof $.fn.fitVids !== 'undefined') {
            $(`${this.wrapperSelector} .vvResponsive`).fitVids()
        }
    }
}
