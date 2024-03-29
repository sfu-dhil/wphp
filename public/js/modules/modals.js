/**
 * @author Joey Takeda
 *
 */

"use strict";

import Modals from '../../node_modules/dhilux/js/modals.bundle.js';
const myParser = new DOMParser();
let citationType;

/**
 * @class WPHPModals (<string>)
 * @extends Modals
 * @inheritDoc
 */
class WPHPModals extends Modals{
    constructor(selector){
        super(selector);
    }

    /**
     * Get the type of citation (MLA, APA) from the URI
     * @param link
     * @returns {string}
     */
    citationType(link){
        let uri = new URL(link.href);
        return uri.pathname.split('/').reverse()[0];
    }

    /**
     * Gets the modal's URI from the link
     * @param link
     * @returns {*}
     */
    modalURI(link){
        return link.href
    }

    /**
     * Async retrieval of the dialog, which sets the entry
     * in the index
     * @param link
     * @returns {Promise<HTMLDialogElement>}
     */
    async getDialog(link){
        if (this.index.has(this.modalURI(link))) {
            return new Promise((resolve, reject) => {
                return resolve(this.index.get(this.modalURI(link)));
            })
        }
        this.tmpDialog = document.createElement('dialog');
        document.body.appendChild(this.tmpDialog);
        citationType = this.citationType(link);
        this.tmpDialog.setAttribute('open','');
        this.tmpDialog.innerHTML = `<div class="loader-ctr"><div class="loader"></div></div>`;
        return super.getDialog(link);
    }

    /**
     * Parses the dialog from the template text and parses
     * @param text
     * @returns {HTMLDialogElement}
     */
    getDialogFromText(text){
        let DOM = myParser.parseFromString(text, 'text/html');
        let dialog = this.renderDialog(DOM);
        return dialog;
    }

    /**
     * Link filter so not to try and put the CSV into
     * a modal
     * @param link
     * @returns {boolean}
     */
    defaultLinkFilter(link){
        let url = new URL(link.href);
        return (!(/csv$/gi.test(url.pathname)));
    }

    /**
     * Custom dialog content for WPHP
     * @param dom {HTMLElement}
     * @returns {HTMLDialogElement}
     */
    renderDialog(dom){
        let content = `
        <header>
            <div class="dialog-content">
                <div class="dialog-heading">
                    <div class="dialog-label">${citationType}</div>
                    <h3>Titles</h3>
                </div>
                <div class="dialog-closer">
                    <form method="dialog">
                        <button class="btn">
                            <svg viewBox="0 0 24 24" height="1rem" width="1rem">
                                <line x1="0" x2="24" y1="0" y2="24"/>
                                <line x1="24" x2="0" y1="0" y2="24"/>
                            </svg>
                            <span class="sr-only">Close</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        <section class="dialog-body">
            <div class="dialog-content">
                ${dom.querySelector('.list-group').outerHTML}
            </div>
        </section>`;
        this.tmpDialog.innerHTML = content;
        return this.tmpDialog;
    }
}

/**
 * Exported constant for making the modals
 */
export const makeModals = () => {
    let btnSelector = 'a[href*="/export/"]';
    let btns = document.querySelectorAll(btnSelector);
    if (btns && btns.length > 0){
        let exportModals = new WPHPModals(btnSelector);
        exportModals.debug = false;
        exportModals.init();
    }
}

/**
 * Driver
 */
makeModals();

