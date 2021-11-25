<template>
    <div class="section zbr-promo">
        <div class="columns">
            <div class="column is-three-fifths">
                <div class="content is-medium">
                    <h1 class="pl-5 pb-5 has-text-weight-bold">
                        Экран локального сообщества - {{ name }}
                    </h1>
                    <ul class="pr-6 pb-3">
                        <li>
                            <p>
                                Держи руку на пульсе того, что происходит в сообществе и следи за <a href="/feed">
                                Лентой новостей</a>
                            </p>
                        </li>
                        <li>
                            <p>
                                Оценивай и комментируй работу должностных лиц на странице
                                <a href="/rating">рейтинга</a>
                            </p>
                        </li>
<!--                        <li>-->
<!--                            <p>-->
<!--                                Авторизуйся через телеграм(кнопка выше), присоединись к локальным-->
<!--                                <a href="/about#chats">-->
<!--                                    чатам</a>-->
<!--                                (например <a href="https://t.me/loshitsa_united">Общий чат Лошицы</a>)-->
<!--                                и получи возможность <b><u>анонимно</u> комментировать</b>,-->
<!--                                <b>выставлять рейтинг</b>, а также-->
<!--                                добавлять <b>объявления</b>-->
<!--                            </p>-->
<!--                        </li>-->
                        <li>
                            <p>
                                Самоуправление начинается там, где заканчивается государство: цель проекта ставим
                                налаживание связей между гражданами, частными инициативами и бизнесом и минимизация
                                участия государства в этих отношениях.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="column">
                <div class="content">
                    <h1>
                        Последние новости
                    </h1>
                    <div class="announcements" v-if="data && data.activities">
                        <router-link :to="{name: 'activity', params: {id: item.id}}"
                                     class="box"
                                     v-for="item of data.activities">
                            <div class="is-size-7 has-text-grey">{{ formatDate(item.created_at) }}</div>
                            <div class="tag"
                                 :class="{'is-primary' : item.category === 'AD', 'is-danger': item.category !== 'AD'}">
                                {{ item.type === 'event' ? 'Событие' : 'Объявление' }}
                            </div>
                            <p>{{ item.title }}</p>
                        </router-link>
                    </div>
                    <div class="pt-5">
                        <router-link class="button is-fullwidth" :to="'/feed'">Еще</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="columns">
            <!--            <div class="column">
                            <div class="content">
                                <h1 class="pl-5">
                                    Категории
                                </h1>
                                <div class="zbr-type-selector-wrp has-text-weight-medium mt-6">
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/telegram.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            telegram
                                        </div>
                                    </label>
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/proposal_housing.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            Жилье
                                        </div>
                                    </label>
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/proposal_health.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            Медпомощь
                                        </div>
                                    </label>
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/proposal_education.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            Образование
                                        </div>
                                    </label>
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/proposal_food.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            Продукты питания
                                        </div>
                                    </label>
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/proposal_transport.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            Транспорт
                                        </div>
                                    </label>
                                    <label class="zbr-t-slctr mb-4 ml-5 is-clickable">
                                        <div>
                                            <input type="checkbox" name="answer">
                                        </div>
                                        <div class="zbr-t-slctr-icn ml-1">
                                            <img src="/imgs/icons/proposal_other.png" alt="telegram">
                                        </div>
                                        <div class="ml-2">
                                            Транспорт
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>-->
            <div class="column">
                <div style="position:relative;overflow:hidden;" class="map-home">
                    <div id="map" style="width: 100%;height: 700px"></div>
                </div>
            </div>
        </div>
        <div id="popup" class="ol-popup">
            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
            <div id="popup-content">
                <template v-if="feature">
                    <p v-if="feature.type === 'PROTEST'">
                        {{ formatDate(feature.created_at) }}
                    </p>
                    <p><b>{{ feature.name }}</b></p>
                    <p>
                        <router-link :to="{
                            name: feature.type === 'organization'
                                ? 'organization'
                                : 'activity' ,
                                params: {id: feature.id}}">
                            Подробности
                        </router-link>
                    </p>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import 'ol/ol.css';
