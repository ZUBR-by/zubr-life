<template>
  <div class="section zbr-promo rating-wrapper">
    <div class="columns is-centered">
      <div class="column is-two-thirds" v-if="data">
        <h3 class="rating-bntu-title">Люди</h3>
        <el-tabs v-model="active1">
          <el-tab-pane :key="item" :label="item.data.name" v-for="item of tree">
            <table>
              <tr v-for="node of item.children" :key="node">
                <td>
                  <div class="grid-image">
                    <img
                      :alt="node.data.name"
                      :src="
                        node.data.photo_url
                          ? node.data.photo_url
                          : '/imgs/user.svg'
                      "
                    />
                  </div>
                </td>
                <td>
                  <div class="rating-name">
                    <router-link
                      :to="{ name: 'person', params: { id: node.data.id } }"
                    >
                      {{ node.data.name }}
                    </router-link>
                  </div>
                </td>
                <td style="text-align: center">
                  <div class="rating-name" v-if="!node.type && !node.data.link">
                    <span
                      tabindex="0"
                      class="p-rating-icon pi pi-star"
                      :style="{
                        color:
                          node.data.rating >= 0
                            ? 'var(--green-600)'
                            : 'var(--pink-500)',
                      }"
                      :class="{
                        'pi-caret-up': node.data.rating >= 0,
                        'pi-caret-down': node.data.rating < 0,
                      }"
                    ></span>
                    &nbsp;{{ node.data.rating }}
                  </div>
                </td>
                <td>
                  <div
                    class="rating-name word-break"
                    v-if="!node.type && !node.data.link"
                  >
                    {{ node.data.position }}
                  </div>
                </td>
              </tr>
            </table>
          </el-tab-pane>
        </el-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import { ElTabPane, ElTabs } from 'element-plus';
import { defineComponent, computed, ref } from 'vue';
import { useQuery } from '@urql/vue';
import TreeTable from 'primevue/treetable';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Column from 'primevue/column';

import 'element-plus/lib/theme-chalk/index.css';

export default defineComponent({
  components: {
    TreeTable,
    Column,
    TabView,
    TabPanel,
  },
  setup() {
    const result = useQuery({
      // language=GraphQL
      query: `
query ($community: String!) {
    organization(
        where: {
            communities: {community_id: {_eq: $community}},
        },
        order_by: [{name: asc}]
    ) {
        attachments
        id
        name
        short_name
        description
        extra
        persons(order_by: [{person: {full_name: asc}}]) {
            position
            person {
                name: full_name
                id
                photo_url
                rating {
                    overall
                }
            }
        }
        persons_aggregate {
            aggregate {
                count
            }
        }
    }
}
      `,
      variables: {
        community: slug,
      },
    });
    const first = computed(() => {
      if (!result.data) {
        return [];
      }
      let tmp = {};
      let count = 0;
      for (let key in result.data.value.organization) {
        if (count > 2) {
          break;
        }
        tmp[result.data.value.organization[key].id] = true;
        count++;
      }
      return tmp;
    });

    const tree = computed(() => {
      if (!result.data) {
        return [];
      }
      let tmp = [];

      for (let item of result.data.value.organization) {
        let children = item.persons.map((sub) => {
          return {
            key: item.id + '-' + sub.person.id,
            data: {
              id: sub.person.id,
              name: sub.person.name,
              position: sub.position,
              photo_url: sub.person.photo_url,
              rating: sub.person.rating ? sub.person.rating.overall : 0,
            },
          };
        });
        tmp.push({
          key: item.id,
          type: 'parent',
          data: {
            name: item.short_name,
            count: item.persons_aggregate.aggregate.count,
          },
          children,
        });
      }
      return tmp;
    });
    const active1 = ref(0);

    return {
      fetching: result.fetching,
      data: result.data,
      tree,
      first,
      active1,
      error: result.error,
    };
  },
  components: {
    ElTabs,
    ElTabPane,
  },
});
</script>

<style scoped>
.p-treetable .p-treetable-tbody > tr > td {
  padding: 0 !important;
}

.grid-image {
  width: 40px;
  height: 40px;
  border-radius: 40px;
  overflow: hidden;
}

.grid-image img {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 40px;
}
.rating-bntu-title {
  padding-left: 0;
  margin-top: 0;
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  color: #000000;
  text-transform: uppercase;
}
table {
  border-collapse: separate;
  border-spacing: 0 1em;
}
.rating-name {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  min-height: 40px;
  padding-left: 10px;
}

.rating-name a {
  color: black;
}
.rating-name a:hover {
  color: #d32121;
}

@media screen and (max-width: 560px) {
  .rating-bntu-title {
    margin-left: 0;
    margin-bottom: 10px;
  }
  .rating-wrapper {
    margin-top: 60px !important;
    padding: 10px 0 0 0 !important;
  }
  .is-two-thirds {
    padding: 0 0 0 10px !important;
  }
  .columns {
    margin: 0 !important;
  }
}
@media screen and (max-width: 350px) {
  .word-break {
    word-break: break-all;
  }
}
</style>
