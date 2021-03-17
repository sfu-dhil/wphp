/**
 * UI/UX Javascript for the Women's Print History Project
 */

"use strict";


(function(){

    const utils = {
        capitalize: function(str){
            return str.slice(1, 1).toUpperCase() + str.slice(2);
        }
    }

   // document.querySelectorAll('img:not([style]), *[style]').forEach(cleanStyles);
    document.querySelectorAll('.card').forEach(removeExcerpt);
    document.querySelectorAll('p').forEach(para => {
        if (/[\w]/g.test(para.innerText)){
            return;
        }
        if (para.innerText.trim() === ''){
            para.parentElement.removeChild(para);
        }
    });
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
    function breakLinksAtSlash(){
        const replaceText = (el) => {
            if (!/\//.test(el.innerText)){
                return;
            }
            for (let child of el.childNodes){
                switch(child.nodeType){
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


        // Okay so I guess I should walk the DOM...
        let els = document.querySelectorAll('body > div.container > *:not(.page-header)');

        els.forEach(replaceText);

        /*

        document.querySelectorAll('a[href]').forEach(link => {
            if (!(/\//g.test(link.innerText))){
             return;
            }
            link.innerText = link.innerText.replace(/\//g,'/\u200B');
        });

         */

    }

})();





