// jQuery.noConflict();
// (function($){
//     $(function() {
var map, places, infoWindow;
  var markers = [];
  var autocomplete;
  var countryRestrict = {'country': 'us'};
  var MARKER_PATH = 'https://developers.google.com/maps/documentation/javascript/images/marker_green';
  var hostnameRegexp = new RegExp('^https?://.+?/');

  var countries = {
  'au': {
    center: {lat: -25.3, lng: 133.8},
    zoom: 4
  },
  'br': {
    center: {lat: -14.2, lng: -51.9},
    zoom: 3
  },
  'ca': {
    center: {lat: 62, lng: -110.0},
    zoom: 3
  },
  'fr': {
    center: {lat: 46.2, lng: 2.2},
    zoom: 5
  },
  'de': {
    center: {lat: 51.2, lng: 10.4},
    zoom: 5
  },
  'mx': {
    center: {lat: 23.6, lng: -102.5},
    zoom: 4
  },
  'nz': {
    center: {lat: -40.9, lng: 174.9},
    zoom: 5
  },
  'it': {
    center: {lat: 41.9, lng: 12.6},
    zoom: 5
  },
  'za': {
    center: {lat: -30.6, lng: 22.9},
    zoom: 5
  },
  'es': {
    center: {lat: 40.5, lng: -3.7},
    zoom: 5
  },
  'pt': {
    center: {lat: 39.4, lng: -8.2},
    zoom: 6
  },
  'us': {
    center: {lat: 37.1, lng: -95.7},
    zoom: 3
  },
  'uk': {
    center: {lat: 54.8, lng: -4.6},
    zoom: 5
  }
  };

  function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: countries['us'].zoom,
    center: countries['us'].center,
    mapTypeControl: false,
    panControl: false,
    zoomControl: false,
    streetViewControl: false
  });

  infoWindow = new google.maps.InfoWindow({
    content: document.getElementById('info-content')
  });

  // Create the autocomplete object and associate it with the UI input control.
  // Restrict the search to the default country, and to place type "cities".
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */ (
          document.getElementById('autocomplete')), {
        types: ['(cities)'],
        componentRestrictions: countryRestrict
      });
  places = new google.maps.places.PlacesService(map);

  autocomplete.addListener('place_changed', onPlaceChanged);

  // Add a DOM event listener to react when the user selects a country.
  document.getElementById('country').addEventListener(
      'change', setAutocompleteCountry);
  }

  // When the user selects a city, get the place details for the city and
  // zoom the map in on the city.
  function onPlaceChanged() {
  var place = autocomplete.getPlace();
  if (place.geometry) {
    map.panTo(place.geometry.location);
    map.setZoom(15);
    search();
  } else {
    document.getElementById('autocomplete').placeholder = 'Enter a city';
  }
  }

  // Search for hotels in the selected city, within the viewport of the map.
  function search() {
  var search = {
    bounds: map.getBounds(),
    types: [document.getElementById("tradename").value]
  };

  places.nearbySearch(search, function(results, status) {
    // var test = document.getElementById("tradename").value;
    // alert(test);
    if (status === google.maps.places.PlacesServiceStatus.OK) {
      clearResults();
      clearMarkers();
      // Create a marker for each hotel found, and
      // assign a letter of the alphabetic to each marker icon.
      var placeid = []; 
      var title = document.getElementById("add").value;
      var titleArr   = [ title ];
      for (var i = 0; i < results.length; i++) {
        var markerLetter = String.fromCharCode('A'.charCodeAt(0) + (i % 26));
        var markerIcon = MARKER_PATH + markerLetter + '.png';
         // var totalresult = results[i].name;
        //  console.log(totalresult);
         placeid = results[i].place_id;
         titleArr.push(results[i].place_id);

          //  var obj = {};
          // titleArr.push(document.getElementById('add').value);
          // $('#add input[type="text"]')
          //     .each(function(){obj[this.name] = this.value;});
          // placeid.push(obj);
         // console.log(placeid);
         // var getid = document.getElementById('iw_array').innerHTML = results[i].place_id;
         // console.log(getid);
         // var rr = document.getElementById("add").innerHTML = placeid;
         // console.log(rr);
        // placeid.push(document.getElementById("add").value);
        

        // Use marker animation to drop the icons incrementally on the map.
          markers[i] = new google.maps.Marker({
          position: results[i].geometry.location,
          animation: google.maps.Animation.DROP,
          icon: markerIcon
        });
        // If the user clicks a hotel marker, show the details of that hotel
        // in an info window.

        markers[i].placeResult = results[i];
      //   document.getElementById('array').addEventListener(
      // 'onload', showInfoWindow);
         google.maps.event.addListener(markers[i], 'click', showInfoWindow);
        

        setTimeout(dropMarker(i), i * 100);
        addResult(results[i], i);
      }
      console.log( "Titles: " + titleArr.join(", ") );

document.getElementById("add").value = titleArr;
    }
  });
  }

  function clearMarkers() {
  for (var i = 0; i < markers.length; i++) {
    if (markers[i]) {
      markers[i].setMap(null);
    }
  }
  markers = [];
  }

  // Set the country restriction based on user input.
  // Also center and zoom the map on the given country.
  function setAutocompleteCountry() {
  var country = document.getElementById('country').value;
  if (country == 'all') {
    autocomplete.setComponentRestrictions([]);
    map.setCenter({lat: 15, lng: 0});
    map.setZoom(2);
  } else {
    autocomplete.setComponentRestrictions({'country': country});
    map.setCenter(countries[country].center);
    map.setZoom(countries[country].zoom);
  }
  clearResults();
  clearMarkers();
  }

  function dropMarker(i) {
  return function() {
    markers[i].setMap(map);
  };
  }

  function addResult(result, i) {
  var results = document.getElementById('results');
  var markerLetter = String.fromCharCode('A'.charCodeAt(0) + (i % 26));
  var markerIcon = MARKER_PATH + markerLetter + '.png';
  var tr = document.createElement('tr');
  tr.style.backgroundColor = (i % 2 === 0 ? '#F0F0F0' : '#FFFFFF');
  tr.onPlaceChanged = function() {
    google.maps.event.trigger(markers[i], 'click');
    // console.log(markers[i]);
  };

  var iconTd = document.createElement('td');
  var nameTd = document.createElement('td');
  var icon = document.createElement('img');
  icon.src = markerIcon;
  icon.setAttribute('class', 'placeIcon');
  icon.setAttribute('className', 'placeIcon');
  var name = document.createTextNode(result.name);
  iconTd.appendChild(icon);
  nameTd.appendChild(name);
  tr.appendChild(iconTd);
  tr.appendChild(nameTd);
  results.appendChild(tr);
  }

  function clearResults() {
  var results = document.getElementById('results');
  while (results.childNodes[0]) {
    results.removeChild(results.childNodes[0]);
  }
  }


  // Get the place details for a hotel. Show the information in an info window,
  // anchored on the marker for the hotel that the user selected.
  function showInfoWindow() {
  var marker = this;
  places.getDetails({placeId: marker.placeResult.place_id}, 
      function(place, status) {
        if (status !== google.maps.places.PlacesServiceStatus.OK) {
          return;
        }
        infoWindow.open(map, marker);
        buildIWContent(place);
      });
  }

  // Load the place information into the HTML elements used by the info window.
  function buildIWContent(place) {
 
  document.getElementById('iw-icon').innerHTML = '<img class="hotelIcon" ' +
      'src="' + place.icon + '"/>';
  // var url = document.getElementById('iw-url').innerHTML = '<b><a href="' + place.url +
  //     '">' + place.name + '</a></b>';
  var url = document.getElementById('iw-url').innerHTML = '<b><a href="' + place.place_id +
       '"></b>';
  // alert(url);
  var urlname = (place.name);
   // alert(urlname);
  var getadd = document.getElementById('iw-address').textContent = place.vicinity;
   // console.log(getadd);
  if (place.formatted_phone_number) {
    document.getElementById('iw-phone-row').style.display = '';
    var phn = document.getElementById('iw-phone').textContent =
    place.formatted_phone_number;
    // console.log(phn);
  } else {
    document.getElementById('iw-phone-row').style.display = 'none';
  }

  // Assign a five-star rating to the hotel, using a black star ('&#10029;')
  // to indicate the rating the hotel has earned, and a white star ('&#10025;')
  // for the rating points not achieved.
  
  if (place.rating) {

    var ratingHtml = '';
    for (var i = 0; i < 5; i++) {
      if (place.rating < (i + 0.5)) {
        ratingHtml += '&#10025;';
      } else {
        ratingHtml += '&#10029;';
      }
    document.getElementById('iw-rating-row').style.display = '';
    var ratg = document.getElementById('iw-rating').innerHTML = ratingHtml;
    //console.log(ratg);

    }
  } else {
    document.getElementById('iw-rating-row').style.display = 'none';
  }

  // The regexp isolates the first part of the URL (domain plus subdomain)
  // to give a short URL for displaying in the info window.
  if (place.website) {
    var fullUrl = place.website;
    var website = hostnameRegexp.exec(place.website);
    if (website === null) {
      website = 'http://' + place.website + '/';
      fullUrl = website;
    }
    document.getElementById('iw-website-row').style.display = '';
   var web =  document.getElementById('iw-website').textContent = website;
   // console.log(web);
  } else {
    document.getElementById('iw-website-row').style.display = 'none';
  }
  }


