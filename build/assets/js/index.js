import GoogleMap from './google-map'
import Vimeo from './gorwthcurve-vimeo-video'
import Youtube from './youtube'
import ExpressForm from './express-form'
import LegacyForm from './legacy-form'
import ThirdPartyMap from "./third-party-map";
import ThirdPartyRecaptcha from "./third-party-recaptcha";
import ThirdPartyVimeo from "./third-party-vimeo";
import ThirdPartyYoutube from "./third-party-youtube";

$(function () {
    window.ACC.registerThirdParty(new GoogleMap())
    window.ACC.registerThirdParty(new Vimeo())
    window.ACC.registerThirdParty(new Youtube())
    window.ACC.registerThirdParty(new ExpressForm())
    window.ACC.registerThirdParty(new LegacyForm())
    window.ACC.registerThirdParty(new ThirdPartyMap())
    window.ACC.registerThirdParty(new ThirdPartyRecaptcha())
    window.ACC.registerThirdParty(new ThirdPartyVimeo())
    window.ACC.registerThirdParty(new ThirdPartyYoutube())

    document.dispatchEvent(new Event(`ACC.ThirdParty.loaded`))
    window.ACC.third_party_loaded = true
})
