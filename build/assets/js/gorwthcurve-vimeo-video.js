import Config from './config'
import ThirdParty from './third-party'

const vimeoSelector = 'div.vimeoVidWrap'

export default class GorwthcurveVimeoVideo extends ThirdParty {

    display() {
        $(vimeoSelector).each(function () {
            const $self = $(this)
            const $iframe = $self.find('iframe')
            $iframe.attr('src', $iframe.attr('data-src'))

            $self.find(`.${Config.overlayClass}`).remove()
            if (typeof $.fn.fitVids !== 'undefined') {
                $self.find('.vvResponsive').fitVids()
            }
        })
    }

    block() {
        $(vimeoSelector).append(Config.getOverlayHTML())
    }
}
