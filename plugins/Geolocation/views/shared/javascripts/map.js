function OmekaMap(mapDivId, center, options) {
    this.mapDivId = mapDivId;
    this.center = center;
    this.options = options;
}

OmekaMap.prototype = {

    map: null,
    mc: null,
    oms: null,
    mapDivId: null,
    mapSize: 'small',
    markers: [],
    options: {},
    center: null,
    coordinates_hash : [],

    addMarker: function (lat, lng, options, bindHtml)
    {
        if (!options) {
            options = {};
        }

        if(this.mapDivId == 'map_canvas'){
	        // add this inside your loop - looping over your marker locations
	        var coordinates_str, actual_lat, actual_lon, adjusted_lat, adjusted_lon;

	        actual_lat = lat;
	        actual_lon = lng;
	        coordinates_str = actual_lat + actual_lon;

	        while (this.coordinates_hash[coordinates_str] != null) {
	        	// adjust coord by 50m or so
	        	adjusted_lat = parseFloat(actual_lat) + (Math.random() -.5) / 50;
	        	adjusted_lon = parseFloat(actual_lon) + (Math.random() -.5) / 50;
	        	coordinates_str = String(adjusted_lat) + String(adjusted_lon);
	        }
	        this.coordinates_hash[coordinates_str] = 1;

	        //var myLatLng = new google.maps.LatLng(adjusted_lat, adjusted_lon);


	        options.position = new google.maps.LatLng(adjusted_lat, adjusted_lon);
        }else{
        	options.position = new google.maps.LatLng(lat, lng);
        }

        options.map = this.map;
        
        bindHtml = bindHtml.replace(/(<([^>]+)>)/ig,"");
        bindHtml = bindHtml.replace(/ /g,'');
        //options.title = bindHtml;
        options.snippet = bindHtml;

        var marker = new google.maps.Marker(options);              
        
        //for clusterer
        this.mc.addMarker(marker);
        //spider
        this.oms.addMarker(marker);
        //return marker;
    },

    initMap: function () {

        // Build the map.
        var mapOptions = {
            zoom: this.center.zoomLevel,
            streetViewControl: false,
            center: new google.maps.LatLng(this.center.latitude, this.center.longitude),
            mapTypeId: google.maps.MapTypeId.ROADMAP,            
            navigationControl: true,
            mapTypeControl: true
        };
        switch (this.mapSize) {
        case 'small':
            mapOptions.navigationControlOptions = {
                style: google.maps.NavigationControlStyle.ZOOM_PAN
            };
            break;
        case 'large':
        default:
            mapOptions.navigationControlOptions = {
                style: google.maps.NavigationControlStyle.DEFAULT
            };
        }

        this.map = new google.maps.Map(document.getElementById(this.mapDivId), mapOptions);



        if (!this.center) {
            alert('Error: The center of the map has not been set!');
            return;
        }

        // Show the center marker if we have that enabled.
        if (this.center.show) {
            this.addMarker(this.center.latitude,
                           this.center.longitude,
                           {title: "(" + this.center.latitude + ',' + this.center.longitude + ")"},
                           this.center.markerHtml);
        }
        
        //The markercluster's options
        var mcOptions = {gridSize: 50, maxZoom: 15};
        //Construct an empty markerclusterer object
        this.mc = new MarkerClusterer(this.map, [], mcOptions);
        this.oms = new OverlappingMarkerSpiderfier(this.map);
        
        this.oms.addListener('click', function(marker) {
            //bindHtml = bindHtml.replace(/(<([^>]+)>)/ig,"");
              //  bindHtml = bindHtml.replace(/ /g,'');
                
                var infowindow = null;
                var request = jQuery.ajax(
                    {
                        url: '/items/map/',
                        type: 'POST',                        
                        data: {id:marker.snippet},
                        async: false,                       
                        success: function(data){
                            var result = jQuery(data).find('.mapsInfoWindow').html();
                                infowindow = new google.maps.InfoWindow({
                                content: result
                            });
                            infowindow.open(marker.getMap(), marker);
                        }
                    }
                );
          });
    }
};

