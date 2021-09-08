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
        let frags = [];
        const handleSlashes = (node) => {
            let text = node.data;
            let el = document.createElement('span');
            if (text.indexOf('/') == -1){
                el.innerText = text;
                return el;
            }
            let doubleSlashTokens = text.split('//');
            // Split the tokens on "//" since those shouldn't be replaced
            doubleSlashTokens.forEach((bit, idx) => {
                let slashBits = bit.split('/');
                if (idx > 0){
                    // Mimic join
                    el.insertAdjacentText('beforeend', '//');
                }
                // If there are no slash tokens, then just append the bit
                if (slashBits.length == 1){
                    el.insertAdjacentText('beforeend', bit);
                } else {
                    // If there are slash tokens, then add a wbr before each one
                    slashBits.forEach((sb, sbidx) => {
                        if (sbidx > 0){
                            el.insertAdjacentHTML('beforeend', `<wbr>/`);
                        }
                        // And add the text back in
                        el.insertAdjacentText('beforeend', sb);
                    });
                }
            });
            return el;
        };
        const replaceText = (el) => {
            if (el.textContent.indexOf('/') == -1) {
                return;
            }
            for (let child of el.childNodes) {
                switch (child.nodeType) {
                    case 1:
                        replaceText(child);
                        break;
                    case 3:
                        if (child.data.trim().length > 0){
                            let frag = handleSlashes(child);
                            el.replaceChild(frag, child);
                        }
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
            console.log(`${e}`);
            return false;

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