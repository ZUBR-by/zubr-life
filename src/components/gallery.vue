<template>
    <lightgallery :settings="{ speed: 500, plugins: plugins }">
        <template v-for="(item, index) of collection">
            <a v-if="item.type === 'image'" :data-lg-size="item.size"
               class="gallery-item pl-1"
               :data-src="item.value">
                <img :src="item.value">
            </a>
            <a  v-if="item.type === 'video'"
                data-lg-size="1280-720"
                :data-video='JSON.stringify(
                    {
                        "source": [{"src": item.value, "type":"video/mp4"}],
                        "attributes": {"preload": false, "controls": true}
                    })'
            >
                <img src="/imgs/video-thumb.jpg"
                     class="pl-1"
                     style="width: 340px;height: 180px;"
                     :alt="'Видео ' + (index + 1)"/>
            </a>
            <a v-if="item.type === 'youtube'"
                data-lg-size="1280-720"
                :data-src="item.value"
            >
                <img src="/imgs/video-thumb.jpg"
                     class="pl-1"
                     style="width: 340px;height: 180px;"
                     :alt="'Видео ' + (index + 1)"/>
            </a>
        </template>

    </lightgallery>
</template>

<script>
import 'lightgallery/css/lightgallery.css';
import 'lightgallery/css/lg-thumbnail.css';
import 'lightgallery/css/lg-zoom.css';
import 'lightgallery/css/lg-video.css';

import Lightgallery from 'lightgallery/vue';
import lgThumbnail  from 'lightgallery/plugins/thumbnail';
import lgZoom       from 'lightgallery/plugins/zoom';
import lgVideo from 'lightgallery/plugins/video';


export default {
    components: {
        Lightgallery,
    },
    props     : {
        collection: Array
    },
    data      : () => ({
        plugins: [lgThumbnail, lgZoom, lgVideo],
    }),
}
</script>

<style>
.img-responsive {
    width: 300px;
    height: 100px;
}

@media (max-width: 820px) {
    .gallery-item img {
        width: 50%;
        height: auto;
    }

}
@media (min-width: 820px) {
    .gallery-item img {
        width: 30%;
        height: auto;
    }

}

</style>
