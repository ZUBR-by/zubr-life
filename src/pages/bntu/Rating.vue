<template>
  <div class="section zbr-promo">
    <div class="columns is-centered">
      <div class="column is-two-thirds" v-if="data">
        <h3 class="content has-text-weight-bold is-medium pl-3">
          Рейтинг
        </h3>
        <TabView ref="tabview2" v-model:activeIndex="active1">
          <TabPanel :header="item.data.name" v-for="(item) of tree">
            <table>
              <tr v-for="(node) of item.children">
                <td><div class="grid-image">
                  <img :src="node.data.photo_url
                                          ? node.data.photo_url
                                          : '/imgs/user.svg'">
                </div>
                </td>
                <td>
                  <div class="pl-2 pt-2">
                    <router-link :to="{name: 'person', params: {id: node.data.id}}">
                      {{ node.data.name }}
                    </router-link>
                  </div>
                </td>
                <td style="text-align: center">
                  <div class="pl-2 pt-3" v-if="!node.type && !node.data.link">
                    <span tabindex="0"
                          class="p-rating-icon pi pi-star"
                          :style="{color: node.data.rating >= 0 ? 'var(--green-600)' : 'var(--pink-500)'}"
                          :class="{'pi-caret-up':  node.data.rating >= 0 , 'pi-caret-down': node.data.rating < 0}"></span>
                    &nbsp;{{ node.data.rating }}
                  </div>
                </td>
                <td>
                  <div class="pt-3 pl-3" v-if="!node.type && !node.data.link">
                    {{ node.data.position }}
                  </div>
                </td>
              </tr>
            </table>
          </TabPanel>
        </TabView>
      </div>
    </div>
  </div>
</template>

<script>

import {defineComponent, computed, ref} from "vue";
import {useQuery} from "@urql/vue";
import TreeTable from 'primevue/treetable';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Column from 'primevue/column';

export default defineComponent({
  components: {
    TreeTable, Column, TabView, TabPanel
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
            community: slug
          }
        }
    );
    const first = computed(() => {
      if (!result.data) {
        return []
      }
      let tmp = {}
      let count = 0;
      for (let key in result.data.value.organization) {
        if (count > 2) {
          break;
        }
        tmp[result.data.value.organization[key].id] = true
        count++;
      }
      return tmp
    })

    const tree = computed(() => {
      if (!result.data) {
        return []
      }
      let tmp = []

      for (let item of result.data.value.organization) {
        let children = item.persons.map((sub) => {
          return {
            key: item.id + '-' + sub.person.id,
            data: {
              id: sub.person.id,
              name: sub.person.name,
              position: sub.position,
              photo_url: sub.person.photo_url,
              rating: sub.person.rating ? sub.person.rating.overall : 0
            }
          }
        });
        tmp.push({
          key: item.id,
          type: 'parent',
          data: {
            name: item.short_name,
            count: item.persons_aggregate.aggregate.count
          },
          children
        })
      }
      return tmp;
    })
    const active1 = ref(0)
    return {
      fetching: result.fetching,
      data: result.data,
      tree,
      first,
      active1,
      error: result.error
    }
  },
})
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
  border-radius: 40px;
}
</style>

