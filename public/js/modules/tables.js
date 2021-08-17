import A11YTables from '../../yarn/dhilux/js/a11Y_tables.js';
import Swup from "../../yarn/swup/dist/swup.min.js";
import SwupPreloadPlugin from "../../yarn/@swup/preload-plugin/dist/SwupPreloadPlugin.min.js";
import StickyElements from "../../yarn/dhilux/js/sticky_elements.js";
import { makeModals } from "./modals.js";
import { breakLinksAtSlash } from "./utils.js";

const tableSizes = new Map();
window.tableSizes = tableSizes;
function makeTables(){
    const measureTable = (tbl) => {
        const checkResize = (records, observer) => {
            console.log('Resized!');
            for (const record of records){
                let { target } = record;
                target.style.maxHeight = target.dataset.maxHeight;
            }
            observer.disconnect();
        }
        let overflow = tbl.closest('.tbl-overflow');
        if (overflow){
            let overflowHeight = overflow.offsetHeight;
            let tblHeight = tbl.offsetHeight;
            if (tblHeight <= overflowHeight){
                overflow.style.resize = 'none';
                return;
            }
            if (overflow.style.height){
                overflow.style.maxHeight = tblHeight + 3 + "px";
            } else {
                overflow.dataset.maxHeight = tblHeight + "px";
                tbl.classList.add('resizable');
                new MutationObserver(checkResize).observe(overflow, {
                    attributes: true,
                    attributesFilter: ['style']
                });
            }
        }
    }

    let accessibleTables = new A11YTables;
    accessibleTables.init();
    accessibleTables.tables.map(t => t.table).forEach(tbl => {
        measureTable(tbl);
    });
    return accessibleTables;
}



function initSwup(){
    const swup = new Swup({
        containers: ['.tbl-container'],
        linkSelector : '.tbl-container .pagination a[href].page-link',
        plugins: [new SwupPreloadPlugin()]
    });
    const swupContainers = function(){
        return swup.options.containers.reduce( (ctrs, q) => {
            return ctrs.concat([...document.querySelectorAll(q)]);
        }, []);
    }
    swup.on('willReplaceContent', function(){
        swupContainers().forEach(ctr => {
            let overflow = ctr.querySelector('.tbl-overflow');
            if (overflow.style.height){
                tableSizes.set(ctr.dataset.swup, overflow.style);
            }
        })
    });
    swup.on('pageView', function(){
        swupContainers().forEach(ctr => {
            if (tableSizes.has(ctr.dataset.swup)){
                let overflow = ctr.querySelector('.tbl-overflow');
                let cached = tableSizes.get(ctr.dataset.swup);
                overflow.style.height = cached.height;
            }
            breakLinksAtSlash(ctr);
        });
        makeTables();
        makeModals();
    });
}


(function(){
    initSwup();
    makeTables();
    new StickyElements('.table > thead > tr');
}());