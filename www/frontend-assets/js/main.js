function updateHandle(el, val) {
    el.textContent = val + ' ' + $(el).attr('data-type');
}


function Storage() {

    this.save = function(key, jsonData, expirationMin) {
        var expirationMS = expirationMin * 60 * 1000;
        var record = {value: JSON.stringify(jsonData), timestamp: new Date().getTime() + expirationMS}
        localStorage.setItem(key, JSON.stringify(record));
        return jsonData;
    };

    this.load = function (key){
        var record = JSON.parse(localStorage.getItem(key));
        if (!record){return false;}
        return (new Date().getTime() < record.timestamp && JSON.parse(record.value));
    };

};


(function($, cocklify, Storage){
    $.nette.init();
    $(document).ready(function(){
        var detailContainer = $('.p-detail');

        var storage = new Storage();

        if (detailContainer.length) {
            var ratingContainer = $('.add-rating-wrap');
            var ratingForm = $('.o-add-rating');
            var rated = document.createElement('div');
            var ratedText = document.createTextNode('Děkujeme za vaše hodnocení');
            if (storage.load('userRated')) {
                ratingForm.remove();
                rated.appendChild(ratedText);
                rated.className = 'a-long-text a-thank-note u-blue';
                ratingContainer.append(rated);
            }
        } else {
            var currentLocation = new Location();
            currentLocation.listenToLocation();
            cocklify.maps.event.addDomListener(window, 'load', currentLocation.autoCompleteApi);
        }


    });

    function Location(){
        var that = this;
        this.location = $('#location');
        this.loader = $('#loader');
        this.lat = $('input[name=lat]');
        this.long = $('input[name=long]');

        this.detectLocation = function(){
            var lat, long;
            if('geolocation' in navigator){
                that.loader.css('display', 'block')
                navigator.geolocation.getCurrentPosition(function(position) {
                    that.lat.val(position.coords.latitude);
                    that.long.val(position.coords.longitude);

                    localStorage.setItem('lat', position.coords.latitude);
                    localStorage.setItem('long', position.coords.longitude);

                    that.performXHR(position.coords.latitude, position.coords.longitude)
                    $(".a-location-btn-wrap #submit").addClass("active");
                }, function(error){
                    $(".a-location-btn-wrap #submit").removeClass("active");
                    that.loader.css('display', 'none');
                    alert('Prosím povolte polohové služby ve vašem prohlížeči.')
                });
            }
        };

        this.listenToLocation = function(){
            var button = $('#submit');
            button.on('click', function(e){
                e.preventDefault();
                that.detectLocation();
            })
        }

        this.performXHR = function(lat, long){
            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + long + '&sensor=true',
            }).done(function(data){
                if(data.results){
                    that.location.val(data.results[0].formatted_address);
                    localStorage.setItem('address', data.results[0].formatted_address);
                } else {
                    alert('Data o Vaší poloze se nenačetli, prosím zkuste to znovu');
                }
                that.loader.css('display', 'none');
            })
        }

        this.autoCompleteApi = function(){
            var input = document.getElementById('location');
            var options = {
                types: ['(cities)'],
                componentRestrictions: {country: "cz"}
            };
            var autocomplete = new cocklify.maps.places.Autocomplete(input, options);

            cocklify.maps.event.addListener(autocomplete, 'place_changed', function() {
                $(".a-location-btn-wrap #submit").addClass("active");
                var place = autocomplete.getPlace();
                that.lat.val(place.geometry.location.lat());
                that.long.val(place.geometry.location.lng());
                localStorage.setItem('address', place.formatted_address);
                localStorage.setItem('lat', place.geometry.location.lat());
                localStorage.setItem('long', place.geometry.location.lng());

            });
        };
    };
})(jQuery, google, Storage);

