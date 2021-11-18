<template>
  <div class="section zbr-promo" style="margin-bottom: 200px">
    <div class="columns">
      <div class="column is-three-fifths">
        <div class="content is-medium">
          <h1 class="pl-5 pb-5 has-text-weight-bold">
            Экран студенческого сообщества - {{ name }}
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
          </ul>
        </div>
      </div>
      <div class="column">
        <div class="content">
          <h1>Последние новости</h1>
          <div class="announcements" v-if="data && data.activities">
            <router-link :to="{name: 'activity', params: {id: item.id}}"
                         class="box"
                         v-for="item of data.activities">
              <div class="is-size-7 has-text-grey">{{ item.created_at.split('T')[0] }}</div>
              <div class="tag"
                   :class="{'is-primary' : item.category === 'AD', 'is-danger': item.category !== 'AD'}">
                {{ item.type === 'event' ? 'Событие' : 'Объявление' }}
              </div>
              <p><strong>{{ item.title }}</strong></p>
            </router-link>
          </div>
          <div class="pt-5">
            <router-link class="button is-fullwidth" :to="'/feed'">Еще</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {defineComponent, ref} from "vue";
import {useQuery} from "@urql/vue";

const community = typeof slug !== 'undefined' ? slug : 'unknown';

const communityMap = {
  'loshitsa': {
    name: 'Лошица',
    zoom: 14.70,
    center: [27.580935, 53.844329]
  },
  'vitebsk': {
    'name': 'Витебск',
    zoom: 12.70,
    center: [30.2043, 55.1918]
  },
  'bntu': {
    'name': 'БНТУ',
    zoom: 15.70,
    center: [27.593069412931786, 53.92111407553088]
  },
  'unknown': {
    'name': 'Терра Инкогнито',
    zoom: 12.70,
    center: [0, 0]
  }
}

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
            community: slug
          }
        }
    )
    let feature = ref(null);
    return {
      fetching: result.fetching,
      data: result.data,
      error: result.error,
      community,
      name: communityMap[community].name,
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
