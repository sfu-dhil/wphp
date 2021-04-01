/**
 * UI/UX Javascript for the Women's Print History Project
 */

"use strict";

import "./modules/utils"
import "./modules/tables";
import "./modules/modals";
import "./modules/map";
import "./modules/accordion";


/*
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
        if (para.innerText.trim() === '' && !para.querySelector('img')){
            para.parentElement.removeChild(para);
        }
    });

    let excerptImg = document.querySelector('.excerpt.hidden img');
    console.log(excerptImg);
    if (excerptImg){
        document.documentElement.style.setProperty('--background-image',
            `url(${excerptImg.src})`);
        document.querySelector('.page-header').classList.add('hasHero');
    }
    breakLinksAtSlash();




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
        contentDiv.replaceChild(cleanStyles(excerptImg), excerpt);
    }


    function cleanStyles(el){
        let style = el.style;
        if (el.tagName === "IMG"){
            ['width', 'height'].forEach(att => {
                if (el.hasAttribute(att)) el.removeAttribute(att);
                if (style[att]) {
                    if (!el.closest('.card')){
                        style.setProperty(`max-${att}`, style[att]);
                    }
                    style.removeProperty(att);
                }
            });
            return el
        }
        for (const key of Object.keys(style)){
            if (['text-align'].includes(key)){
                return;
            }
            style.removeProperty(key);
        }
        return el;
    } */

    /**
     * Adds a zero-width space at all '/' in links to make them break
     * at smaller widths
     */
 /*   function breakLinksAtSlash() {
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
                        child.data = child.data.replace(/([\/\?#=])/g, '\u200B$1');
                        break;
                    default:
                        break;
                }
            }
        }
        try{
            replaceText(document.querySelector('main'));
            return true;
        } catch(e) {
            return false;
            console.log(`${e}`);
        }
    }


})(); */



