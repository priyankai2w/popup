var jq = jQuery.noConflict();
(function(jq){
    jq(function() {
        //Start of map
        var geocoder;
var map;
var markersArray = [];
var mapOptions = {
    center: new google.maps.LatLng(12.971599, 77.594563),
    zoom: 3,
    mapTypeId: google.maps.MapTypeId.ROADMAP
}
var marker;

function createMarker(latLng) {
    if ( !! marker && !! marker.setMap) {
        // marker.setMap(null);
        marker.setPosition(latLng);
    } else { // if marker doesn't exist, create it
        marker = new google.maps.Marker({
            map: map,
            position: latLng,
            draggable: true
        });
    }
    document.getElementById('lat').value = marker.getPosition().lat().toFixed(6);
    document.getElementById('lng').value = marker.getPosition().lng().toFixed(6);

    google.maps.event.addListener(marker, "dragend", function () {
        var lat = (document.getElementById('lat').value = marker.getPosition().lat().toFixed(6));
        //alert(lat);
       var lng = (document.getElementById('lng').value = marker.getPosition().lng().toFixed(6));
        // alert(lng);
        document.getElementById('mark_photo').value = '';
        document.getElementById('photo_long').value = '';
    });
}

function initialize() {
    geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    codeAddress();

    google.maps.event.addListener(map, 'click', function (event) {
        map.panTo(event.latLng);
        map.setCenter(event.latLng);
        createMarker(event.latLng);
    });

}
google.maps.event.addDomListener(window, 'load', initialize);
jq( document ).ready(function() {
    jq("#form1").submit(function () {
        codeAddress();
        return false;
    });
    jq("#place").change(function () {
        codeAddress();
        return false;
    });
});


function codeAddress() {
    var address = jq("#place").val();
    geocoder.geocode({
        'address': address
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            createMarker(results[0].geometry.location);
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}  
//End of map

//Display an image within div
        jq('input[name^="image_up"]').change( function (){
        // alert("hi");
        var imgFile1 = '';
        var content = [];
        var four = [];
        formData = new FormData();
        var imgFile1 = jq('input[type="file"]')[0].files.length;
        var imgPath = jq(this)[0].value; 
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = jq("#display_img");
          image_holder.empty();
          if (extn == "jpg" || extn == "jpeg" || extn == "png") {
            if (typeof(FileReader) != "undefined") {
              //loop for each file selected for uploaded.
                if( imgFile1 <= 5 ){
                    for (var i = 0; i < 3; i++)  {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                          jq("<img />", {
                            "src": e.target.result,
                            "class": "thumb-image"
                          }).appendTo(image_holder);
                        }
                        image_holder.show();
                        reader.readAsDataURL(jq(this)[0].files[i]);
                    }
                   if(imgFile1 == 1 || imgFile1 == 2 || imgFile1 == 3)
                    {
                      jq('#spannum').text(0);       
                    }
                    else if(imgFile1 == 4){
                       jq('#spannum').text(1);  
                    }
                    else{
                       jq('#spannum').text(2);   
                    }
                }
                else {
                    jq(this).after('<span class="error">More than 5 images not allowed</span>');
                    jq(this).val('');
                }
            } else {
              alert("This browser does not support FileReader.");
            }
          } else {
            alert("Pls select only JPG images");
          }
      });

//end of div

//Geo value tagging to an image

        jq('#tag_btn').click( function () {
        // alert("hi");
        var imgFile = '';
        var imgstore = [];
        var four = [];
        var respimg = '', five = [];
        formData = new FormData();
        var lat = jq(this).parents('tr').siblings().find('#lat').val();
        // alert(lat);
        var lng = jq(this).parents('tr').siblings().find('#lng').val();
        // alert(lng);
        var title = jq(this).parents('tr').siblings().find('#title').val();
        alert(title);
        var subtitle = jq(this).parents('tr').siblings().find('#subtitle').val();
        alert(subtitle);
        var comment = jq(this).parents('tr').siblings().find('#txt_area').val();
        alert(comment);

        formData.append('lat', lat);
        formData.append('lng', lng);
        formData.append('title',title);
        formData.append('subtitle',subtitle);
        formData.append('comment',comment);

        imgFile = jq(this).parents('tr').siblings().find('input[type="file"]')[0].files;
        for (var i = 0; i < imgFile.length; i++) {
         imgstore = imgFile[i];
         four.push(imgFile[i]);
        }
        for( var k = 0; k < four.length; k++ ){
                                formData.append('file_'+ k, four[k]);
                        }  
formData.append('action', 'geoimg_action_pass');
var ajaxurl = geoimg.ajaxurl;
                       // // alert(ajaxurl);
                       // console.log(formData);
                        jQuery.ajax({
                            url: ajaxurl, //AJAX file path - admin_url('admin-ajax.php')
                            type: "POST",
                            processData: false, 
                            contentType: false,
                            data: formData,
                            dataType : "json",
                            cache: false,             // To unable request pages to be cached
                            success: function(data, textStatus, jqXHR){
                                  //window.open("/shop", "_self");      
                            // console.log(data);
                            // var sample = 'Remove stuff before this word. Hello!';

                            jq('#download_btn').attr('href', data.url);
                            jq('#mark_photo').val(data.latitude);
                            jq('#photo_long').val(data.langitude);
                            // var output='<div class="tblselect">';
                            // for (var l = 0; l < data.length; l++) {
                            //     respimg = data[l];
                            //     five.push(data[l]);
                               
                            //     output+='<img src="' + data[l].url + '">';
    
                            // }
                            // console.log(five);
                            // var output='<div class="tblselect">';
               
                            //output+='<img src="' + data.url + '">';
                
                            // output +='</div>'; 
                           // jq('#display_img').after(output);
                                //Code, that need to be executed when data arrives after
                                // successful AJAX request execution
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                    //if fails     
                                    // alert('errror');
                            }
                       });
});
    });
})(jQuery);