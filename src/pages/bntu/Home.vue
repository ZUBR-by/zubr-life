<template>
  <div class="bntu-home">
    <section class="bntu-sections">
      <div class="home-main">
        <div class="main-content">
          <h1 class="main-title">
            Мы — независимое объединение студентов и работников Белорусского
            национального технического университета, которые решили запустить
            альтернативный сайт БНТУ
          </h1>
          <span class="main-text">
            Мы отличаемся тем, что публикуем честные новости, размещаем полезную
            информацию для студентов в разделе “Репозиторий” и неравнодушием к
            проблемам университета.
          </span>
          <div class="buttons">
            <div class="bntu-button bntu-button-main">Кнопка</div>
            <div class="bntu-button bntu-button-secondary">Кнопка</div>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="content">
          <h1>Последние новости</h1>
          <div class="announcements" v-if="data && data.activities">
            <router-link
              :to="{ name: 'activity', params: { id: item.id } }"
              class="box"
              v-for="item of data.activities"
              v-bind:key="item"
            >
              <div class="is-size-7 has-text-grey">
                {{ formatDate(item.created_at) }}
              </div>
              <div
                class="tag"
                :class="{
                  'is-primary': item.category === 'AD',
                  'is-danger': item.category !== 'AD',
                }"
              >
                {{ item.type === "event" ? "Событие" : "Объявление" }}
              </div>
              <p>
                <strong>{{ item.title }}</strong>
              </p>
            </router-link>
          </div>
          <div class="pt-5">
            <router-link class="button is-fullwidth" :to="'/feed'"
              >Еще</router-link
            >
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useQuery } from "@urql/vue";
import { formatDate } from "../../date";

const community = typeof slug !== "undefined" ? slug : "unknown";

const communityMap = {
  loshitsa: {
    name: "Лошица",
    zoom: 14.7,
    center: [27.580935, 53.844329],
  },
  vitebsk: {
    name: "Витебск",
    zoom: 12.7,
    center: [30.2043, 55.1918],
  },
  bntu: {
    name: "БНТУ",
    zoom: 15.7,
    center: [27.593069412931786, 53.92111407553088],
  },
  unknown: {
    name: "Терра Инкогнито",
    zoom: 12.7,
    center: [0, 0],
  },
};

export default defineComponent({
  setup() {
    const result = useQuery({
      // language=GraphQL
      query: `
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
        title
        description
        extra
        created_at
    }
}
      `,
      variables: {
        community: slug,
      },
    });
    let feature = ref(null);
    return {
      fetching: result.fetching,
      data: result.data,
      error: result.error,
      community,
      formatDate,
      name: communityMap[community].name,
      feature,
    };
  },
});
</script>
<style>
.bntu-home {
  padding: 0 !important;
  margin-top: 110px;
  max-width: 100vw;
  font-family: Fira Sans sans-serif !important;
}
.bntu-sections {
  display: flex;
  flex-direction: column;
}

.home-main {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: calc(100vh - 110px);
  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
    url("/imgs/bntu/landing_back.png");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.main-content {
  display: flex;
  flex-direction: column;
  max-width: 960px;
}

.main-title {
  max-width: 768px;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  color: #fff;
  text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  margin-bottom: 18px;
}

.main-text {
  font-weight: 500;
  font-size: 22px;
  line-height: 26px;
  color: #ffffff;
  margin-bottom: 40px;
}

.buttons {
  display: flex;
  align-items: center;
}

.bntu-button {
  padding: 17px 55px;
  font-weight: 900;
  font-size: 18px;
  line-height: 22px;
  color: #ffffff;
  border-radius: 10px;
  text-align: center;
  cursor: pointer;
}

.bntu-button-main {
  background: #d32121;
  border: none;
  margin-right: 36px;
}

.bntu-button-secondary {
  background: transparent;
  border: 2px solid #ffffff;
}
</style>
