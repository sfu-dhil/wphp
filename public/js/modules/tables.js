/**
 * @description Module for creating accessible tables and adding
 * functionality to the tables for better UI.
 * @author Joey Takeda
 */

"use strict";

import A11YTables from '../../yarn/dhilux/js/a11Y_tables.js';
import Swup from "../../yarn/swup/dist/swup.min.js";
import SwupPreloadPlugin from "../../yarn/@swup/preload-plugin/dist/SwupPreloadPlugin.min.js";
import StickyElements from "../../yarn/dhilux/js/sticky_elements.js";

// Import utilities to process all incoming content
import { makeModals } from "./modals.js";
import { breakLinksAtSlash } from "./utils.js";

const tableSizes = new Map();

/**
 * Function to create the accessible and scolle-able tables
 * @returns {A11YTables}
 */
function makeTables(){

    /**
     * Measures and sets up the table resize function
     * so that the table cannot be resized past its maximum
     * height
     * @param tbl
     */
    const measureTable = (tbl) => {

        /**
         * Callback function for the table's mutation
         * observer
         * @param records
         * @param observer
         */
        const checkResize = (records, observer) => {
            for (const record of records){
                let { target } = record;
                target.style.maxHeight = target.dataset.maxHeight;
            }
            observer.disconnect();
        }
        // Only make scrollable tables for data tables
        let overflow = tbl.closest('.tbl-overflow');
        if (overflow){
            // Determine the difference between the
            // height of the table's container against
            // the height of the table
            let overflowHeight = overflow.offsetHeight;
            let tblHeight = tbl.offsetHeight;
            // Remove resize if the table is shorter than container
            if (tblHeight <= overflowHeight){
                overflow.style.resize = 'none';
                return;
            }
            // If the table overflows, but there's already a height
            // the overflow container  (i.e. from an earlier resizing
            // but has been replaced), then set the overflow's
            // max-height to the table height plus a few pixels
            if (overflow.style.height){
                overflow.style.maxHeight = tblHeight + 3 + "px";
            } else {
                // Otherwise, set the max-height for the table
                overflow.dataset.maxHeight = tblHeight + "px";
                // Allow the table to be resizable
                tbl.classList.add('resizable');
                // And watch the table to make sure the table never overflows
                new MutationObserver(checkResize).observe(overflow, {
                    attributes: true,
                    attributesFilter: ['style']
                });
            }
        }
    }
    // Create the accessible tables from DHILUX
    let accessibleTables = new A11YTables;
    accessibleTables.init();
    // And now use those tables to create resizable ones
    accessibleTables.tables.map(t => t.table).forEach(tbl => {
        measureTable(tbl);
    });
    return accessibleTables;
}


/**
 * Initializes SWUP, which allows the table content to be swapped
 * out when paginated rather than triggered a full page refresh
 */
function initSwup(){
    const swup = new Swup({
        containers: ['.tbl-container'],
        linkSelector : '.tbl-container .pagination a[href].page-link',
        plugins: [new SwupPreloadPlugin()]
    });

    /**
     * Allow access to all of the swup containers easily
     * @returns {[HTMLElement*]}
     */
    const swupContainers = function(){
        return swup.options.containers.reduce( (ctrs, q) => {
            return ctrs.concat([...document.querySelectorAll(q)]);
        }, []);
    }

    // Right before the content is replaced, set the last
    // height so the table's height remains the same
    swup.on('willReplaceContent', function(){
        swupContainers().forEach(ctr => {
            let overflow = ctr.querySelector('.tbl-overflow');
            if (overflow.style.height){
                tableSizes.set(ctr.dataset.swup, overflow.style);
            }
        })
    });

    // Once the table is replaced, resize the table to be
    // the same height as the old one and then process the
    // incoming content
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
    if (document.querySelectorAll('.tbl-container').length > 0){
        initSwup();
        makeTables();
        // Add sticky events for the theads on tables
        new StickyElements('.table > thead > tr');
    }
}());