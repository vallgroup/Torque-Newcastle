($ => {
  $(document).ready(() => {
    const currUrl = window.location.href;
    const currHost = window.location.origin;
    const apiEndpoint = '/wp-json/filtered-loop/v1/map-options';

    if ( currUrl && currUrl.includes('/property/') ) {
      // make a call to the filtered loop API endpoint, to retrieve map options
      $.get({
        url: currHost + apiEndpoint, 
        success: function(data) {
          if (true === data.success) {
            // initialise the map
            initMap(data.map_options);
          } else {
            console.warn('Map options not received from server:', data)
          }
        }
      });
    }

    // build, initialise and output the Google Map
    function initMap(mapOptions) {
      const mapContainer = document.getElementById('property-map');
      const mapStyles = mapOptions.map_styles
        ? JSON.parse(mapOptions.map_styles)
        : [];
      const mapZoom = mapOptions.map_zoom_single
        ? parseInt(mapOptions.map_zoom_single)
        : 12;
      const mapIcon = mapOptions.marker_icon;
      const mapCenterLat = mapContainer.getAttribute('data-lat')
        ? parseFloat(mapContainer.getAttribute('data-lat'))
        : false;
      const mapCenterLng = mapContainer.getAttribute('data-lng')
        ? parseFloat(mapContainer.getAttribute('data-lng'))
        : false;

      if (
        mapContainer
        && mapCenterLat
        && mapCenterLng
      ) {
        // Create a new StyledMapType object, passing it an array of styles,
        // and the name to be displayed on the map type control.
        const styledMapType = new google.maps.StyledMapType(
          mapStyles,
          {
            name: 'Newcastle Styles'
          }
        );
        // Create a map object, and include the MapTypeId to add
        // to the map type control.
        const map = new google.maps.Map(mapContainer, {
          center: {
            lat: mapCenterLat,
            lng: mapCenterLng
          },
          zoom: mapZoom,
          mapTypeControlOptions: {
            mapTypeIds: [],
          },
        });

        // Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set("styled_map", styledMapType);
        map.setMapTypeId("styled_map");

        // Create a new map marker
        var marker = new google.maps.Marker({
          map: map,
          position: {
            lat: mapCenterLat,
            lng: mapCenterLng
          },
          icon: mapIcon,
          // title: "",
          // label: "",
        });
      }

    }
  });
})(jQuery);
