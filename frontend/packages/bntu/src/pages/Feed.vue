<template>
  <div class="news">
    <img
      src="/imgs/news/background.png"
      class="news-background"
      alt="news-background"
    />
    <div class="news-content">
      <h2 class="news-content-title">Новости</h2>
      <div class="bntu-news-contents" v-if="data">
        <div
          class="bntu-news-content"
          v-for="item of data.community_activity"
          v-bind:key="item.id"
        >
          <div class="bntu-news-content-wrapper">
            <template v-if="item.files">
              <template v-for="src of item.files.slice(0, 1)" :key="src">
                <img
                  :src="src.attachment.url"
                  class="bntu-news-content-img"
                  :alt="item.title"
                />
              </template>
            </template>
            <span class="bntu-news-content-title">
              {{ item.title }}
            </span>
            <template v-if="!item.files.length">
              <span class="bntu-news-content-description">
                {{ item.description.replace(item.title, '') }}
              </span>
            </template>
            <template v-else>
              <span class="bntu-news-content-description-img">
                {{ item.description.replace(item.title, '') }}
              </span>
            </template>
          </div>

          <div class="bntu-news-bottom">
            <div class="is-size-7 has-text-grey">
              {{ formatDate(item.created_at) }}
            </div>
            <router-link
              :to="{ name: 'activity', params: { id: item.id } }"
              class="bntu-news-bottom-more"
              >Читать подробнее
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="bntu-contacts">
      <div class="bntu-contacts-wrapper">
        <h3 class="bntu-contacts-title">Контакты</h3>
        <div class="bntu-contacts-content">
          <div class="bntu-contacts-stach-wrapper">
            <div class="bntu-contacts-stach">
              <h4 class="bntu-contacts-stach-title">
                Связь с редакцией и стачкомом:
              </h4>
              <span class="bntu-contacts-stach-text"
                >telegram:
                <a
                  href="https://t.me/bntu97_bot"
                  target="_blank"
                  class="bntu-contacts-stach-link"
                  >@bntu97_bot</a
                ></span
              >
            </div>
            <div class="bntu-contacts-stach">
              <h4 class="bntu-contacts-stach-title">
                По вопросам сотрудничества:
              </h4>
              <span class="bntu-contacts-stach-text"
                >bntubelarus@gmail.com</span
              >
            </div>
          </div>
          <div class="bntu-contacts-social">
            <div class="bntu-contacts-stach">
              <h4 class="bntu-contacts-stach-title">Наши соц сети:</h4>
              <div class="bntu-contacts-social-links">
                <a
                  href="https://instagram.com/bntu97"
                  class="bntu-contacts-social-link"
                  target="_blank"
                >
                  <img
                    src="/imgs/bntu/inst.png"
                    alt="instagram bntu97"
                    width="17"
                    height="17"
                  />
                </a>
                <a
                  target="_blank"
                  href="https://t.me/bntu97"
                  class="bntu-contacts-social-link"
                >
                  <img
                    src="/imgs/bntu/tg.png"
                    alt="telegram bntu97"
                    style="margin-left: -5px"
                    width="22"
                    height="18"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="bntu-footer">
      <span class="bntu-footer-text">© 2022 Все права защищены</span>
    </footer>
  </div>
</template>

<script>
import { useQuery } from '@urql/vue';
import { defineComponent } from 'vue';
import { formatDate } from '../date';

export default defineComponent({
  setup() {
    const result = useQuery({
        // language=GraphQL
        query: `
query ($community: String!) {
    community_activity(
        where: {
            category: {_in: ["PROTEST", "AD", "EVENT", "ART"]}
            communities: {community_id: {_eq: $community}}
        },
        order_by: [{created_at: desc}]
    ) {
        files {
            attachment {
                url
                type: content_type
            }
        }
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

    return {
      fetching: result.fetching,
      data: result.data,
      formatDate,
    };
  },
});
</script>

<style>
.news {
  position: relative;
  margin-top: 110px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.news-background {
  position: fixed;
  top: 110px;
  left: 0;
  width: 100%;
  height: 100vh;
  max-height: 100vh;
  object-fit: cover;
  z-index: -1;
  filter: brightness(50%);
}
.news-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 1480px;
  padding: 30px 20px 30px 20px;
}
.news-content-title {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  color: #ffffff;
  text-transform: uppercase;
  text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  margin-bottom: 85px;
}

@media screen and (max-width: 560px) {
  .news {
    margin-top: 60px;
  }
  .news-content {
    padding: 10px 20px;
  }
  .news-background {
    top: 60px;
  }
}
</style>
