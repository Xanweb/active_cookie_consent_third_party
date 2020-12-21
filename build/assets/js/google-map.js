import ThirdParty from './third-party'

export default class GoogleMap extends ThirdParty {

    /**
     * @return {string}
     * @protected
     */
    get wrapperSelector() {
        return `${super.wrapperSelector}.acc-google_map`
    }

    display() {
        const $gmapScriptTag = $(document.head).find('script[data-src*="maps.googleapis.com/maps/api/js"]')
        $gmapScriptTag.attr('src', $gmapScriptTag.attr('data-src'))

        super.display()
    }
}
