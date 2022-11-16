import GoogleMap from './google-map'
import Vimeo from './gorwthcurve-vimeo-video'
import Youtube from './youtube'
import ExpressForm from './express-form'

$(function () {
    window.ACC.registerThirdParty(new GoogleMap())
    window.ACC.registerThirdParty(new Vimeo())
    window.ACC.registerThirdParty(new Youtube())
    window.ACC.registerThirdParty(new ExpressForm())
})
