
/*function visioCarteAjoutTicket() {
    document.getElementById("map").style.display = "block";

    var adresseDepart = document.getElementsByName("adresseDepartTicket")[0].value;

    var url = "https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(adresseDepart);

    var map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            var locationD = data[0];

            map.setView([locationD.lat, locationD.lon], 10);

            L.marker([locationD.lat, locationD.lon]).addTo(map)
                .bindPopup(adresseDepart)
                .openPopup();
        })
        .catch(error => console.error('Erreur : ', error));

    var adresseArrivee = document.getElementsByName("adresseArriveeTicket")[0].value;

    url = "https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(adresseArrivee);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            var locationA = data[0];

            L.marker([locationA.lat, locationA.lon]).addTo(map)
                .bindPopup(adresseArrivee)
                .openPopup();
        })
        .catch(error => console.error('Erreur : ', error));

    L.Routing.control({
        waypoints: [
            L.latLng(locationD.lat, locationD.lon),
            L.latLng(locationA.lat, locationA.lon)
        ],
        routeWhileDragging: true
    }).addTo(map);

}*/

function visioCarteAjoutTicket(){
    document.getElementById("map").style.display = "block";

    var adresseDepart = document.querySelector("input[name='adresseDepartTicket']").value;
    var adresseArrivee = document.querySelector("input[name='adresseArriveeTicket']").value;

    var urlDepart = "https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(adresseDepart);
    var urlArrivee = "https://nominatim.openstreetmap.org/search?format=json&q=" + encodeURIComponent(adresseArrivee);

    var map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    function geocode(url){
        return fetch(url)
            .then(response => response.json())
            .then(data => {
                return data[0];
            });
    }

    Promise.all([geocode(urlDepart), geocode(urlArrivee)])
        .then(([locationD, locationA]) => {
            map.setView([locationD.lat, locationD.lon], 10);

            L.marker([locationD.lat, locationD.lon]).addTo(map)
                .bindPopup(adresseDepart)
                .openPopup();
            
            L.marker([locationA.lat, locationA.lon]).addTo(map)
                .bindPopup(adresseArrivee)
                .openPopup();

            L.Routing.control({
                waypoints: [
                    L.latLng(locationD.lat, locationD.lon),
                    L.latLng(locationA.lat, locationA.lon)
                ],
                routeWhileDragging: true
            }).addTo(map);
        }).catch(error => console.error('Erreur : ', error));

}