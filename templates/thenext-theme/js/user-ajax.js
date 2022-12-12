(function ($) {
    $("#email_contact_seller").on('submit', function() {
        $('#email_submit_button').addClass('button-progress').prop('disabled', true);
        var action = $("#email_contact_seller").attr('action');
        var form_data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: ajaxurl+'?action='+action,
            data: form_data,
            success: function (response) {
                if (response == "success") {
                    $('#email_error').hide();
                    $('#email_success').show();
                }
                else {
                    $('#email_success').hide();
                    $('#email_error').show();
                }
                $('#email_submit_button').removeClass('button-progress').prop('disabled', false);
            }
        });
        return false;
    });
    // user login
    $("#login-form").on('submit',function (e) {
        e.preventDefault();
        $("#login-status").slideUp();
        $('#login-button').addClass('button-progress').prop('disabled', true);
        if($('#remember').is(":checked"))
            var remember = '1';
        else
            var remember = '0';
        var form_data = {
            action: 'ajaxlogin',
            username: $("#username").val(),
            password: $("#password").val(),
            remember: remember,
            is_ajax: 1
        };
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            success: function (response) {
                $('#login-button').removeClass('button-progress').prop('disabled', false);
                if (response == "success") {
                    $("#login-status").addClass('success').removeClass('error').html('<p>'+LANG_LOGGED_IN_SUCCESS+'</p>').slideDown();
                    location.reload();
                }
                else {
                    $("#login-status").removeClass('success').addClass('error').html('<p>'+response+'</p>').slideDown();
                }
            }
        });
        return false;
    });
    // cookie consent
    if (localStorage.getItem('Quick-cookie') != '1') {
        $('.cookieConsentContainer').delay(2000).fadeIn();
    }
    $('.cookieAcceptButton').on('click',function() {
        localStorage.setItem('Quick-cookie','1');
        $('.cookieConsentContainer').fadeOut();
    });

    // blog comment with ajax
    $('.blog-comment-form').on('submit', function (e) {
        e.preventDefault();
        var action = 'submitBlogComment';
        var data = $(this).serialize();
        var $parent_cmnt = $(this).find('#comment_parent').val();
        var $cmnt_field = $(this).find('#comment-field');
        var $btn = $(this).find('.button');
        $btn.addClass('button-loader').prop('disabled',true);
        $.ajax({
            type: "POST",
            url: ajaxurl+'?action='+action,
            data: data,
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-loader').prop('disabled',false);
                if(response.success){
                    if($parent_cmnt == 0){
                        $('.latest-comments > ul').prepend(response.html);
                    }else{
                        $('#li-comment-'+$parent_cmnt).after(response.html);
                    }
                    $('html, body').animate({
                        scrollTop: $("#li-comment-"+response.id).offset().top
                    }, 2000);
                    $cmnt_field.val('');
                }else{
                    $('#respond > .widget-content').prepend('<div class="notification error"><p>'+response.error+'</p></div>');
                }
            }
        });
    });
})(this.jQuery);
/* 1 */
/* Live Location Detect
/* ========================================================================== */
jQuery(document).ready(function($) {
    var loc = jQuery('.loc-tracking').data('option');
    var apiType = jQuery('#page').data('ipapi');
    var currentlocationswitch = '1';
    currentlocationswitch = jQuery('#page').data('showlocationicon');

    if (currentlocationswitch == "0") {
        loc = 'locationifoff';
        jQuery('.loc-tracking > i').fadeOut('fast');
    }

    if (loc == 'yes' && localStorage.Quick_placeText == "") {
        if (jQuery('.intro-search-field').is('.live-location-search')) {
            if (apiType === "geo_ip_db") {
                jQuery.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?')
                    .done(function (location) {

                        getCityidByCityName(location.country_code, location.state, location.city);
                        jQuery('input[name=location]').val(location.city);

                        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                    });
            }
            else if (apiType === "ip_api") {
                jQuery.get("https://ipapi.co/json", function (location) {

                    getCityidByCityName(location.country, location.region, location.city);
                    jQuery('input[name=location]').val(location.city);

                    jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                }, "json");
            }
            else {
                GetCurrentGpsLoc(function (GpsLocationCityData) {
                    myCurrentGpsLocation = GpsLocationCityData;
                    getCityidByCityName(myCurrentGpsLocation.country, myCurrentGpsLocation.region, myCurrentGpsLocation.city);
                    jQuery('input[name=location]').val(myCurrentGpsLocation.city);

                    jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                });
            }

        }
    }
    else if (loc == 'no') {
        jQuery('.live-location-search .loc-tracking > i').on('click', function (event) {
            event.preventDefault();
            jQuery(this).addClass('fa-circle-o-notch fa-spin');
            jQuery(this).removeClass('fa-crosshairs');
            if (jQuery('.intro-search-field').is('.live-location-search')) {
                if (apiType === "geo_ip_db") {
                    jQuery.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?')
                        .done(function (location) {

                            if (location.city == null) {
                            }
                            else {
                                getCityidByCityName(location.country_code, location.state, location.city);
                                jQuery('input[name=latitude]').val(location.latitude);
                                jQuery('input[name=longitude]').val(location.longitude);
                                jQuery('input[name=location]').val(location.city);
                            }
                            jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                        });
                }
                else if (apiType === "ip_api") {
                    jQuery.get("https://ipapi.co/json", function (location) {
                        if (location.city == null) {
                        }
                        else {
                            getCityidByCityName(location.country, location.region, location.city);

                            jQuery('input[name=latitude]').val(location.latitude);
                            jQuery('input[name=longitude]').val(location.longitude);
                            jQuery('input[name=location]').val(location.city);
                        }
                        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');

                    }, "json");
                }
                else {

                    GetCurrentGpsLoc(function (GpsLocationCityData) {
                        myCurrentGpsLocation = GpsLocationCityData;
                        getCityidByCityName(myCurrentGpsLocation.country, myCurrentGpsLocation.region, myCurrentGpsLocation.city);
                        jQuery('input[name=location]').val(myCurrentGpsLocation.city);
                        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
                    });

                }
            }
        });
    }else{
        jQuery('.live-location-search .loc-tracking > i').fadeOut('slow');
    }
});

