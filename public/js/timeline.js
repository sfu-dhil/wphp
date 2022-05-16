/**
 * Creates and renders a TimelineJS (https://timeline.knightlab.com/) widget
 * using the JSON-LD output for a person in WPHP
 *
 * @author Joey Takeda (SFU DHIL)
 * @author Saba Akhyani (SFU DHIL)
 *
 */

/**
 * Main class for the timeline
 */
class PersonTimeline{
    constructor(){
        this.init();
    }

    /**
     * Gets the timeline container
     * @returns {Element}
     */
    get container(){
        return document.querySelector('#timeline-container');
    }

    /**
     * Gets the timeline div
     * @returns {Element}
     */
    get div(){
        return document.querySelector('#timeline');
    }

    /**
     * Gets the URL for the JSON-LD output from the
     * document's metadata
     * @returns {String}
     */
    get url(){
        return document.querySelectorAll("link[rel='alternate'][type='application/ld+json']")[0]?.href;
    }

    /**
     * Gets the last stylesheet for the page for easier
     * access
     * @returns {StyleSheet}
     */
    get stylesheet(){
        return document.styleSheets[document.styleSheets.length - 1];
    }

    /**
     * Gets the number of books
     * @returns {number}
     */
    get numOfBooks(){
        return this.data['@graph'].length;
    }

    /**
     * Gets the Wikipedia URL (person.wikipediaUrl) for a person, if available
     * @returns {String | null}
     */
    get wikiUrl(){
        let link = document.querySelector('.wikipediaUrl');
        if (!link){
            return null;
        }
        return link.href;
    }

    /**
     * Gets the associated image URL (person.wikipediaUrl) for a person, if available
     * @returns {String | null}
     */
    get imageUrl(){
        let link = document.querySelector('.imageUrl');
        if (!link){
            return null;
        }
        let href = link.href;
        if (/\/wiki\/File:/gi.test(href)){
            href = href.replace('File:', 'Special:FilePath/File:')
        }
        return href;
    }

    /**
     * Gets the books published
     * @returns {Array}
     */
    get books(){
        return this.data['@graph'];
    }

    /**
     * Gets the parsed birth date
     * @returns {Object}
     */
    get birth(){
        return this.parseDate(this.data['birthDate']);
    }
    /**
     * Gets the parsed death date
     * @returns {Object}
     */
    get death(){
        return this.parseDate(this.data['deathDate']);
    }

    /**
     * Gets the place of birth
     * @returns {Object}
     */
    get birthPlace(){
        return this.data.birthPlace;
    }

    /**
     * Gets the place of death
     * @returns {Object}
     */
    get deathPlace(){
        return this.data.deathPlace;
    }

    /**
     * Returns the given name of a person
     * @returns {String}
     */
    get givenName(){
        return this.data.givenName;
    }

    /**
     * Returns the family name of a person
     * @returns {String}
     */
    get familyName(){
        return this.data.familyName;
    }


    /**
     * Gets the constructed name of the person
     * @returns {string}
     */
    get name(){
        return [this.givenName, this.familyName].join(' ');
    }



    /**
     * Formatted birth and death date string for a person
     * @returns {string}
     */
    get bioDates() {
        let byear = this.birth?.year;
        let dyear = this.death?.year;
        if (!byear && !dyear){
            return '';
        }
        return `(${[byear, dyear].join('–')})`;
    }

    /**
     * Initialization: fetch the JSON, read it, and then build
     * the timeline from it
     */
    init() {
        let _ = this;
        fetch(this.url)
            .then(response => response.json())
            .then(json => {
                _.data = json;
                _.timelineObject = this.buildTimelineObject();
                console.log(_.container.offsetWidth);
                _.timeline = new TL.Timeline(this.div, this.timelineObject, {
                    slide_padding_lr: 75
                });
                _.watchTimeline();
            }).catch(e => {
            _.container.classList.add('loaded');
            _.div.innerHTML = `<p>Timeline unavailable</p>`;
            console.error(e);
        });
    }

    getSlidePadding(){
        return 50;

    }
    /**
     * Observes the Timeline so we can add styling, handling, etc
     * rather than try and hook into TimelineJS's processing (which
     * is mostly not event based)
     *
     */
    watchTimeline() {
        let self = this;
        let handledImgs = [];
        // set up the mutation observer
        let observer = new MutationObserver(function (mutations, me) {
            let imgs = self.div.querySelectorAll('img');
            imgs.forEach(img => {
                if (handledImgs.includes(img)){
                    return;
                }
                img.addEventListener('error', e => {
                    let mediaBlock = img.closest('.tl-media');
                    mediaBlock.parentElement.removeChild(mediaBlock);
                    handledImgs.push(img);
                });
            });
        });
        // start observing
        observer.observe(self.div, {
            childList: true,
            subtree: true
        });
        self.container.classList.add('loaded');
    };

