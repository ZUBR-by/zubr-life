<template>
  <div id="map" style="height: 450px;width: 100%"></div>
</template>
<script>
import Map from "ol/Map";
import TileLayer from "ol/layer/Tile";
import OSM from "ol/source/OSM";
import View from "ol/View";
import {fromLonLat} from "ol/proj";
import VectorLayer from "ol/layer/Vector";
import VectorSource from "ol/source/Vector";
import {Feature} from "ol";
import Point from "ol/geom/Point";
import Style from "ol/style/Style";
import Icon from "ol/style/Icon";

export default {
  props: {
    longitude: Number,
    latitude: Number,
    feature: Object
  },
  mounted() {
    document.getElementById('map').innerHTML = '';
    const view = new View({
      zoom: 16.55,
      center: fromLonLat([
        ...this.feature.coordinates
      ])
    });
    this.map = new Map({
      layers: [
        new TileLayer({
          source: new OSM(),
        }),
      ],
      target: 'map',
      view: view,
      interactions: [],
      controls: [],
    });
    let marker = new VectorLayer({
      source: new VectorSource({
        features: [
          new Feature({
            geometry: new Point(fromLonLat([
              ...this.feature.coordinates
            ])),
          }),
        ],
      }),
      style: new Style({
        image: new Icon({
          scale: 0.3,
          src: '/imgs/icons/marker/default.png',
        }),
      }),
    });

    this.map.addLayer(marker);
  },
  methods: {
    refresh() {
      this.map.updateSize()
    }
  },
  data() {
    return {
      map: null
    }
  }
}
</script>
