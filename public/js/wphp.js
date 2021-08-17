/**
 * UI/UX Javascript for the Women's Print History Project
 */

"use strict";

const fetchPersonData = async () => {
    let extract, image = '';
    if (window.location.href.indexOf('/person/') == -1){
        return false;
    }
    let wikiLink = document.querySelector('a.wikipedia_url');
    let imageLink = document.querySelector('a.image_url');
    if (!wikiLink && !imageLink){
        return false;
    }
    if (wikiLink){
        extract = await fetchExtract(wikiLink);
    }
    if (imageLink){
        try{
            image = await fetchImage(imageLink);
        } catch(e){
            console.log(e);
        }
    }
    if (extract || image){
        let grid = document.createElement('div');
        grid.classList.add('wiki_api');
        grid.insertAdjacentHTML('beforeend', extract);
        grid.appendChild(document.querySelector('main.container > .tbl-container'));
        grid.insertAdjacentHTML('beforeend', image);

        window.wiki_image = image;
        window.wiki_extract = extract;
        return document.querySelector('.page-header').insertAdjacentElement('afterend', grid);
    } else {
        return false;
    }
}

(function(){

// Let's just try something for the people
    fetchPersonData().then((result) => {
        console.log(`Did it work? ${result}`);
    })
}());




async function fetchImage(imageLink){
    if (!imageLink){
        return;
    }

    let imageUrl = new URL(imageLink.href);
    let src = imageUrl.href;
    if (imageUrl.hostname.indexOf('en.wikipedia.org') > 0){
        // DO STUFF TO MAKE THE IMAGE WORK
    }
    return new Promise((resolve, reject) => {
        let img = new Image();
        img.src = src;
        img.onload = () => {
            let imgDiv = `<div class="wiki_image">
                            <img src="${src}"
                                 alt="${document.querySelector('h1').innerText}"/>
                           </div>`;
            resolve(imgDiv);
        }
        img.onerror = () => reject(new Error('Could not load img'));
    });
}

async function fetchExtract(link){
    if (!link){
        return;
    }
    let wikiUrl = new URL(link.href);
    let titles = wikiUrl.pathname.split('/').pop();
    let params = {
        action: 'query',
        prop: 'extracts',
        exintro: true,
        format: 'json',
        origin: '*',
        exsentences: 5,
        titles,
    }
    let apiUrl = new URL(`${wikiUrl.origin}/w/api.php`);
    apiUrl.search = new URLSearchParams(params).toString();
    let response = await fetch(apiUrl);
    let json = await response.json();
    let extract = Object.values(json.query.pages)[0].extract;
    window.wiki_json = json;
    let out = `<div class="wiki_extract">
                <div class="wiki_attribution">
                    <a href="${link.href}"  target="_blank" rel="nofollow">
                        <img src="/images/wikipedia.svg" alt="Read More"/>
                   </a>
                 </div>
                <div class="extract">${extract}</div>

                </div>`;
    return out;
}


/* Basic utilities */
import "./modules/utils";

/* Modals for citation popups */
import "./modules/modals";

/* Table handling (accessibility, etc) */
import "./modules/tables";

/* Accordion for detail elements */
import "./modules/accordion";

