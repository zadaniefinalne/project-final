var inputfile = "map/cityOfScholar.php";
function vyberSkolaBydlisko() {
    var checkBoxSkola = document.getElementById("skola");
    var checkBoxMesto = document.getElementById("mesto");


    if (checkBoxSkola.checked == true){
        inputfile = "map/cityOfScholar.php";
    }
    if (checkBoxMesto.checked == true){
        inputfile = "map/cityOfUsers.php";
    }

    initMap();
}

function initMap() {
    var slovakia = new google.maps.LatLng(48.5, 19.1);

    var map = new google.maps.Map(document.getElementById('map'), {
        center: slovakia,
        zoom: 7
    });

    downloadUrl(inputfile, function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function(markerElem) {
            var id = markerElem.getAttribute('id');
            //console.log(id);
            var geocoder = new google.maps.Geocoder();
            var address = markerElem.getAttribute('city');
            //console.log(address);
            var count = markerElem.getAttribute('count');
            var info_window = new google.maps.InfoWindow({
                content: "Počet uživ. " + count
            });


            Geocode(address);
            function createMarker(latlng, html)
            {
                var marker = new google.maps.Marker
                ({
                    map: map,
                    position: latlng

                });
                google.maps.event.addListener(marker, 'click', function() {
                    //info_window.setContent(count);
                    info_window.open(map, marker);
                });
            }

            function Geocode(address) {

                geocoder.geocode
                ({'address': address},
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            createMarker(results[0].geometry.location, "TEST");
                        } else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                            setTimeout(function () {
                                Geocode(address);
                            }, 1);

                        } else {
                            alert("Geocode was not successful for the following reason:"
                                + status);
                        }
                    });
            }

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = name;
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = address;
            infowincontent.appendChild(text);

        });
    });
}


function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open('GET', url, true);
    request.send(null);
}

function doNothing() {}