<template>
  <div class="section zbr-promo">
    <div class="columns is-centered">
      <div class="column is-two-thirds" v-if="data">
        <h3 class="content has-text-weight-bold is-medium pl-3">
          Рейтинг
        </h3>
        <TreeTable :value="tree" :expanded-keys="first">
          <Column field="name"
                  header="Название"
                  :expander="true"
                  class="ml-3"
                  style="font-size: 0.9rem;padding: 0.5rem 0.3rem;min-width: 350px"
          >
            <template #body="{node}">
              <template v-if="node.type">
                <router-link :to="{name: 'organization', params: {id: node.key}}" class="pl-5">
                  {{ node.data.name }}
                </router-link>
              </template>
              <div v-else class="ml-5" style="float: left">
                <div v-if="!node.data.link">
                  <div class="grid-image" style="float:left;">
                    <img :src="node.data.photo_url
                                          ? node.data.photo_url
                                          : 'https://zubr.in/assets/images/user.svg'">
                  </div>
                  <div style="float:left;" class="pl-2 pt-2">
                    <router-link :to="{name: 'person', params: {id: node.data.id}}">
                      {{ node.data.name }}
                    </router-link>
                  </div>
                </div>
                <template v-else>
                  <div class="pt-1">
                    <router-link :to="{name: 'organization', params: {id: node.data.org_id}}">
                      Посмотреть весь состав
                    </router-link>
                  </div>
                </template>
              </div>
            </template>
          </Column>
          <Column style="font-size: 0.9rem;padding: 0.5rem 0.3rem;width: 100px">
            <template #body="{node}">
              <div class="pl-2 pt-3" v-if="!node.type && !node.data.link">
                    <span tabindex="0"
                          class="p-rating-icon pi pi-star"
                          :style="{color: node.data.rating >= 0 ? 'var(--green-600)' : 'var(--pink-500)'}"
                          :class="{'pi-caret-up':  node.data.rating >= 0 , 'pi-caret-down': node.data.rating < 0}"></span>
                &nbsp;{{ node.data.rating }}
              </div>
            </template>
          </Column>
          <Column header="Должность"
                  style="font-size: 0.9rem;padding: 0.5rem 0.3rem">
            <template #body="{node}">
              <div class="pt-3" v-if="!node.type && !node.data.link">
                {{ node.data.position }}
              </div>
            </template>
          </Column>
        </TreeTable>
      </div>
    </div>
  </div>
</template>

<script>

import {defineComponent, computed} from "vue";
import {useQuery} from "@urql/vue";
import TreeTable from 'primevue/treetable';
import Column from 'primevue/column';

export default defineComponent({
  components: {
    TreeTable, Column
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
        description
        extra
        persons(limit: 3, order_by: [{person: {rating: {overall: desc_nulls_last}, full_name: asc}}]) {
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
        if (item.persons_aggregate.aggregate.count > 3) {
          children.push({
            key: item.id + '-more',
            data: {
              link: true,
              org_id: item.id
            }
          })
        }
        tmp.push({
          key: item.id,
          type: 'parent',
          data: {
            name: item.name,
            count: item.persons_aggregate.aggregate.count
          },
          children
        })
      }
      return tmp;
    })
    return {
      fetching: result.fetching,
      data: result.data,
      tree,
      first,
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

