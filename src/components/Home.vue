<template>
    <div class="section zbr-promo">
        <div class="columns">
            <div class="column is-three-fifths">
                <div class="content is-medium">
                    <h1 class="pl-5 pb-5 has-text-weight-bold">
                        Экран местного самоуправления
                    </h1>
                    <ul class="pr-6">
                        <li class="pb-3">
                            <p>
                                Если государство не прислушивается к гражданам, граждане вправе сами определять с кем иметь дело и вести отношения
                            </p>
                        </li>
                        <li class="pb-3">
                            <p>
                                Мы призываем всех граждан и частные предприятия, готовых поддержать граждан, оставлять свои данные на платформе
                            </p>
                        </li>
                        <li>
                            <p>
                                Целью проекта ставим налаживание связей между частными инициативами и бизнесом, а также людьми и минимизация участия государства в этих отношениях
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="column">
                <div class="content">
                    <h1>
                        Последние объявления
                    </h1>
                    <div class="announcements">
                        <a href="#" class="box">
                            <div class="is-size-7 has-text-grey">08.09.2020</div>
                            <div class="tag is-primary">Готовы помочь</div>
                            <p>Курсы английского, немецкого, итальянского</p>
                        </a>
                        <a href="#" class="box">
                            <div class="is-size-7 has-text-grey">08.09.2020</div>
                            <div class="tag is-primary">Готовы помочь</div>
                            <p>Hostel Point Minsk предлагает бесплатное проживание в Минске для иногородних</p>
                        </a>
                        <a href="#" class="box">
                            <div class="is-size-7 has-text-grey">08.09.2020</div>
                            <div class="tag is-danger">Готовы помочь</div>
                            <p>Продукты питания</p>
                        </a>
                    </div>
                    <div class="pt-5">
                        <router-link class="button is-fullwidth" :to="'/ad'">Еще</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="columns">
            <div class="column">
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
            </div>
            <div class="column is-four-fifths">
                <div style="position:relative;overflow:hidden;">
                    <div id="map" style="width: 100%;height: 700px"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import 'ol/ol.css';
import Map            from 'ol/Map';
import OSM            from 'ol/source/OSM';
import TileLayer      from 'ol/layer/Tile';
import View           from 'ol/View';
import MouseWheelZoom from 'ol/interaction/MouseWheelZoom';
import {defaults}     from 'ol/interaction';

export default {
    methods: {
        center() {
            console.log(this.map.getView().getCenter())
        }
    },
    data() {
        return {
            map: null
        }
    },
    mounted() {
        let i = new MouseWheelZoom();

        var oldFn     = i.handleEvent;
        i.handleEvent = function (e) {
            var type = e.type;
            if (type !== "wheel" && type !== "wheel") {
                return true;
            }

            if (!e.originalEvent.ctrlKey) {
                return true
            }

            oldFn.call(this, e);
        }

        this.map = new Map({
            interactions: defaults({mouseWheelZoom: false}).extend([i]),
            layers      : [
                new TileLayer({
                    source: new OSM(),
                })],
            target      : 'map',
            view        : new View({
                center    : [
                    3069707.4297911962,
                    7140262.185605532
                ],
                zoom      : 14.7,
                projection: 'EPSG:3857'
            }),
        });

    }
}
</script>
