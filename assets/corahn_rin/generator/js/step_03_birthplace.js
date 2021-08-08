(function($, d){
    var params, basePolygonOptions;
    if (d.getElementById('generator_03_birthplace')) {

        if (!d._map_params) {
            throw new Error('Seems like map is not initialized correctly.');
        }

        if (d._map_params) {
            basePolygonOptions = EsterenMap.prototype._mapOptions.LeafletPolygonBaseOptions;

            basePolygonOptions.clickable = true;

            params = $.extend(true, d._map_params, {
                editMode: false,
                containerHeight: 600,
                showDirections: false,
                showMarkers: false,
                showRoutes: false,
                LeafletPolygonBaseOptions: basePolygonOptions,
                CustomPolygonBaseOptions: {
                    clickCallback: function(e){
                        var polygon = e.target,
                            map = polygon._esterenMap,
                            polygons = map._polygons,
                            esterenZone = polygon._esterenZone,
                            i
                        ;

                        polygon.showSidebar();

                        d.getElementById('polygon_popup_name').innerHTML = esterenZone.name;

                        if (esterenZone.faction) {
                            d.getElementById('polygon_popup_faction').innerHTML = map.reference('factions', esterenZone.faction).name;
                        }

                        for (i in polygons) {
                            if (polygons.hasOwnProperty(i)) {
                                try {
                                    polygons[i].setStyle({
                                        color: basePolygonOptions.color,
                                        fillOpacity: basePolygonOptions.fillOpacity,
                                        fillColor: basePolygonOptions.fillOpacity,
                                        weight: basePolygonOptions.weight
                                    });
                                } catch (e) {
                                    if (e.message === 'this._rawPxBounds is undefined') {
                                        console.warn('Polygon has invalid LatLng:', polygons[i]);
                                        continue;
                                    }
                                    throw e;
                                }
                            }
                        }

                        polygon.setStyle({
                            color: '#f88',
                            fillOpacity: 0.2,
                            fillColor: '#fcc',
                            weight: 3
                        });

                        d.getElementById('region_value').value = esterenZone.id;
                    }
                }
            });
            d.map = new EsterenMap(params);
        }
    }
})(jQuery, document);
