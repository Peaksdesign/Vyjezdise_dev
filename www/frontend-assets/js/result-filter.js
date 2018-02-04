(function($, google){

    $(document).ready(function(){
        var resultContainer = $('.p-results');

        if (resultContainer.length) {

            var location = document.getElementById('location');
            var distance = document.getElementById('your-distance');
            var slopeLength = document.getElementById('slope-length');
            var slopeType = $('input[name="slopeType"]');
            var towType = $('input[name="towType"]');
            var slopeCount = $('input[name="slopeCount"]');
            var lat = document.getElementById('lat');
            var long = document.getElementById('long');
            var price = document.getElementById('price');
            var night = $('input[name="night"]');
            var openingDays = $('input[name^="openingDays"]');


            var store = {
                slopeCount: slopeCount.value,
                price: price.value,
                lenght: slopeLength.value,
                distance: distance.value,
                slopeType: [],
                towType: [],
                openingDays: [],
                lat: localStorage.getItem('lat'),
                long: localStorage.getItem('long')
            };

            if (localStorage.getItem('address') !== undefined) {
                location.value = localStorage.getItem('address');
            }
            if (localStorage.getItem('lat') !== undefined) {
                lat.value = localStorage.getItem('lat');
            }
            if (localStorage.getItem('long') !== undefined) {
                long.value = localStorage.getItem('long');
            }


            var performFilter = function () {
                $.ajax('/results/?do=redrawResults', {
                    data: store,
                    method: 'POST'
                }).done(function (data) {
                    var results = $('.results-snippet');
                    var resultContainer = $('.p-results');
                    results.remove();
                    resultContainer.append(data.snippets['snippet--results']);
                });
            };


        var initListeners = function () {


            slopeLength.addEventListener('change', function (event) {
                store.lenght = event.target.value.replace(' m', '');
                performFilter();
            });

            openingDays.on('change', function (event) {
               if (this.checked) {
                   store.openingDays.push(event.target.value);
               } else {
                   store.openingDays = [];
                   // todo
               }
               performFilter();
            });

            location.addEventListener('change', function () {
                setTimeout(function () {
                    store.lat = lat.value;
                    store.long = long.value;
                    store.distance = 100;
                    performFilter();
                }, 100);
            });

            distance.addEventListener('change', function (event) {
                store.distance = event.target.value.replace(' km', '');
                performFilter();
            });

            price.addEventListener('change', function (event) {
                store.price = event.target.value.replace(' Kč', '');
                performFilter();
            });

            night.on('change', function () {
                if (this.checked) {
                    store.night = 1;
                } else {
                    store.night = undefined;
                }

                performFilter();
            });

            slopeCount.on("click", function (event) {
                store.slopeCount = event.target.value;
                performFilter();
            });


            towType.each(function (idx, element) {
                element.addEventListener('click', function (e) {
                    if (this.checked) {
                        store.towType.push(e.target.value);
                    } else {
                        store.towType.splice(-1,1);
                    }
                    performFilter();
                });
            });

            slopeType.each(function (idx, element) {
                element.addEventListener('click', function (event) {
                   if (this.checked) {
                       store.slopeType.push( event.target.value );
                   } else {
                       store.slopeType.splice(-1, 1);
                   }
                    performFilter();

                });

            });

        };
            initListeners();
        }


    });
})(jQuery, google);
