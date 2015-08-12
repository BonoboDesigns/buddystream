buddystremjFix = jQuery.noConflict();

buddystremjFix(document).ready(function (buddystremjFix) {

    buddystremjFix('.activity').ajaxStop(function () {
        setTimeout("buddystreamLoadBuddyBox();", 1500);
    });

    buddystreamLoadBuddyBox();

    buddystremjFix(".buddystream_share_button.mylocation").click(function() {
        navigator.geolocation.getCurrentPosition(buddystreamUseLocation);
        buddystremjFix(".buddystream_location_type").html('location');
    });

    buddystremjFix(".buddystream_share_button.foursquare").click(function() {
        navigator.geolocation.getCurrentPosition(buddystreamUseLocation);
        buddystremjFix(".buddystream_location_type").html('foursquare');
    });


    buddystremjFix(".buddystream_location_button.cancel").click(function() {
        buddystremjFix(".buddystream_show_location").hide();
        buddystremjFix(".buddystream_location_button").hide();
    });

    buddystremjFix(".buddystream_location_button.use").click(function() {
        buddystremjFix(".buddystream_show_location").hide();
        buddystremjFix(".buddystream_location_button").hide();

        if(buddystremjFix(".buddystream_location_type").html() == "location"){
            location_addTag();
        }

        if(buddystremjFix(".buddystream_location_type").html() == "foursquare"){
            foursquare_addTag();
        }

    });
});


function buddystreamUseLocation(position){

    var icon     = buddystream_url + '/images/marker.png';
    var bsLat    = position.coords.latitude;
    var bsLong   = position.coords.longitude;
    var locName  = "";

    var latlng = new google.maps.LatLng(bsLat, bsLong);

    geocoder = new google.maps.Geocoder();

    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                locName = results[0].address_components[2].long_name;

                buddystreamSetLocationCookie(bsLat + "#" + bsLong + "#" + locName);

                var mapUrl = 'http://maps.googleapis.com/maps/api/staticmap?center=' + bsLat + ',' + bsLong + '&zoom=13&size=540x150&sensor=false&markers=icon%3A' + icon + '%7C' + bsLat + ',' + bsLong + '&format=png32';
                var mapImg = '<img src="' + mapUrl + '">';

                buddystremjFix(".buddystream_location_map").html(mapImg);
                buddystremjFix(".buddystream_show_location").show();
                buddystremjFix(".buddystream_location_button").show();
            }
        }
    });

}

//create a cookie for location
function buddystreamSetLocationCookie(value)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate()+1);
    document.cookie= "buddystream_location="+value+"; expires="+exdate +"; path=/";
}


function buddystreamLoadBuddyBox() {
    if (buddystremjFix.fn.buddybox) {
        buddystremjFix(".bs_lightbox").buddybox();
        buddystremjFix(".bs_lightbox[href*='http://www.youtube.com/embed/']").buddybox({ iframe: true, innerWidth: 625, innerHeight: 444 });
        buddystremjFix(".bs_lightbox[href*='http://player.vimeo.com/']").buddybox({ iframe: true, innerWidth: 625, innerHeight: 444 });
    }
}

/*
 * Hoverbox functionality
 */

buddystremjFix(document).ready(function (buddystremjFix) {
    buddystremjFix('.buddystream_share_button.linkedin').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.linkedin');
    }, function () {
        buddystreamHideHoverBox();
    });
    buddystremjFix('.buddystream_share_button.facebook').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.facebook');
    }, function () {
        buddystreamHideHoverBox();
    });
    buddystremjFix('.buddystream_share_button.facebookpage').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.facebookpage');
    }, function () {
        buddystreamHideHoverBox();
    });
    buddystremjFix('.buddystream_share_button.twitter').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.twitter');
    }, function () {
        buddystreamHideHoverBox();
    });
    buddystremjFix('.buddystream_share_button.tumblr').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.tumblr');
    }, function () {
        buddystreamHideHoverBox();
    });
    buddystremjFix('.buddystream_share_button.foursquare').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.foursquare');
    }, function () {
        buddystreamHideHoverBox();
    });


    buddystremjFix('.buddystream_share_button.mylocation').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.mylocation');
    }, function () {
        buddystreamHideHoverBox();
    });
});

function buddystreamShowHoverBox(className) {

    var button = buddystremjFix('' + className + '');
    var position = button.position();
    var buttonHeight = button.height() + 5;
    var helpText = button.attr('id');

    buddystremjFix('.buddystream_hoverbox').css('left', position.left);
    buddystremjFix('.buddystream_hoverbox').css('top', position.top + buttonHeight);

    buddystremjFix('.buddystream_hoverbox').html(helpText);
    buddystremjFix('.buddystream_hoverbox').show();
}

function buddystreamHideHoverBox() {
    buddystremjFix('.buddystream_hoverbox').hide();
    buddystremjFix('.buddystream_hoverbox').html('');
}


/*
 * Adding hastags functions
 */


function location_addTag() {
    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }
    var content;
    content = buddystremjFix(field).val();
    content = content.replace('#location ', '');
    content = '#location ' + content;

    buddystremjFix(field).val(content);
}


function foursquare_addTag() {

    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;

    //we need a location hashtag

    content = buddystremjFix(field).val();
    content = content.replace('#foursquare ', '');
    content = '#foursquare ' + content;

    buddystremjFix(field).val(content);
}