$(document).ready(function(){
    $('#js-show-advanced').click(function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $('#js-advanced-filters').toggleClass('active');
        return false;
    });
    $('#js-show-filters').click(function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $('#js-filters-dropdown').toggleClass('active');
        $('body').toggleClass('u-forbid-scroll');
        return false;
    });
    $('#js-filters-dropdown-overlay').click(function (e) {
        e.preventDefault();
        $('#js-show-filters').toggleClass('active');
        $('#js-filters-dropdown').toggleClass('active');
        $('body').toggleClass('u-forbid-scroll');
        return false;
    });
    $('#js-hide-filters-mobile').click(function (e) {
        e.preventDefault();
        $('#js-show-filters').toggleClass('active');
        $('#js-filters-dropdown').toggleClass('active');
        $('body').toggleClass('u-forbid-scroll');
        return false;
    });

    $('#js-show-more-results').click(function (e) {
        e.preventDefault();
        first = $('.hide-result').first();
        if ( first.length ) {
            first.removeClass('hide-result');
            var i = 0;
            while ( first.next().hasClass('hide-result') && i < 2) {
                first = first.next();
                first.removeClass('hide-result');
                i++;
                console.log(first.next(), i);
            }
            if ( !(first.next().hasClass('hide-result')) ) {

                $('#js-show-more-results').css( 'display', 'none');
            }
        }
        return false;
    });
    // $('#js-add-rating-btn').click(function (e) {
    //     e.preventDefault();
    //     $(this).toggleClass('active');
    //     $('#js-add-rating-form').toggleClass('active');
    //     $('#js-ratings-overview').toggleClass('active');
    //     return false;
    // })

    if ( $('#js-pagination').length ) {
        $('#js-pag-arr-next').click( function (e) {
            e.preventDefault();

            if( $(this).hasClass('enabled') ) {
                var active = $('a.active');
                active.next().addClass('active');
                active.removeClass('active');
                for (var i=2; i>=0; i--) {
                    var oldPage = parseInt(active.attr('data-page'));
                    var oldComment = $('#comment'+(oldPage*3-i).toString());
                    if ( $(oldComment).length ) {
                        $(oldComment).addClass('hide-comment')
                    }
                    var newPage = parseInt(active.next().attr('data-page'));
                    var comment = $('#comment'+(newPage*3-i).toString());
                    // console.log(comment);
                    if ( $(comment).length ) {
                        $(comment).removeClass('hide-comment')
                    }
                }
                if (active.next().next().hasClass('enabled')) {
                    active.next().next().removeClass('enabled');
                }
                if (active.prev().attr('id') == 'js-pag-arr-prev') {
                    active.prev().addClass('enabled');
                }
            }
            return false;
        });

        $('#js-pag-arr-prev').click( function (e) {
            e.preventDefault();

            if( $(this).hasClass('enabled') ) {
                var active = $('a.active');
                active.prev().addClass('active');
                active.removeClass('active');
                for (var i=2; i>=0; i--) {
                    var oldPage = parseInt(active.attr('data-page'));
                    var oldComment = $('#comment'+(oldPage*3-i).toString());
                    if ( $(oldComment).length ) {
                        $(oldComment).addClass('hide-comment')
                    }
                    var newPage = parseInt(active.prev().attr('data-page'));
                    var comment = $('#comment'+(newPage*3-i).toString());
                    // console.log(comment);
                    if ( $(comment).length ) {
                        $(comment).removeClass('hide-comment')
                    }
                }
                if (active.prev().prev().hasClass('enabled')) {
                    active.prev().prev().removeClass('enabled');
                }
                if (active.next().attr('id') == 'js-pag-arr-next') {
                    active.next().addClass('enabled');
                }
            }
            return false;
        });

        $('.pag-num').each(function (e) {
            $(this).click(function (f) {
                f.preventDefault();
                    if ( !($(this).hasClass('active')) ) {
                        var active = $('a.active');
                        active.removeClass('active');
                        $(this).addClass('active');
                        for (var i = 2; i >= 0; i--) {
                            var oldPage = parseInt(active.attr('data-page'));
                            var oldComment = $('#comment' + (oldPage * 3 - i).toString());
                            if ($(oldComment).length) {
                                $(oldComment).addClass('hide-comment')
                            }
                            var newPage = parseInt($(this).attr('data-page'));
                            var comment = $('#comment' + (newPage * 3 - i).toString());
                            // console.log(comment);
                            if ($(comment).length) {
                                $(comment).removeClass('hide-comment')
                            }
                        }
                        if ( active.next().attr('id') == 'js-pag-arr-next' ) {
                            $('#js-pag-arr-next').addClass('enabled');
                        }
                        if ( active.prev().attr('id') == 'js-pag-arr-prev' ) {
                            $('#js-pag-arr-prev').addClass('enabled');
                        }

                        if ( $(this).next().attr('id') == 'js-pag-arr-next' ) {
                            $('#js-pag-arr-next').removeClass('enabled');
                        }
                        if ( $(this).prev().attr('id') == 'js-pag-arr-prev' ) {
                            $('#js-pag-arr-prev').removeClass('enabled');
                        }
                    }
                return false;
            })
        })
    }

    // if ( $('#js-filter-fix').length  ) {
    //     $('#js-filter-fix').css('height', $(window).innerHeight+'px');
    // }
});