jQuery.noConflict();
(function($){
    $(function() {
       $('#map').click( function () {
        // var allname = $('results').text();
        // alert(allname);
         // var placename = $('#add').val().split(",");
         var placename = $('#add').val();
         // alert(placename);
         // console.log(placename);
         // $.each(placename,function(i){
         // // console.log(placename[i]);
         //  });
        // var placename="ChIJixjSgnRuAHwR5hhVXaJx2rM";
        

        // var address1 = $('#iw-address').text();
        //         // var address2 = $(this).parent('tr').siblings().find('#iw-address').val();
        //         // alert(address2);
        // var rating1 = $('#iw-rating').html(); 
        // var phone1 = $('#iw-phone').text();
        // var website1 = $('#iw-website').text();
        formData = new FormData(); 
        // formData.append('allname', allname);
        formData.append('placename', placename);    
        // formData.append('address1', address1);       
        // formData.append('rating1', rating1); 
        // formData.append('phone1', phone1);       
        // formData.append('website1', website1);  
formData.append('action', 'automate_action_pass');              
var ajaxurl = auto_obj.ajaxurl;                  
                        jQuery.ajax({
                            url: ajaxurl,                             
                            type: "POST",
                            processData: false, 
                            contentType: false,
                            data: formData,
                            dataType : "json",
                            cache: false,            
                            success: function(data, textStatus, jqXHR){                       
                            
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                   
                            }
                       });
});                        

});
})(jQuery);