    /**
     * Main function for building the necessary Timeline object for TimelineJS
     * @returns {Object | null} Returns the object if it exists, but null otherwise
     */
    buildTimelineObject(){
        // If there are no books, then just remove the timeline completely
        if (this.numOfBooks === 0) {
            this.div.parentElement.removeChild(this.div);
            return null;
        }
        let title = this.buildTitle();
        let events = this.buildEvents();
        return {
            title,
            events
        }
    }

    /**
     * Returns the title slide for the Timeline
     * @returns {Object}
     */
    buildTitle(){
        let title = {
            text: {
                headline: `${this.name} ${this.bioDates}`,
                text: `Author of ${this.numOfBooks} titles`
            }
        }
        if (this.wikiUrl || this.imageUrl){
            title.media = {
                url: this.imageUrl,
                credit: `<a href="${this.imageUrl}">${this.imageUrl}</a>`,
                alt: `Image of ${this.name}`
            }
            if (this.wikiUrl){
                title.media.link = this.wikiUrl;
                title.media.link_target = '_blank';
            }
        }
        return title;
    }

    /**
     * Constructs the sequence of "events" for the Timeline
     * @returns {[]}
     */
    buildEvents() {
        let events = [];

        // Construct events for birth and death, if available
        if (this.birth){
            let birth_event = {
                start_date: this.birth,
                text: {
                    headline: "Birth"
                }
            }
            if (this.birthPlace && this.birthPlace.name){
                birth_event.text.text = `<p>Location: ${this.birthPlace.name}</p>`;
            }
            events.push(birth_event);
        }
        if (this.death){
            let death_event = {
                start_date: this.death,
                text: {
                    headline: "Death"
                }
            }
            if (this.deathPlace && this.deathPlace.name){
                death_event.text.text = `<p>Location: ${this.deathPlace.name}</p>`;
            }
            events.push(death_event);
        }
        // Now construct the events for the books
        for (const b of this.books){
            // Bail on books that don't have a publication date
            if (!b['datePublished']){
                continue;
            }
            let book_event = this.buildBookEvent(b);
            if (book_event){
                events.push(book_event);
            }
        }

        return events;
    }

    /**
     * Constructs a single event for a given book
     * @param b The book object
     * @returns {Object} Object representing the publication event slide
     */
    buildBookEvent(b){
        let link = `<a href="${b['@id']}">View title record</a>`;
        // Publication dates can be ranges so we have to split it
        let datePublished = parseInt(b['datePublished'].split('-')[0]);
        let genre = b['genre'][0];
        let ageCaption;
        if (this.death && datePublished > this.death.year) {
            ageCaption = `<p>Published posthumously</p>`;
        }
        // It only make sense to output age if we know both birth
        // and death (otherwise, someone could be aged 1400 or something)
        else if (this.birth && this.birth.year && this.death){
            ageCaption = `<p>Age: ${datePublished - this.birth.year}</p>`;
        } else {
            // Do nothing
        }
        return {
                start_date: {
                    year: datePublished
                },
                group: genre,
                text: {
                        headline: `<span data-genre="${genre}">${b.name}</span>`,
                        text: [ageCaption, link].join('<br/>')
                    }
            }
    }

    /**
     * Converts a ISO-8601 "date" into a structured object for ease of processing
     * @param date
     * @returns {{month: (*|string), year: (*|string), day: (*|string)}|null}
     */
    parseDate(date) {
        // create a js object initially set to empty and then filled with values based on input
        let data = {
            day: "",
            month: "",
            year: ""
        }
        // Bail if there isn't a date or the date contains letters (to handle BCE etc)
        if (!date || /[a-zA-Z]/gi.test(date)) {
            return null;
        }
        let a = date.split('-')
        data = {
            day: a[2] ? a[2] : '',
            month: a[1] ? a[1] : '',
            year: a[0] ? a[0] : '',
        }
        return data;
    }
}


let widget = document.querySelector('#timeline-container > details');

// if the widget is already open, then construct the person timeline
if (widget.open){
    timeline = new PersonTimeline();
} else {
    // Otherwise, wait for the widget to open,
    // and then construct it (but only once)
    widget.addEventListener('toggle', e=>{
        if (timeline){
            return;
        }
        if (widget.open){
            timeline = new PersonTimeline();
        }
    });
}





