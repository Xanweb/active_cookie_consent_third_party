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
        $('script[data-src*="maps.googleapis.com/maps/api/js"]').each(function() {
            $(this).attr('src', $(this).attr('data-src'))
        })

        super.display()
    }
}
