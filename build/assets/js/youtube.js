import Config from './config'
import ThirdParty from './third-party'

const youtubeSelector = 'div.youtubeBlock'

export default class Youtube extends ThirdParty {

    display() {
        $(youtubeSelector).each(function () {
            const $self = $(this)
            const $iframe = $self.find('iframe')
            $iframe.attr('src', $iframe.attr('data-src'))

            $self.find(`.${Config.overlayClass}`).remove()
        })
    }

    block() {
        $(youtubeSelector).append(Config.getOverlayHTML())
    }
}