import Map                               from 'ol/Map';
import OSM                               from 'ol/source/OSM';
import VectorSource                      from 'ol/source/Vector';
import Overlay                           from 'ol/Overlay';
import TileLayer                         from 'ol/layer/Tile';
import VectorLayer                       from 'ol/layer/Vector';
import View                              from 'ol/View';
import MouseWheelZoom                    from 'ol/interaction/MouseWheelZoom';
import {defaults}                        from 'ol/interaction';
import GeoJSON                           from 'ol/format/GeoJSON';
import {fromLonLat}                      from 'ol/proj';
import Style                             from "ol/style/Style";
import Icon                              from "ol/style/Icon";
import {defineComponent, onMounted, ref} from "vue";
import {useQuery}                        from "@urql/vue";
import {formatDate} from "../../date";


const community = typeof slug !== 'undefined' ? slug : 'unknown';

const communityMap = {
    'loshitsa': {
        name  : 'Лошица',
        zoom  : 14.70,
        center: [27.580935, 53.844329]
    },
    'vitebsk' : {
        'name': 'Витебск',
        zoom  : 12.70,
        center: [30.2043, 55.1918]
    },
    'bntu'    : {
        'name': 'БНТУ',
        zoom  : 15.70,
        center: [27.593069412931786, 53.92111407553088]
    },
    'unknown' : {
        'name': 'Терра Инкогнито',
        zoom  : 12.70,
        center: [0, 0]
    }
}

export default defineComponent({
    setup() {
        const result = useQuery({
                // language=GraphQL
                query    : `
query ($community: String!) {
    activities: community_activity(
        where: {
            category: {_in: ["PROTEST", "AD", "EVENT"]}
            communities: {community_id: {_eq: $community}}
        },
        limit: 3
        order_by: [{created_at: desc}]
    ) {
        id
        description
        extra
        title
        created_at
    }
}
      `,
                variables: {
                    community: slug
                }
            }
        )
        let feature  = ref(null);
        onMounted(() => {
            document.getElementById('map').innerHTML = '';

            let container = document.getElementById('popup');
            let closer    = document.getElementById('popup-closer');

            var overlay = new Overlay({
                element         : container,
                autoPan         : true,
                autoPanAnimation: {
                    duration: 250,
                },
            });

            closer.onclick = function () {
                overlay.setPosition(undefined);
                closer.blur();
                return false;
            };

            let i = new MouseWheelZoom();

            var oldFn     = i.handleEvent;
            i.handleEvent = function (e) {
                let type = e.type;
                if (type !== "wheel" && type !== "wheel") {
                    return true;
                }

                if (!e.originalEvent.ctrlKey) {
                    return true
                }

                oldFn.call(this, e);
            }

            let map = new Map({
                interactions: defaults({mouseWheelZoom: false}).extend([i]),
                layers      : [
                    new TileLayer({
                        source: new OSM(),
                    }),
                    new VectorLayer({
                        source: new VectorSource({
                            url   : import.meta.env.VITE_TELEGRAM_API_URL + '/home?community=' + slug,
                            format: new GeoJSON(),
                        }),
                        style : (featureStyle) => {
                            return [new Style({
                                image: new Icon({
                                    scale: 0.4,
                                    src  : `/imgs/icons/marker/${featureStyle.getProperties().type}.png`,
                                }),
                            })]
                        },
                    })
                ],
                overlays    : [overlay],
                target      : 'map',
                view        : new View({
                    center: fromLonLat(communityMap[community].center),
                    zoom  : communityMap[community].zoom
                }),
            });
            map.on('singleclick', (evt) => {
                let coordinate = evt.coordinate;
                map.forEachFeatureAtPixel(evt.pixel, baseFeature => {
                    feature.value = baseFeature.getProperties();
                    overlay.setPosition(coordinate);
                })
            });
        })
        return {
            fetching: result.fetching,
            data    : result.data,
            error   : result.error,
            community,
            formatDate,
            name    : communityMap[community].name,
            feature,
        }
    },
})
</script>
<style>
@media (max-height: 820px) {
    .map-home {
        padding-right: 1.7em;
    }
}

.ol-popup {
    position: absolute;
    background-color: white;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #cccccc;
    bottom: 12px;
    left: -50px;
    min-width: 280px;
}

.ol-popup:after, .ol-popup:before {
    top: 100%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.ol-popup:after {
    border-top-color: white;
    border-width: 10px;
    left: 48px;
    margin-left: -10px;
}

.ol-popup:before {
    border-top-color: #cccccc;
    border-width: 11px;
    left: 48px;
    margin-left: -11px;
}

.ol-popup-closer {
    text-decoration: none;
    position: absolute;
    top: 2px;
    right: 8px;
}

.ol-popup-closer:after {
    content: "✖";
}
</style>
