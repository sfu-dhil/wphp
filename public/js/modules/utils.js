/**
 * @description Module for cleaning up various UI components in WPHP,
 * mostly those that are caused by editor-created markup
 * @author Joey Takeda
 *
 */

"use strict";

/**
 * Remove all excerpts from cards to create
 * grid display
 */
function removeExcerpts() {
    let cards = document.querySelectorAll('.card');
    cards.forEach(removeExcerpt);
}

/**
 * Remove empty paragraphs
 */
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

/**
 * Convert absolute links to relative links
 * in blog content
 */
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


/**
 * Transform the excerpt to be a simple image, if there is one;
 * otherwise, just remove the excerpt entirely.
 * @param card
 */
function removeExcerpt(card){
    /**
     * Function to handle error conditions for images,
     * which removes itself after loading the new image.
     * @param e
     */
    const replaceWithPlaceholder = (e) => {
        e.target.src = "/images/placeholder.jpg";
        e.target.classList.add('placeholder');
        e.target.removeEventListener('error', replaceWithPlaceholder);
    }
    // Not querySelectorAll since we only want to get the first img
    if (card.querySelector('.card_top .excerpt img')){
        let contentDiv = card.querySelector('.card_top');
        let excerpt = contentDiv.querySelector('.excerpt');
        let excerptImg = excerpt.querySelector('img');
        if (!excerptImg){
            excerptImg = document.createElement('img');
        }
        excerptImg.setAttribute('loading','lazy');
        excerptImg.addEventListener('error', replaceWithPlaceholder);
        contentDiv.replaceChild(cleanStyles(excerptImg), excerpt);
    }

}

/**
 * Cleans editor constructed text to remove unnecessary
 * or problematic styles
 * @param el
 * @returns {*}
 */
function cleanStyles(el){
    let style = el.style;
    // Retain width/height for images, but make them
    // maximum values rather than set
    if (el.tagName === "IMG"){
        ['width', 'height'].forEach(att => {
            // Sometimes images have width/height as a style property
            // or hardcoded as attributes: we have to handle both
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
    // Remove most styles from elements
    for (const key of Object.keys(style)){
        if (['text-align'].includes(key)){
            return;
        }
        style.removeProperty(key);
    }
    return el;
}

/**
 * Recursively adds a <wbr> element at all '/' in links to ensure links
 * break properly. This has to be done iteratively in order to
 * ensure all content continues to be placed properly back into
 * the document without the addition of extra content.
 *
 * @param root {HTMLElement} The root element to walk
 *
 */
export function breakLinksAtSlash(root = document.querySelector('main')) {

    /**
     * Adds a <wbr> element in every instance of a slash
     * Inspired by (and departs from):
     * https://css-tricks.com/better-line-breaks-for-long-urls/
     *
     * @param node {TextNode}
     * @returns {any}
     */
    const handleSlashes = (node) => {
        let text = node.data;
        let el = document.createElement('span');
        if (text.indexOf('/') == -1){
            el.innerHTML = text;
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
                el.insertAdjacentText('beforeend', bit.replace('/\n+/gi',' '));
            } else {
                // If there are slash tokens, then add a wbr before each one
                slashBits.forEach((sb, sbidx) => {
                    if (sbidx > 0){
                        el.insertAdjacentHTML('beforeend', `<wbr>/`);
                    }
                    // And add the text back in
                    el.insertAdjacentText('beforeend', sb.replace('/\n+/gi',' '));
                });
            }
        });
        return el;
    };

    /**
     * Function called on each element that tests each child
     * to see if there's anything to break on
     * @param el
     */
    const replaceText = (el) => {
        // If there's no slash at all in the content or we're trying
        // to process a form, then bail
        if (el.textContent.indexOf('/') == -1 || el.tagName == 'FORM') {
            return;
        }
        // Step through the children
        for (let child of el.childNodes) {
            switch (child.nodeType) {
                case Node.ELEMENT_NODE:
                    replaceText(child);
                    break;
                case Node.TEXT_NODE:
                    // Only process if there's meaningful content
                    if (child.data.trim().length > 0){
                        // Create a fragment from handleSlashes
                        // and replace the content with it
                        let frag = handleSlashes(child);
                        el.replaceChild(frag, child);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    // Try to replace the text
    try {
        replaceText(root);
        return true;
    } catch (e) {
        console.log(`${e}`);
        return false;
    }
}

/**
 * Function that attempts to add the author metadata to the header
 * of the page; it won't work all the time, but it gives it a bit of a
 * better shot of getting it right
 */
function addAuthor(){
    const content = document.querySelector('.blog-content');
    const children = content.children;
    const authorRex = /\s*Authored\s*by:\s*/gi;
    const titleMeta = document.querySelector('meta[name="citation_title"]');
    let bylineEl = [...children].find(el => {
        return  authorRex.test(el.innerText);
    });
    if (!bylineEl){
        return;
    }
    let byline = bylineEl.innerText.trim();
    let bylineNorm = byline.replace(authorRex,'').replace(' and ',',');
    let names = bylineNorm.split(/,+\s*/gi);
    names.forEach(name => {
        let parts = name.split(/\s+/);
        let nameArr = [parts.pop(), parts.join(' ')];
        if (nameArr.length > 0){
            let authorMeta = `<meta name="citation_author" content="${nameArr.join(', ')}"/>`;
            titleMeta.insertAdjacentHTML('beforebegin', authorMeta);
        }
    });
}

/**
 * Driver
 */
(function(){
    /* Only run the utilities for standard,
    * public pages, not editor pages */
    if (/\/(edit|new|sort|copy)(\/|$)/gi.test(window.location.pathname)){
        return;
    }
    removeExcerpts();
    localizeLinks();
    cleanUpParas();
    breakLinksAtSlash();
    let blogContent = document.querySelector('.blog-content');
    if (blogContent){
        blogContent.querySelectorAll('img').forEach(cleanStyles);
        addAuthor();
    }
})();