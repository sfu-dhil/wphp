"use strict";



    function removeExcerpts() {
        let cards = document.querySelectorAll('.card');
        cards.forEach(removeExcerpt);
    }

    function cleanUpParas() {
        let paras = document.querySelectorAll('p');
        paras.forEach(para => {
            if (/[\w]/g.test(para.innerText)){
                return;
            }
            if (para.innerText.trim() === '' && !para.querySelector('img')){
                para.parentElement.removeChild(para);
            }
        });
    }

    function localizeLinks() {
        let links = [...document.querySelectorAll('.blog-content a, .homepage-info-block a')];
        links.forEach(link => {
            // Get the href attribute NOT the href prop
            let rawHref = link.getAttribute('href');
            if (/(womensprinthistoryproject\.com|dhil.lib.sfu.ca\/wphp)/gi.test(rawHref)) {
                link.setAttribute('href', new URL(rawHref).pathname);
            }
        });
    }



    function removeExcerpt(card){
        // Get only the first img.
        const replaceWithPlaceholder = (e) => {
            e.target.src = "/images/placeholder.jpg";
            e.target.classList.add('placeholder');
            e.target.removeEventListener('error', replaceWithPlaceholder);
        }
        if (card.querySelector('.card_top .excerpt img')){
            let contentDiv = card.querySelector('.card_top');
            let excerpt = contentDiv.querySelector('.excerpt');
            let excerptImg = excerpt.querySelector('img');
            if (!excerptImg){
                excerptImg = document.createElement('img');
            }

            excerptImg.addEventListener('error', replaceWithPlaceholder);
            contentDiv.replaceChild(cleanStyles(excerptImg), excerpt);
        }

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
    }

    /**
     * Adds a zero-width space at all '/' in links to make them break
     * at smaller widths
     */
    export function breakLinksAtSlash(root = document.querySelector('main')) {
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
        try {
            replaceText(root);
            return true;
        } catch (e) {
            return false;
            console.log(`${e}`);
        }
    }


(function(){
    localizeLinks();
    cleanUpParas();
    removeExcerpts();
    breakLinksAtSlash();
    let blogContent = document.querySelector('.blog-content');
    if (blogContent){
        blogContent.querySelectorAll('img').forEach(cleanStyles);
    }

})();