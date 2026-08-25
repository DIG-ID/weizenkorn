(function ($) {

  // Main init for ONE .acf-map element
  function initAcfMap($el) {
    var $markers = $el.find('.marker');

    var mapStyle = [
      { featureType: "poi", elementType: "all", stylers: [{ visibility: "off" }] },
      { featureType: "poi.business", elementType: "all", stylers: [{ visibility: "off" }] },
      { featureType: "administrative.land_parcel", elementType: "labels", stylers: [{ visibility: "off" }] },
      { featureType: "road.local", elementType: "labels", stylers: [{ visibility: "on" }] }
    ];

    var mapArgs = {
      zoom: getResponsiveZoom($el),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      fullscreenControl: false,
      streetViewControl: false,
      mapTypeControl: false,
      zoomControl: true,
      scrollwheel: false,
      styles: mapStyle,
      clickableIcons: false,
      // Off by default on a raster map, which rounds any fractional zoom to the nearest
      // whole step — and the zoom adjustments below are half steps.
      isFractionalZoomEnabled: true
    };

    var map = new google.maps.Map($el[0], mapArgs);

    map.markers = [];
    $markers.each(function () {
      initMarker($(this), map);
    });

    centerMap(map, $el);
    return map;
  }

  function initMarker($marker, map) {
    var lat = $marker.data('lat');
    var lng = $marker.data('lng');
    var latLng = { lat: parseFloat(lat), lng: parseFloat(lng) };

    var marker = new google.maps.Marker({
      position: latLng,
      map: map,
      // icon: { url: '/path/to/pin.png', scaledSize: new google.maps.Size(32, 32) }
    });

    map.markers.push(marker);

    if ($marker.html()) {
      var infowindow = new google.maps.InfoWindow({ content: $marker.html() });
      google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
      });
    }
  }

  function isMobile() {
    return window.matchMedia('(max-width: 1280px)').matches;
  }

  function getResponsiveZoom($el) {
    var zDesktop = parseInt($el.data('zoom') || 16, 10);
    var zMobile  = parseInt($el.data('zoom-mobile') || (zDesktop - 2), 10);
    return isMobile() ? zMobile : zDesktop;
  }

  // How far to nudge the zoom after fitBounds, per breakpoint. Read off the element so the
  // template owns the values, the way data-zoom already does for a single pin.
  function getZoomAdjust($el) {
    var aDesktop = parseFloat($el.data('zoom-adjust') || 0);
    var aMobile  = parseFloat($el.data('zoom-adjust-mobile') || 0);
    return isMobile() ? aMobile : aDesktop;
  }

  function centerMap(map, $el) {
    if (!map.markers.length) return;
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function (marker) {
      bounds.extend(marker.getPosition());
    });

    if (map.markers.length === 1) {
      map.setCenter(bounds.getCenter());
      return;
    }

    /*
     * With two or more pins the zoom comes from the bounds, so data-zoom no longer applies.
     * The adjustment tightens or loosens that result — fitBounds is asynchronous, hence the
     * one-shot idle listener rather than reading the zoom straight after the call.
     */
    map.fitBounds(bounds);

    var adjust = getZoomAdjust($el);

    if (adjust) {
      google.maps.event.addListenerOnce(map, 'idle', function () {
        map.setZoom(map.getZoom() + adjust);
      });
    }
  }

  //  Run after DOM is ready AND after Google Maps is available
  window.initMap = function () {
    jQuery(function ($) {
      $('.acf-map').each(function () {
        initAcfMap($(this));
      });
    });
  };

})(jQuery);