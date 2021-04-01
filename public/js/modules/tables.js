import A11YTables from '../../yarn/dhilux/js/A11Y_tables.js';
import Swup from "../../yarn/swup/dist/swup.min.js";
import SwupPreloadPlugin from "../../yarn/@swup/preload-plugin/dist/SwupPreloadPlugin.min.js";


(function(){
    const accessibleTables = new A11YTables;
    accessibleTables.init();

    const swup = new Swup({
        containers: ['.tbl-container'],
        linkSelector : '.tbl-container .pagination a[href].page-link',
        plugins: [new SwupPreloadPlugin()]
    });

    swup.on('contentReplaced', function(){
        let tables = new A11YTables;
        tables.init();
        makeModals();
        breakLinksAtSlash();
    });
}());


