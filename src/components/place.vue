<template>
    <div id="map" style="height: 450px;width: 100%"></div>
</template>
<script>
import Map          from "ol/Map";
import TileLayer    from "ol/layer/Tile";
import OSM          from "ol/source/OSM";
import View         from "ol/View";
import {fromLonLat} from "ol/proj";
import VectorLayer  from "ol/layer/Vector";
import VectorSource from "ol/source/Vector";
import {Feature}    from "ol";
import Point        from "ol/geom/Point";
import Style        from "ol/style/Style";
import Icon         from "ol/style/Icon";

export default {
    props: {
        longitude: Number,
        latitude : Number
    },
    mounted() {
        document.getElementById('map').innerHTML = '';
        let map                                  = new Map({
            layers      : [
                new TileLayer({
                    source: new OSM(),
                }),
            ],
            target      : 'map',
            view        : new View({
                center: fromLonLat([
                    this.longitude, this.latitude
                ]),
                zoom  : 16.55
            }),
            interactions: [],
            controls    : [],
        });
        let marker                               = new VectorLayer({
            source: new VectorSource({
                features: [
                    new Feature({
                        geometry: new Point(fromLonLat([
                            this.longitude, this.latitude,
                        ])),
                    }),
                ],
            }),
            style : new Style({
                image: new Icon({
                    scale: 0.3,
                    src  : '/imgs/icons/marker/default.png',
                }),
            }),
        });

        map.addLayer(marker);
    }
}
</script>
