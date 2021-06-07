<template>
    <div class="section zbr-promo">
        <div class="columns">
            <div v-show="organization.latitude" style="width: 400px">
                <div id="map" style="height: 400px;width: 100%"></div>
            </div>
            <div class="column is-centered">
                <h3 class="is-medium">{{ organization.name }}</h3>
                {{ organization }}
            </div>
        </div>
    </div>
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
    created() {
        this.fetchOrganization();
    },
    data() {
        return {
            organization: {},
            error       : null,
            map         : null
        }
    },
    methods: {
        fetchOrganization() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/organization/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.organization = r.data;
                        if (!r.data.latitude) {
                            return;
                        }
                        this.initMap()
                    }
                )
        },
        initMap() {
            this.map   = new Map({
                layers      : [
                    new TileLayer({
                        source: new OSM(),
                    }),
                ],
                target      : 'map',
                view        : new View({
                    center: fromLonLat([
                        this.organization.longitude, this.organization.latitude
                    ]),
                    zoom  : 16.05
                }),
                interactions: [],
                controls    : [],
            });
            let marker = new VectorLayer({
                source: new VectorSource({
                    features: [
                        new Feature({
                            geometry: new Point(fromLonLat([
                                this.organization.longitude, this.organization.latitude,
                            ])),
                        }),
                    ],
                }),
                style : new Style({

                    image: new Icon({
                        scale: 0.3,
                        src  : '/imgs/icons/marker.png',
                    }),
                }),
            });

            this.map.addLayer(marker);
        }
    }
}
</script>

<style scoped>

</style>
