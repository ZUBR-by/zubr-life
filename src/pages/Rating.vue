<template>
  <div class="section zbr-promo">
    <div class="columns is-centered">
      <div class="column is-half" v-if="data">
        <h3 class="content has-text-weight-bold is-medium pl-3">
          Рейтинг
        </h3>
        <TreeTable :value="tree" >
          <Column field="name"
                  header="Name"
                  :expander="true"
                  class="ml-3"
                  style="font-size: 0.9rem;padding: 0.5rem"
                  >
            <template #body="{node}">
              <template v-if="node.type">
                <router-link :to="{name: 'organization', params: {id: node.key}}" class="pl-5">
                  {{node.data.name}}
                </router-link>
              </template>
              <template v-else>
                <div class="grid-image" style="float: left">
                  <img :src="node.data.photo_url
                                        ? node.data.photo_url
                                        : 'https://zubr.in/assets/images/user.svg'">
                </div>
                <div class="pl-1 pt-3" style="float:left;">{{node.data.name}}</div>
              </template>
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
        persons(limit: 5) {
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
    const tree = computed(() => {
      if (!result.data) {
        return []
      }
      let tmp = []
      for (let item of result.data.value.organization) {

        tmp.push({
          key: item.id,
          type: 'parent',
          data: {
            name: item.name,
          },
          children: item.persons.map((sub) => {
            return {
              key: item.id + '-' + sub.person.id,
              data: {
                name: sub.person.name,
                photo_url: sub.person.photo_url
              }
            }
          })
        })
      }
      console.log(tmp)
      return tmp;
    })
    return {
      fetching: result.fetching,
      data: result.data,
      tree,
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

