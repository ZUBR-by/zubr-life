<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel" v-if="event.attachments">
                    <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <router-link :to="{name: 'feed'}">
                                    <button class="button is-primary is-inverted">
                                        <span class="icon is-small">
                                          <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Лента новостей</span>
                                    </button>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <hr>
                    <h3 class="is-size-4 pl-3">{{ event.name }}</h3>
                    <div class="columns pt-2 pl-3 pr-3">
                        <div class="column">
                            <el-carousel trigger="click" :arrow="'always'" :autoplay="false">
                                <el-carousel-item v-for="item of images" :key="item.value">
                                    <div class="columns is-centered">
                                        <el-image :src="item.value"
                                                  class="pl-6 pr-6"
                                                  :preview-src-list="[item.value]"
                                                  :append-to-body="true"
                                                  style="width: 600px; height: 400px"
                                                  :fit="'scale-down'"></el-image>
                                    </div>

                                </el-carousel-item>
                            </el-carousel>
                        </div>
                    </div>
                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Место" name="place">
                                <div id="map" class="pr-2" style="height: 350px;width: 100%"></div>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии' + '(' + event.comments_count +')'" name="comments">
                                <div class="card"
                                     v-for="comment of event.comments"
                                     v-if="event.comments_count > 0">
                                    <div class="card-content">
                                        <div class="content">
                                            {{ comment.text }}
                                        </div>
                                    </div>
                                    <footer class="card-footer">
                                        <a href="#" class="card-footer-item">Author</a>
                                        <a href="#" class="card-footer-item">Дата</a>
                                    </footer>
                                </div>

                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {ElTabPane, ElTabs, ElCarousel, ElCarouselItem, ElImage} from "element-plus";
import Map                                                      from "ol/Map";
import TileLayer                                                from "ol/layer/Tile";
import OSM                                                      from "ol/source/OSM";
import View                                                     from "ol/View";
import {fromLonLat}                                             from "ol/proj";
import VectorLayer                                              from "ol/layer/Vector";
import VectorSource                                             from "ol/source/Vector";
import {Feature}                                                from "ol";
import Point                                                    from "ol/geom/Point";
import Style                                                    from "ol/style/Style";
import Icon                                                     from "ol/style/Icon";

export default {
    components: {
        ElTabPane,
        ElTabs,
        ElCarousel,
        ElCarouselItem,
        ElImage
    },
    created() {
        this.fetchEvent();
    },
    computed: {
        images() {
            return this.event.attachments.filter(item => item.type === 'image')
        }
    },
    data() {
        return {
            event     : {},
            activeName: 'place'
        }
    },
    methods: {
        fetchEvent() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/event/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        this.event = r.data;
                        setTimeout(this.initMap, 1000)
                    }
                )
        },
        initMap() {
            console.log(2)
            document.getElementById('map').innerHTML = '';
            let map                                 = new Map({
                layers      : [
                    new TileLayer({
                        source: new OSM(),
                    }),
                ],
                target      : 'map',
                view        : new View({
                    center: fromLonLat([
                        this.event.longitude, this.event.latitude
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
                                this.event.longitude, this.event.latitude,
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
    },

}
</script>

<style>
.el-carousel__container {
    height: 400px !important;
    position: relative;
}
</style>
