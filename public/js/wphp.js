/**
 * UI/UX Javascript for the Women's Print History Project
 */

"use strict";

import Modals from '../yarn/dhilux/js/modals.bundle.js';
import A11YTables from '../yarn/dhilux/js/A11Y_tables.js';
import Accordion from '../yarn/dhilux/js/accordion.js';
import * as L from '../yarn/leaflet/dist/leaflet-src.esm.js';


const myParser = new DOMParser();
let citationType;


class WPHPModals extends Modals{
    constructor(selector){
        super(selector);
    }

    citationType(link){
        return link.href.split('/').reverse()[0];

    }

    modalURI(link){
        return link.href
    }
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

    getDialogFromText(text){
        let DOM = myParser.parseFromString(text, 'text/html');
        let dialog = this.renderDialog(DOM);
        return dialog;
    }
    defaultLinkFilter = function(link){
        return (!(/csv$/gi.test(link)));
    }

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
        </section>
    </dialog>`;
      this.tmpDialog.innerHTML = content;
      return this.tmpDialog;
    }
}


(function(){

    const utils = {
        capitalize: function(str){
            return str.slice(1, 1).toUpperCase() + str.slice(2);
        }
    }

    let cards = document.querySelectorAll('.card');
    let paras = document.querySelectorAll('p');

    cards.forEach(removeExcerpt);
    paras.forEach(para => {
        if (/[\w]/g.test(para.innerText)){
            return;
        }
        if (para.innerText.trim() === ''){
            para.parentElement.removeChild(para);
        }
    });

    breakLinksAtSlash();
    makeModals();
    const accessibleTables = new A11YTables;
    accessibleTables.init();
    const accordions = [...document.querySelectorAll('details')].map(detail => new Accordion(detail));

    function makeModals(){
        let btnSelector = 'a[href*="/export/"]';
        let btns = document.querySelectorAll(btnSelector);
        if (btns){
            console.log('BUTTONS!!!');
            let exportModals = new WPHPModals(btnSelector);
            exportModals.debug = true;
            exportModals.init();
        }
    }

    function removeExcerpt(card){
        // Get only the first img.
        const replaceWithPlaceholder = (e) => {
            e.target.src = "/images/placeholder.jpg";
            e.target.classList.add('placeholder');
            e.target.removeEventListener('error', replaceWithPlaceholder);
        }
        let contentDiv = card.querySelector('.card_top');
        let excerpt = contentDiv.querySelector('.excerpt');
        let excerptImg = excerpt.querySelector('img');
        if (!excerptImg){
            excerptImg = document.createElement('img');
        }

        excerptImg.addEventListener('error', replaceWithPlaceholder);
        contentDiv.replaceChild(excerptImg, excerpt);
    }


    function cleanStyles(el){
        let style = el.style;
        if (el.tagName === "IMG"){
            ['width', 'height'].forEach(att => {
                if (el.hasAttribute(att)) el.removeAttribute(att);
                if (style[att]) {
                    style.setProperty(`max-${att}`, style[att]);
                    style.removeProperty(att);
                }
            });
            return
        }
        for (const key of Object.keys(style)){
            if (['text-align'].includes(key)){
                return;
            }
            style.removeProperty(key);
        }
        return el;
    }

    /**
     * Adds a zero-width space at all '/' in links to make them break
     * at smaller widths
     */
    function breakLinksAtSlash() {
        const replaceText = (el) => {
            if (!/\//.test(el.innerText)) {
                return;
            }
            for (let child of el.childNodes) {
                switch (child.nodeType) {
                    case 1:
                        replaceText(child);
                        break;
                    case 3:
                        child.data = child.data.replace(/\//g, '/\u200B');
                        break;
                    default:
                        break;
                }
            }
        }
        try{
            console.log('REPLACING!?!?')
            replaceText(document.querySelector('main'));
            return true;
        } catch(e) {
            return false;
            console.log(`${e}`);
        }
    }
    const mapDiv = document.querySelector('#map');

    if (mapDiv){
        let map = L.map(mapDiv, {
            center: [parseFloat(mapDiv.dataset.latitude), parseFloat(mapDiv.dataset.longitude)],
            zoom: 12,
            minZoom: 5
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }


})();



