/**
 * UI/UX Javascript for the Women's Print History Project
 */

"use strict";


(function(){
    const cards = document.querySelectorAll('.card');
    cards.forEach(removeExcerpt);

    function cleanImg(img){
        ['width', 'height', 'style'].forEach(att => {
           if (img.hasAttribute(att)) img.removeAttribute(att);
        });
        return img;
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
        contentDiv.replaceChild(cleanImg(excerptImg), excerpt);
    }

})();