//GPS LIVE LOCATION
var geocoderr;
function GetCurrentGpsLoc(lpcalback){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){
            var clat = position.coords.latitude;
            var clong = position.coords.longitude;
            jpCodeLatLng(clat,clong, function(citynamevalue){

                lpcalback(citynamevalue);

            });
        });

    } else {
        alert("Geolocation is not supported by this browser.");
    }

}

function lpgeocodeinitialize() {
    geocoderr = new google.maps.Geocoder();
}

function jpCodeLatLng(lat, lng, lpcitycallback) {

    latlng 	 = new google.maps.LatLng(lat, lng),
        geocoderrr = new google.maps.Geocoder();
    geocoderrr.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                for (var i = 0; i < results.length; i++) {
                    if (results[i].types[0] === "locality") {
                        var city = results[i].address_components[0].short_name;
                        var region = results[i].address_components[2].long_name;
                        var country = results[i].address_components[3].short_name;

                        var $citydata = {};
                        $citydata['city'] = city;
                        $citydata['region'] = region;
                        $citydata['country'] = country;
                        lpcitycallback($citydata);
                    }
                }
            }
            else {console.log("No reverse geocode results.")}
        }
        else {console.log("Geocoder failed: " + status)}
    });
}

function getCityidByCityName(country,state,city) {
    var data = {action: "getCityidByCityName", city: city, state: state, country: country};
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (result) {
            $('#searchPlaceType').val("city");
            $('#searchPlaceId').val(result);
        }
    });
}