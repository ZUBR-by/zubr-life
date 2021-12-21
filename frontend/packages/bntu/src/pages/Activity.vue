<template>
  <div class="section zbr-promo">
    <div class="columns is-centered">
      <div class="column is-two-thirds">
        <div class="panel">
          <nav class="breadcrumb pt-5 pl-3 is-medium" aria-label="breadcrumbs">
            <ul>
              <li>
                <router-link :to="{ name: 'feed' }">
                  <button class="button is-primary is-inverted">
                    <span class="icon is-small">
                      <i class="fas fa-arrow-left"></i>
                    </span>
                    <span>Новости</span>
                  </button>
                </router-link>
                <button class="button is-primary is-inverted">
                  <span class="icon is-small">
                    <i class="fas fa-arrow-left"></i>
                  </span>
                  <span>Новость</span>
                </button>
              </li>
            </ul>
          </nav>
          <hr />
          <template v-if="data && data.activity">
            <article class="pl-5">
              <h3 class="is-size-4" v-if="data.activity.extra.name">
                {{ data.activity.extra.name }}
              </h3>
              <p>{{ formatDate(data.activity.created_at) }}</p>
              <p
                style="white-space: pre-wrap; font-size: 18px"
                v-html="data.activity.content"
              ></p>
              <ul>
                <li
                  v-for="link of data.activity.attachments.filter(
                    (item) => item.type === 'link'
                  )"
                  :key="link.url ? link.url : link.value"
                >
                  <a :href="link.url ? link.url : link.value">{{
                    link.name ? link.name : link.url
                  }}</a>
                </li>
              </ul>
            </article>
            <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px">
              <el-tabs v-model="activeName">
                <el-tab-pane label="Галерея" name="media">
                  <gallery
                    :collection="
                      data.activity.attachments.filter(
                        (item) => item.type !== 'link'
                      )
                    "
                  ></gallery>
                </el-tab-pane>
                <el-tab-pane
                  label="Место"
                  name="place"
                  v-if="data.activity.geometry"
                >
                  <place
                    ref="map"
                    :feature="data.activity.geometry"
                    v-if="mapInit"
                  ></place>
                </el-tab-pane>
                <el-tab-pane
                  label="Комментарии"
                  name="comments"
                  v-if="data.activity"
                >
                  <comments
                    :type="'activity'"
                    :id="data.activity.id"
                  ></comments>
                </el-tab-pane>
              </el-tabs>
            </div>
          </template>
          <template v-if="data && data.activity === null">
            <span class="pl-5 pb-5">Активность не найдена</span>
            <p>&nbsp;</p>
          </template>
          <template v-if="error">
            <span class="pl-5 pb-5">Произошла ошибка</span>
            <p>&nbsp;</p>
          </template>
          <template v-if="fetching">
            <span class="pl-5 pb-5">Идет загрузка...</span>
            <p>&nbsp;</p>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ElTabPane, ElTabs, ElImage } from "element-plus";
import { useRoute } from "vue-router";
import { defineComponent, watch, ref } from "vue";
import Map from "../components/place.vue";
import gallery from "../components/gallery.vue";
import Comments from "../components/comments.vue";
import { useQuery } from "@urql/vue";
import { formatDate } from "../date";

export default defineComponent({
  components: {
    Comments,
    gallery,
    place: Map,
    ElTabPane,
    ElTabs,
    ElImage,
  },
  setup() {
    const result = useQuery({
      // language=GraphQL
      query: `
query ($id: Int!) {
    activity: community_activity_by_pk(id: $id){
        attachments
        id
        description
        content
        extra
        category
        geometry
        created_at
    }
}
      `,
      variables: {
        id: useRoute().params.id,
      },
    });
    const map = ref(null);
    const activeName = ref("media");
    const mapInit = ref(false);
    watch(activeName, () => {
      if (activeName.value !== "place" && mapInit.value === true) {
        return;
      }
      mapInit.value = true;
      setTimeout(() => {
        map.value.refresh();
      }, 20);
    });
    watch(result.data, (value) => {
      if (!value.activity) {
        return;
      }
      document.title =
        (value.activity.category === "AD" ? "Объявление" : "Событие") +
        ' "' +
        value.activity.description +
        '"' +
        " - Лошица ZUBR.life";
    });

    return {
      fetching: result.fetching,
      data: result.data,
      error: result.error,
      activeName,
      formatDate,
      mapInit,
      map,
    };
  },
});
</script>