function OmekaMapBrowse(mapDivId, center, options) {
    var omekaMap = new OmekaMap(mapDivId, center, options);
    jQuery.extend(true, this, omekaMap);
    var bmap = this.initMap();

    //XML loads asynchronously, so need to call for further config only after it has executed
    this.loadKmlIntoMap(this.options.uri, this.options.params);
}

OmekaMapBrowse.prototype = {
	/* Need to parse KML manually b/c Google Maps API cannot access the KML
       behind the admin interface */
    loadKmlIntoMap: function (kmlUrl, params) {
        var that = this;
        jQuery.ajax({
            type: 'GET',
            dataType: 'xml',
            url: kmlUrl,
            data: params,
            success: function(data) {
                var xml = jQuery(data);

                /* KML can be parsed as:
                    kml - root element
                        Placemark
                            namewithlink
                            description
                            Point - longitude,latitude
                */
                var placeMarks = xml.find('Placemark');

                // If we have some placemarks, load them
                if (placeMarks.size()) {
                    // Retrieve the balloon styling from the KML file
                    that.browseBalloon = that.getBalloonStyling(xml);

                    // Build the markers from the placemarks
                    jQuery.each(placeMarks, function (index, placeMark) {
                        placeMark = jQuery(placeMark);
                        that.buildMarkerFromPlacemark(placeMark);
                    });

                    // We have successfully loaded some map points, so continue setting up the map object
                    return that;
                } else {
                    // @todo Elaborate with an error message
                    return false;
                }


            }
        });


    },

    getBalloonStyling: function (xml) {
        return xml.find('BalloonStyle text').text();
    },

    // Build a marker given the KML XML Placemark data
    // I wish we could use the KML file directly, but it's behind the admin interface so no go
    buildMarkerFromPlacemark: function (placeMark) {
        // Get the info for each location on the map
        var title = placeMark.find('name').text();
        var titleWithLink = placeMark.find('namewithlink').text();
        var body = placeMark.find('description').text();
        var snippet = placeMark.find('Snippet').text();
        var icon = placeMark.find('Icon href').text();

        // Extract the lat/long from the KML-formatted data
        var coordinates = placeMark.find('Point coordinates').text().split(',');
        var longitude = coordinates[0];
        var latitude = coordinates[1];

        // Use the KML formatting (do some string sub magic)
        var balloon = this.browseBalloon;
        balloon = balloon.replace('$[namewithlink]', titleWithLink).replace('$[description]', body).replace('$[Snippet]', snippet);

        // Build a marker, add HTML for it
        this.addMarker(latitude, longitude, {icon:icon, title: title}, balloon);
    },

    // Calculate the zoom level given the 'range' value
    // Not currently used by this class, but possibly useful
    // http://throwless.wordpress.com/2008/02/23/gmap-geocoding-zoom-level-and-accuracy/
    calculateZoom: function (range, width, height) {
        var zoom = 18 - Math.log(3.3 * range / Math.sqrt(width * width + height * height)) / Math.log(2);
        return zoom;
    }
};

function OmekaMapSingle(mapDivId, center, options) {
    var omekaMap = new OmekaMap(mapDivId, center, options);
    jQuery.extend(true, this, omekaMap);
    this.initMap();
}
OmekaMapSingle.prototype = {
    mapSize: 'small'
};

