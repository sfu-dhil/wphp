#!/bin/sh

function recrud() {
    echo $1
    lc=$(echo $1 |  tr '[:upper:]' '[:lower:]')
    ./bin/console doctrine:generate:crud \
                  --quiet \
                  --no-interaction \
                  --entity=AppBundle:$1 \
                  --route-prefix=$2 \
                  --with-write \
                  --format=annotation \
                  --overwrite
    
    mkdir -p src/AppBundle/Resources/views/$1/
    mv app/Resources/views/$lc/* src/AppBundle/Resources/views/$1    
	rmdir app/Resources/views/$lc
};

recrud Firm firm
recrud Firmrole firmrole
recrud Format format
recrud Genre genre
recrud Geonames geonames
recrud Person person
recrud Relation relation
recrud Role role
recrud Source source
recrud Title title
recrud TitleFirmrole titlefirmrole
recrud TitleRole titlerole

