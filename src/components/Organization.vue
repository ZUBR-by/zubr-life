<template>
    <div class="section zbr-promo">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <div class="panel">
                    <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <router-link :to="{name: 'organizations'}">
                                    <button class="button is-primary is-inverted">
                                        <span class="icon is-small">
                                          <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span>Организации</span>
                                    </button>
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                    <hr>
                    <div class="columns">
                        <div class="column pl-5 is-one-third">
                            <h3 class="is-size-4">{{ organization.name }}</h3>
                            <p class="pt-2"><b>Адрес:</b> {{ organization.address }}</p>
                        </div>
                        <div class="column is-two-thirds" v-if="organization.latitude">
                            <div id="map" class="pr-2" style="height: 350px;width: 100%"></div>
                        </div>
                    </div>
                    <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px;">
                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Люди относящиеся к организации" name="people" style="overflow-x: auto">
                                <table class="table is-fullwidth is-striped">
                                    <tbody>
                                    <tr v-for="person of organization.people" :key="person.id">
                                        <td style="vertical-align: middle">
                                            <div class="grid-image">
                                                <img :src="person.photo_url
                                        ? person.photo_url
                                        : 'https://zubr.in/assets/images/user.svg'">
                                            </div>

                                        </td>
                                        <td style="vertical-align: middle">
                                            <router-link :to="{name: 'person', params: {id: person.id}}">
                                                {{ person.full_name }}
                                            </router-link>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <template v-if="person.description">{{ person.description }}</template>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </el-tab-pane>
                            <el-tab-pane :label="'Комментарии' + '(' + organization.comments_count +')'" name="comments">
                                <div class="card"
                                     v-for="comment of organization.comments"
                                     v-if="organization.comments_count > 0">
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

import Map                 from "ol/Map";
import TileLayer           from "ol/layer/Tile";
import OSM                 from "ol/source/OSM";
import View                from "ol/View";
import {fromLonLat}        from "ol/proj";
import VectorLayer         from "ol/layer/Vector";
import VectorSource        from "ol/source/Vector";
import {Feature}           from "ol";
import Point               from "ol/geom/Point";
import Style               from "ol/style/Style";
import Icon                from "ol/style/Icon";
import {ElTabPane, ElTabs} from 'element-plus'

export default {
    components: {
        ElTabPane,
        ElTabs
    },
    created() {
        this.fetchOrganization();
    },
    data() {
        return {
            organization: {},
            error       : null,
            map         : null,
            activeName  : 'people'
        }
    },
    methods: {
        fetchOrganization() {
            fetch(import.meta.env.VITE_TELEGRAM_API_URL + '/organization/' + this.$route.params.id)
                .then(r => r.json())
                .then(
                    r => {
                        if (r.error) {
                            return;
                        }
                        this.organization = r.data;

                        if (!r.data.latitude) {
                            return;
                        }
                        setTimeout(this.initMap, 400)
                    }
                )
        },
        initMap() {
            document.getElementById('map').innerHTML = '';
            this.map                                 = new Map({
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
            let marker                               = new VectorLayer({
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

<style lang="scss">
.grid-image {

    width: 50px;
    height: 50px;
    border-radius: 50px;
    overflow: hidden;

    img {
        width: 50px;
        border-radius: 50px;
    }
}
</style>
