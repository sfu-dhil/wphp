/**
 * Test module for citations
 */

"use strict";

let csl = {};

(function(){
    // First fetch the show page
    // show-page
    let ldLink = document.querySelector('link[type="application/ld+json"]');
    if (ldLink){
        let resource = ldLink.href;
        console.log(resource);
        fetch(resource)
            .then(response => response.json())
            .then(json => {
                console.log(json);
                // Now construct the CSL:
                csl.type = 'book';
                csl.id = json["@id"];
                csl.title = json.name;
                csl.author = json.author.map(author => {
                    let obj = {};
                    obj.family = author.familyName;
                    obj.given = author.givenName
                    return obj;
                });
                csl.issued = json.datePublished.split('-');
                csl["publisher-place"] = json.locationCreated.name;
                csl["container-title"] = `The Women's Print History Project`;
                csl["container-title-short"] = "WPHP";
                csl.accessed = ('2021-08-10').split('-');
                console.log(csl);
            })
    }
}());
