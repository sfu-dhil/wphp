function buildEvents(books, birth, death, birthPlace, deathPlace) {
    var events = [];
    books.forEach(function (b) {
        var caption;
        if (death.year && b.datePublished > death.year) {
            caption = "Published posthumously";
        } else if (birth.year) {
            caption = "Age: " + (b.datePublished - birth.year);
        } else {
            caption = "Age at published year unknown";
        }

        events.push(
            {
                start_date: {
                    year: b.datePublished,
                }
                ,
                group: b.genre[0],
                text:
                    {
                        headline: `<span data-genre="${b.genre[0]}">${b.name}</span>`,
                         text: caption
                    }
                ,
            });
    });

    events.push(
        {
            start_date: birth,
            text: {
                headline: "Birth",
                text: "<p>Location:" + birthPlace.name + "</p>"

            },
        }
    )
    events.push(
        {
            start_date: death,
            text: {
                headline: "Death",
                text: "<p>Location:" + deathPlace.name + "</p>"
            }
        }
    )

    return events;
}

function getName(givenName, familyName) {
    var a = "";
    if (givenName && familyName) {
        a = givenName + " " + familyName;
    } else {
        if (givenName) {
            a = givenName;
        } else if (familyName) {
            a = familyName;
        }
    }
    return a;
}

function parseDate(date) {
    // create a js object initially set to empty and then filled with values based on input
    var data = {
        day: "",
        month: "",
        year: ""
    }
    if (!date) {
        return data;
    }
    var a = date.split('-')
    data = {
        day: a[2] ? a[2] : '',
        month: a[1] ? a[1] : '',
        year: a[0] ? a[0] : '',
    }
    return data;
}

function getDates(birth, death) {
    if (birth.year && death.year) {
        return "(" + birth.year + "-" + death.year + ")";
    }
    if (birth.year) {
        return "[" + birth.year + "-]";
    }
    if (death.year) {
        return "[-" + death.year + "]";
    }
    return "";
}

// Only instantiate the timeline if the div exists.
if (document.querySelector('#timeline-div')){
    $.getJSON(url, function (data) {
        var numOfBooks = data['@graph'].length;
        if (numOfBooks === 0) {
            document.getElementById("timeline-div").remove();
        } else {
            var birth = parseDate(data.birthDate);
            var death = parseDate(data.deathDate);
            var myObject = {
                title: {
                    text: {
                        headline: getName(data.givenName, data.familyName) + " " + getDates(birth, death),
                        text: "Author of " + numOfBooks + " titles",
                    }
                },
                events: buildEvents(data['@graph'], birth, death, data.birthPlace, data.deathPlace),
            }
            // instantiate timeline
            window.timeline = new TL.Timeline('timeline-div', myObject)
            console.log(myObject)
        }
    });
}
