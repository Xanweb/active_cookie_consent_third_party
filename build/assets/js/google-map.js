import Config from './config'
import ThirdParty from './third-party'

const gmapSelector = '.googleMapCanvas'

export default class GoogleMap extends ThirdParty {

    display() {
        const $gmapScriptTag = $(document.head).find('script[data-src*="maps.googleapis.com/maps/api/js"]')
        $gmapScriptTag.attr('src', $gmapScriptTag.attr('data-src'))

        $(gmapSelector).each(function () {
            $(this).find('div.block-google-map-template').fadeOut(300, function() {
                $(this).remove()
            })
        })
    }

    block() {
        $(gmapSelector).each(function () {
            const $self = $(this)
            $self.append(`<div class="block-google-map-template" style="width: ${$self.data('width')};height: ${$self.data('height')};">${Config.getOverlayHTML()}</div>`)
        })
    }
}
