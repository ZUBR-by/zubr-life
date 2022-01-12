<template>
  <div class="section zbr-promo person-wrapper">
    <div class="columns is-centered">
      <div class="column is-two-thirds card-person">
        <div class="panel">
          <nav
            class="breadcrumb pt-5 pl-3 is-medium nav-person"
            aria-label="breadcrumbs"
          >
            <ul>
              <li>
                <router-link :to="{ name: 'rating' }">
                  <button class="button is-primary is-inverted">
                    <span class="icon is-small">
                      <i class="fas fa-arrow-left"></i>
                    </span>
                    <span>Люди</span>
                  </button>
                </router-link>
              </li>
            </ul>
          </nav>
          <hr />
          <template v-if="data">
            <div class="columns pl-3 person-info">
              <div class="column pl-5">
                <div class="person-photo">
                  <el-image
                    fit="cover"
                    :src="
                      data.person.photo_url
                        ? data.person.photo_url
                        : '/imgs/user.svg'
                    "
                    :preview-src-list="[
                      data.person.photo_url
                        ? data.person.photo_url
                        : '/imgs/user.svg',
                    ]"
                  ></el-image>
                </div>
              </div>
              <div class="column is-four-fifths">
                <h3 class="is-size-3">{{ data.person.full_name }}</h3>
                <rating
                  :id="data.person.id"
                  @change="refresh"
                  v-model="data.person.rating"
                ></rating>
                <div v-if="links" class="pt-4">
                  <ul>
                    <li v-for="link of links" :key="link.value">
                      <a :href="link.value" target="_blank">
                        {{ link.name ? link.name : link.value }}
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="pt-4">
                  {{ data.person.description }}
                </div>

                <div>
                  <p
                    v-for="item of data.person.organizations"
                    :key="item.organization.id"
                    style="text-transform: uppercase"
                  >
                    {{ item.position }}
                    <template v-if="item.extra.department">
                      ({{ item.extra.department }})
                    </template>
                    в
                    <router-link
                      :to="{
                        name: 'organization',
                        params: { id: item.organization.id },
                      }"
                      class="in-organisation"
                    >
                      {{ item.organization.name }}
                    </router-link>
                  </p>
                </div>
                <template v-if="!!data.person.reviews.length">
                  <ul class="pt-4" id="v-for-object" style="padding-left: 0">
                    <template
                      v-for="(value, name) in data.person.reviews[0].info"
                    >
                      <li
                        :key="value"
                        v-if="!!value"
                        style="margin-bottom: 10px"
                      >
                        <span style="font-weight: bold">{{ name }}:</span>
                        {{ value }}
                      </li>
                    </template>
                  </ul>
                </template>
              </div>
            </div>
            <div class="pl-5 pt-3 pb-4 pr-5" style="min-height: 300px">
              <el-tabs v-model="activeName">
                <el-tab-pane
                  label="Комментарии"
                  name="comments"
                  v-if="data.person.id"
                >
                  <comments :id="data.person.id" :type="'person'"></comments>
                </el-tab-pane>
              </el-tabs>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ElTabPane, ElTabs, ElCard, ElImage } from 'element-plus';
import 'element-plus/lib/theme-chalk/index.css';
import Comments from '../components/comments.vue';
import Rating from '../components/rating.vue';
import { useQuery } from '@urql/vue';
import { useRoute } from 'vue-router';
import { computed, watch } from 'vue';

export default {
  setup() {
    const result = useQuery({
      // language=GraphQL
      query: `
query ($id: Int!, $community: String!) {
    person: person_by_pk(id: $id) {
        attachments
        id
        full_name
        photo_url
        description
        extra
        reviews {
            info
            created_at
            author
            id
        }
        rating {
            overall
            downvotes
            upvotes
            is_upvoted
            is_downvoted
        }
        organizations: organizations(where: {organization: {
            communities: {community_id: {_eq: $community}}
        }}) {
            position
            extra
            organization {
                id
                name
            }
        }
    }
}
      `,
      variables: {
        id: useRoute().params.id,
        community: slug,
      },
    });
    const links = computed(() => {
      if (!result.data || !result.data.person) {
        return [];
      }
      return result.data.person.attachments.filter((i) => i.type === 'link');
    });
    const photo = computed(() => {
      if (
        !result.data ||
        !result.data.person ||
        !result.data.person.photo_url
      ) {
        return '/imgs/user.svg';
      }
      return result.data.person.photo_url;
    });
    watch(result.data, (value) => {
      if (!value.person) {
        return;
      }
      document.title = value.person.full_name + ' - Лошица ZUBR.life';
    });
    console.log(result.data);
    return {
      fetching: result.fetching,
      data: result.data,
      error: result.error,
      activeName: 'comments',
      el: '#v-for-object',
      refresh() {
        result.executeQuery({
          requestPolicy: 'network-only',
        });
      },
      map: null,
      links,
      photo,
    };
  },
  components: {
    Rating,
    Comments,
    ElTabPane,
    ElTabs,
    ElCard,
    ElImage,
  },
};
</script>

<style scoped lang="scss">
.person-photo {
  width: 150px;
  height: 150px;
  border-radius: 150px;
  overflow: hidden;

  img {
    width: 150px;
    border-radius: 150px;
  }
}

.el-image {
  height: 100%;
  width: 100%;
}

.person-image {
  object-fit: cover !important;
}

.nav-person {
  padding-top: 10px !important;
}

.nav-person ul {
  padding-left: 0 !important;
}

.card-person {
  border-radius: 30px;
}

.person-info {
  margin: 0 !important;
  padding: 0 !important;
  width: 100%;
}

.in-organisation {
  color: #d32121 !important;
}

.panel {
  box-shadow: none !important;
}
.person-wrapper {
  padding-top: 0;
}

@media screen and (max-width: 560px) {
  .person-wrapper {
    margin-top: 60px !important;
    padding: 0 !important;
  }
}
</style>
