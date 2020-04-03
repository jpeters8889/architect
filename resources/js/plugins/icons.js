import {library, dom} from '@fortawesome/fontawesome-svg-core'
import {faCogs} from "@fortawesome/free-solid-svg-icons/faCogs";
import {faBuilding} from "@fortawesome/free-regular-svg-icons/faBuilding";
import {faBars} from "@fortawesome/free-solid-svg-icons/faBars";
import {faCircle} from "@fortawesome/free-solid-svg-icons/faCircle";
import {faEdit} from "@fortawesome/free-solid-svg-icons/faEdit";
import {faTimes} from "@fortawesome/free-solid-svg-icons/faTimes";
import {faPlus} from "@fortawesome/free-solid-svg-icons/faPlus";
import {faCircleNotch} from "@fortawesome/free-solid-svg-icons/faCircleNotch";
import {faFacebookSquare} from "@fortawesome/free-brands-svg-icons/faFacebookSquare";
import {faCrop} from "@fortawesome/free-solid-svg-icons/faCrop";
import {faHome} from "@fortawesome/free-solid-svg-icons/faHome";
import {faPaste} from "@fortawesome/free-solid-svg-icons/faPaste";
import {faCaretUp} from "@fortawesome/free-solid-svg-icons/faCaretUp";
import {faCaretDown} from "@fortawesome/free-solid-svg-icons/faCaretDown";

export default () => {
    // Generic
    library.add(faBars);

    // Fields
    library.add(faEdit);
    library.add(faTimes);
    library.add(faPlus);
    library.add(faFacebookSquare);
    library.add(faCrop);
    library.add(faHome);
    library.add(faPaste);
    library.add(faCircleNotch);
    library.add(faCaretUp);
    library.add(faCaretDown);

    // Navigation
    library.add(faBuilding);
    library.add(faCogs);

    // Misc
    library.add(faCircle);

    dom.watch();
}