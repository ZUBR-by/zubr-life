<template>
  <div class="section zbr-promo">
    <div class="columns is-centered">
      <div class="column is-half" v-if="data">
        <h3 class="content has-text-weight-bold is-medium pl-3">
          Рейтинг
        </h3>
        <TreeTable :value="tree" :expanded-keys="first">
          <Column field="name"
                  header="Название"
                  :expander="true"
                  class="ml-3"
                  style="font-size: 0.9rem;padding: 0.5rem 0.3rem"
          >
            <template #body="{node}">
              <template v-if="node.type">
                <router-link :to="{name: 'organization', params: {id: node.key}}" class="pl-5">
                  {{ node.data.name }}({{ node.data.count }})
                </router-link>
              </template>
              <div v-else class="ml-5" style="float: left">
                <template v-if="!node.data.link">
                  <div class="grid-image" style="float: left">
                    <img :src="node.data.photo_url
                                          ? node.data.photo_url
                                          : 'https://zubr.in/assets/images/user.svg'">
                  </div>
                  <div class="pl-1 pt-3" style="float:left;">
                    <router-link :to="{name: 'person', params: {id: node.data.id}}">
                      {{ node.data.name }}
                    </router-link>
                  </div>
                  <div class="pl-2 pt-3" style="float: left">
                    <span tabindex="0" class="p-rating-icon pi pi-star"></span>
                    &nbsp;0
                  </div>
                </template>
                <template v-else>
                  <div class="pt-1">
                    <router-link :to="{name: 'organization', params: {id: node.data.org_id}}">
                      Посмотреть всех
                    </router-link>
                  </div>
                </template>
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
        order_by: [{persons_aggregate: {count: desc}}]
    ) {
        attachments
        id
        name
        description
        extra
        persons(limit: 3, order_by: [{person: {full_name: asc}}]) {
            person {
                name: full_name
                id
                photo_url
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
        if (count > 1) {
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
              photo_url: sub.person.photo_url
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