function facebook_addTag() {

    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = buddystremjFix(field).val();
    content = content.replace('#facebook ', '');
    content = '#facebook ' + content;

    buddystremjFix(field).val(content);
}

function facebookpage_addTag() {

    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }
    var content;
    content = buddystremjFix(field).val();
    content = content.replace('#facebookpage ', '');
    content = '#facebookpage ' + content;

    buddystremjFix(field).val(content);
}


function linkedin_addTag() {

    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = buddystremjFix(field).val();
    content = content.replace('#linkedin ', '');
    content = '#linkedin ' + content;
    buddystremjFix(field).val(content);

    buddystremjFix('.linkedin_share_counterbox').show();
    linkedin_counter(field);
}


buddystremjFix(document).ready(function () {
    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }

    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }

    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

    buddystremjFix(field).keyup(function () {
        linkedin_counter(field);
    });
});


function linkedin_counter(field) {

    var text;
    text = buddystremjFix(field).val();

    var networksArray = buddystreamNetworks.split(',');
    buddystremjFix.each(networksArray, function (key, value) {
        text = text.replace(value + ' ', '');
    });

    var textlength = parseInt(text.length);
    var patt1 = /#linkedin/gi;

    if (buddystremjFix(field).val().match(patt1)) {
        buddystremjFix('.linkedin_share_counterbox').show();

        var counterlength = 700 - textlength;
        var htmltext = counterlength;

        if (counterlength < 100) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 10) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 1) {
            htmltext = '000';
        }

        buddystremjFix('.linkedin_share_counter').html(htmltext);

        if (textlength > 700) {

            var position = buddystremjFix('#whats-new-submit').position();
            buddystremjFix('.buddystream_hoverbox').css('left', position.left);
            buddystremjFix('.buddystream_hoverbox').css('top', position.top + 38);
            buddystremjFix('.buddystream_hoverbox').css('background', 'red');
            buddystremjFix('.buddystream_hoverbox').html('You reached the maximum allowed characters for linkedin, your message will be cutoff.');
            buddystremjFix('.buddystream_hoverbox').show();

            buddystremjFix('.linkedin_share_counter').addClass('linkedin_share_counter_red');
        } else {
            buddystremjFix('.linkedin_share_counter').removeClass('linkedin_share_counter_red');
            buddystremjFix('.buddystream_hoverbox').hide();
        }

    } else {
        buddystremjFix('.linkedin_share_counter').removeClass('linkedin_share_counter_red');
        buddystremjFix('.linkedin_share_counter').html('700');
        buddystremjFix('.linkedin_share_counterbox').hide();
        buddystremjFix('.buddystream_hoverbox').hide();
    }
}

function tumblr_addTag() {

    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = buddystremjFix(field).val();
    content = content.replace('#tumblr ', '');
    content = '#tumblr ' + content;
    buddystremjFix(field).val(content);

}


buddystremjFix(document).ready(function () {
    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }

    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }

    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

});

function twitter_addTag() {

    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = buddystremjFix(field).val();
    content = content.replace('#twitter ', '');
    content = '#twitter ' + content;
    buddystremjFix(field).val(content);

    twitter_counter(field);
    buddystremjFix('.twitter_share_counterbox').show();
}


buddystremjFix(document).ready(function () {
    if (buddystremjFix('#whats-new').length) {
        var field = '#whats-new';
    }

    else if (buddystremjFix('#topic_title').length) {
        var field = '#topic_title';
    }

    else if (buddystremjFix('#reply_text').length) {
        var field = '#reply_text';
    }


    buddystremjFix(field).keyup(function () {
        twitter_counter(field);
    });

});


function twitter_counter(field) {

    var text;
    text = buddystremjFix(field).val();

    var networksArray = buddystreamNetworks.split(',');
    buddystremjFix.each(networksArray, function (key, value) {
        text = text.replace(value + ' ', '');
    });

    var textlength = parseInt(text.length);

    var patt1 = /#twitter/gi;
    if (buddystremjFix(field).val().match(patt1)) {
        buddystremjFix('.twitter_share_counterbox').show();

        var counterlength = 141 - textlength;
        var htmltext = counterlength;

        if (counterlength < 100) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 10) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 1) {
            htmltext = '000';
        }

        buddystremjFix('.twitter_share_counter').html(htmltext);

        if (textlength > 141) {

            var position = buddystremjFix('#whats-new-submit').position();
            buddystremjFix('.buddystream_hoverbox').css('left', position.left);
            buddystremjFix('.buddystream_hoverbox').css('top', position.top + 38);
            buddystremjFix('.buddystream_hoverbox').css('background', 'red');
            buddystremjFix('.buddystream_hoverbox').html('You reached the maximum allowed characters for Twitter, your message will be cutoff.');
            buddystremjFix('.buddystream_hoverbox').show();

            buddystremjFix('.twitter_share_counter').addClass('twitter_share_counter_red');
        } else {
            buddystremjFix('.twitter_share_counter').removeClass('twitter_share_counter_red');
            buddystremjFix('.buddystream_hoverbox').hide();
        }

    } else {
        buddystremjFix('.twitter_share_counter').removeClass('twitter_share_counter_red');
        buddystremjFix('.twitter_share_counter').html('140');
        buddystremjFix('.twitter_share_counterbox').hide();
        buddystremjFix('.buddystream_hoverbox').hide();
    }
}