function OmekaMapForm(mapDivId, center, options) {
    var that = this;
    var omekaMap = new OmekaMap(mapDivId, center, options);
    jQuery.extend(true, this, omekaMap);
    this.initMap();

    this.formDiv = jQuery('#' + this.options.form.id);

    // Make the map clickable to add a location point.
    google.maps.event.addListener(this.map, 'click', function (event) {
        // If we are clicking a new spot on the map
        if (!that.options.confirmLocationChange || that.markers.length === 0 || confirm('Are you sure you want to change the location of the item?')) {
            var point = event.latLng;
            var marker = that.setMarker(point);
            jQuery('#geolocation_address').val('');
        }
    });

    // Make the map update on zoom changes.
    google.maps.event.addListener(this.map, 'zoom_changed', function () {
        that.updateZoomForm();
    });

    // Make the Find By Address button lookup the geocode of an address and add a marker.
    jQuery('#geolocation_find_location_by_address').bind('click', function (event) {
        var address = jQuery('#geolocation_address').val();
        that.findAddress(address);

        //Don't submit the form
        event.stopPropagation();
        return false;
    });

    // Make the return key in the geolocation address input box click the button to find the address.
    jQuery('#geolocation_address').bind('keydown', function (event) {
        if (event.which == 13) {
            jQuery('#geolocation_find_location_by_address').click();
            event.stopPropagation();
            return false;
        }
    });

    // Add the existing map point.
    if (this.options.point) {
        this.map.setZoom(this.options.point.zoomLevel);

        var point = new google.maps.LatLng(this.options.point.latitude, this.options.point.longitude);
        var marker = this.setMarker(point);
        this.map.setCenter(marker.getPosition());
    }
}

OmekaMapForm.prototype = {
    mapSize: 'large',

    /* Get the geolocation of the address and add marker. */
    findAddress: function (address) {
        var that = this;
        if (!this.geocoder) {
            this.geocoder = new google.maps.Geocoder();
        }
        this.geocoder.geocode({'address': address}, function (results, status) {
            // If the point was found, then put the marker on that spot
            if (status == google.maps.GeocoderStatus.OK) {
                var point = results[0].geometry.location;

                // If required, ask the user if they want to add a marker to the geolocation point of the address.
                // If so, add the marker, otherwise clear the address.
                if (!that.options.confirmLocationChange || that.markers.length === 0 || confirm('Are you sure you want to change the location of the item?')) {
                    var marker = that.setMarker(point);
                } else {
                    jQuery('#geolocation_address').val('');
                    jQuery('#geolocation_address').focus();
                }
            } else {
                // If no point was found, give us an alert
                alert('Error: "' + address + '" was not found!');
                return null;
            }
        });
    },

    /* Set the marker to the point. */
    setMarker: function (point) {
        var that = this;




        // Get rid of existing markers.
        this.clearForm();

        // Add the marker
        var marker = this.addMarker(point.lat(), point.lng());



        // Pan the map to the marker
        that.map.panTo(point);

        //  Make the marker clear the form if clicked.
        google.maps.event.addListener(marker, 'click', function (event) {
            if (!that.options.confirmLocationChange || confirm('Are you sure you want to remove the location of the item?')) {
                that.clearForm();
            }
        });

        this.updateForm(point);
        return marker;
    },

    /* Update the latitude, longitude, and zoom of the form. */
    updateForm: function (point) {
        var latElement = document.getElementsByName('geolocation[0][latitude]')[0];
        var lngElement = document.getElementsByName('geolocation[0][longitude]')[0];
        var zoomElement = document.getElementsByName('geolocation[0][zoom_level]')[0];

        // If we passed a point, then set the form to that. If there is no point, clear the form
        if (point) {
            latElement.value = point.lat();
            lngElement.value = point.lng();
            zoomElement.value = this.map.getZoom();
        } else {
            latElement.value = '';
            lngElement.value = '';
            zoomElement.value = this.map.getZoom();
        }
    },

    /* Update the zoom input of the form to be the current zoom on the map. */
    updateZoomForm: function () {
        var zoomElement = document.getElementsByName('geolocation[0][zoom_level]')[0];
        zoomElement.value = this.map.getZoom();
    },

    /* Clear the form of all markers. */
    clearForm: function () {
        // Remove the markers from the map
        for (var i = 0; i < this.markers.length; i++) {
            this.markers[i].setMap(null);
        }

        // Clear the markers array
        this.markers = [];

        // Update the form
        this.updateForm();
    },

    /* Resize the map and center it on the first marker. */
    resize: function () {
        google.maps.event.trigger(this.map, 'resize');
        var point;
        if (this.markers.length) {
            var marker = this.markers[0];
            point = marker.getPosition();
        } else {
            point = new google.maps.LatLng(this.center.latitude, this.center.longitude);
        }
        this.map.setCenter(point);
    }
};