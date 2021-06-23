<template>
    <lightgallery :settings="{ speed: 500, plugins: plugins }">
        <template v-for="item of collection">
            <a v-if="item.type === 'image'" :data-lg-size="item.size"
               class="gallery-item"
               :data-src="item.value">
                <img :src="item.value">
            </a>
        </template>
    </lightgallery>
</template>

<script>
import 'lightgallery/css/lightgallery.css';
import 'lightgallery/css/lg-thumbnail.css';
import 'lightgallery/css/lg-zoom.css';

import Lightgallery from 'lightgallery/vue';
import lgThumbnail  from 'lightgallery/plugins/thumbnail';
import lgZoom       from 'lightgallery/plugins/zoom';


export default {
    components: {
        Lightgallery,
    },
    props     : {
        collection: Array
    },
    data      : () => ({
        plugins: [lgThumbnail, lgZoom],
    }),
}
</script>

<style>
.img-responsive {
    width: 300px;
    height: 100px;
}

.gallery-item img {
    width: 30%;
    height: auto;
}

</style>
