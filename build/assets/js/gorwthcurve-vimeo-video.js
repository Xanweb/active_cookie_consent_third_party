import ThirdParty from './third-party'

export default class GorwthcurveVimeoVideo extends ThirdParty {

    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-growthcurve_vimeo_video`
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
