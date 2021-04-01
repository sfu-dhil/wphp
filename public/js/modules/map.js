import * as L from '../../yarn/leaflet/dist/leaflet-src.esm.js';

(function(){
    const mapDiv = document.querySelector('#map');

    if (mapDiv){
        let map = L.map(mapDiv, {
            center: [parseFloat(mapDiv.dataset.latitude), parseFloat(mapDiv.dataset.longitude)],
            zoom: 12,
            minZoom: 5
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }
}());



