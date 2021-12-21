<template>
  <div class="bntu-home">
    <section class="bntu-sections">
      <div class="home-main">
        <div class="main-content">
          <h1 class="main-title">
            Мы — независимое объединение студентов и работников Беларусского
            национального технического университета, которые решили запустить
            альтернативный сайт БНТУ
          </h1>
          <span class="main-text">
            Мы отличаемся тем, что публикуем честные новости, размещаем полезную
            информацию для студентов в разделе «Репозиторий» и неравнодушием к
            проблемам университета.
          </span>
          <div class="buttons">
            <a
              href="https://t.me/bntu97_bot"
              target="_blank"
              class="bntu-button bntu-button-main"
            >
              Рассказать о проблеме
            </a>
          </div>
        </div>
        <div class="main-content-bottom">
          <span class="main-text">
            Мы отличаемся тем, что публикуем честные новости, размещаем полезную
            информацию для студентов в разделе «Репозиторий» и неравнодушием к
            проблемам университета.
          </span>
          <div class="buttons">
            <a
              href="https://t.me/bntu97_bot"
              target="_blank"
              class="bntu-button bntu-button-main"
            >
              Рассказать о проблеме
            </a>
          </div>
        </div>
      </div>
      <div class="home-boxes">
        <div class="home-boxes-wrapper">
          <a
            href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
            target="_blank"
            class="home-box home-box-repository"
          >
            <div class="home-box-background home-box-repository"></div>
            <div class="home-box-main-text">Репозиторий</div>
            <div class="home-box-main-secondary">
              Здесь собрана полезная литература, курсы, ссылки на информационные
              ресурсы, а также готовые контрольные, лабораторные, курсовые
              проекты для студентов всех факультетов.<br /><br />
              Периодически мы будем обновлять репозиторий, для этого нам
              необходима ваша помощь: если вам есть чем поделиться состудентами
              своей специальности — присылайте анонимно файл в наш телеграм-бот.
            </div>
          </a>
          <router-link class="home-box" :to="'/rating'">
            <div class="home-box-main-text">Люди</div>
            <div class="home-box-background home-box-people"></div>
            <div class="home-box-main-secondary">
              Здесь собрана информация о преподавателях и других работниках
              университета. Тут можно узнать: что требует тот или иной
              преподаватель от студентов, ведет ли учет посещаемости и как
              относится к студентам.<br /><br />
              Под описанием каждый может оставить отзыв о работнике, поставить
              ему оценку, что сформирует рейтинг. Мы должны знать героев в лицо!
              Если вы хотите добавить своего преподавателя в раздел “Люди” —
              напишите в наш телеграм-бот.
            </div>
          </router-link>
          <router-link class="home-box" :to="'/'">
            <div class="home-box-main-text">Проблемы</div>
            <div class="home-box-background home-box-problems"></div>
            <div class="home-box-main-secondary">
              Здесь мы рассказываем о проблемах в университете от лица студентов
              и работников БНТУ, а также путях их решения.<br /><br />
              Каждый может написать комментарий под проблемой, чтобы повысить ее
              значимость. Анонимно рассказать о проблеме можно в нашем
              телеграм-боте.
            </div>
          </router-link>
        </div>
        <div class="home-boxes-wrapper-mobile">
          <div class="home-box-mobile">
            <div class="home-box-mobile-photo home-box-mobile-photo-repository">
              Репозиторий
            </div>
            <p class="home-box-mobile-text">
              <span
                >Здесь собрана полезная литература, курсы, ссылки на
                информационные ресурсы, а также готовые контрольные,
                лабораторные, курсовые проекты для студентов всех
                факультетов.</span
              >
              <br /><br />
              <span
                >Периодически мы будем обновлять репозиторий, для этого нам
                необходима ваша помощь: если вам есть чем поделиться
                состудентами своей специальности — присылайте анонимно файл в
                наш телеграм-бот.</span
              >
            </p>
            <a
              href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
              target="_blank"
              class="home-box-mobile-button"
              >Перейти</a
            >
          </div>
          <span class="home-box-mobile-span"
            >Периодически мы будем обновлять репозиторий, для этого нам
            необходима ваша помощь: если вам есть чем поделиться состудентами
            своей специальности — присылайте анонимно файл в наш
            телеграм-бот.</span
          >
          <div class="home-box-mobile">
            <div class="home-box-mobile-photo home-box-mobile-photo-people">
              Люди
            </div>
            <p class="home-box-mobile-text">
              <span
                >Здесь собрана информация о преподавателях и других работниках
                университета. Тут можно узнать: что требует тот или иной
                преподаватель от студентов, ведет ли учет посещаемости и как
                относится к студентам.</span
              >
              <br /><br />
              <span
                >Под описанием каждый может оставить отзыв о работнике,
                поставить ему оценку, что сформирует рейтинг. Мы должны знать
                героев в лицо! Если вы хотите добавить своего преподавателя в
                раздел “Люди” — напишите в наш телеграм-бот.</span
              >
            </p>
            <router-link :to="'/rating'" class="home-box-mobile-button"
              >Перейти
            </router-link>
          </div>
          <span class="home-box-mobile-span"
            >Под описанием каждый может оставить отзыв о работнике, поставить
            ему оценку, что сформирует рейтинг. Мы должны знать героев в лицо!
            Если вы хотите добавить своего преподавателя в раздел “Люди” —
            напишите в наш телеграм-бот.</span
          >
          <div class="home-box-mobile">
            <div class="home-box-mobile-photo home-box-mobile-photo-problems">
              Проблемы
            </div>
            <p class="home-box-mobile-text">
              <span
                >Здесь мы рассказываем о проблемах в университете от лица
                студентов и работников БНТУ, а также путях их решения.</span
              >
              <br /><br />
              <span
                >Каждый может написать комментарий под проблемой, чтобы повысить
                ее значимость. Анонимно рассказать о проблеме можно в нашем
                телеграм-боте.</span
              >
            </p>
            <a
              href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
              target="_blank"
              class="home-box-mobile-button"
              >Перейти</a
            >
          </div>
          <span class="home-box-mobile-span"
            >Каждый может написать комментарий под проблемой, чтобы повысить ее
            значимость. Анонимно рассказать о проблеме можно в нашем
            телеграм-боте.</span
          >
        </div>
      </div>
      <div class="bntu-news">
        <div class="bntu-news-wrapper">
          <h2 class="bntu-news-title">Новости</h2>
          <div class="bntu-news-contents" v-if="data && data.activities">
            <div
              class="bntu-news-content"
              v-for="item of data.activities"
              v-bind:key="item"
            >
              <div class="bntu-news-content-wrapper">
                <template v-if="item.attachments">
                  <template
                    v-for="src of item.attachments.slice(0, 1)"
                    :key="src"
                  >
                    <img :src="src.url" class="bntu-news-content-img" />
                  </template>
                </template>
                <span class="bntu-news-content-title">
                  {{ item.title }}
                </span>
                <template v-if="!item.attachments.length">
                  <span class="bntu-news-content-description">
                    {{ item.description.replace(item.title, "") }}
                  </span>
                </template>
                <template v-else>
                  <span class="bntu-news-content-description-img">
                    {{ item.description.replace(item.title, "") }}
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
        <router-link class="bntu-button-news bntu-button-empty" :to="'/feed'"
          >К другим новостям
        </router-link>
        <div class="bntu-news-line"></div>
      </div>
      <div class="bntu-zubr">
        <h3 class="bntu-zubr-title">ПРИ ПОДДЕРЖКЕ ZUBR</h3>
        <a href="https://t.me/zubr_in" target="_blank" class="bntu-zubr-link">
          <img src="/imgs/icons/zubr.svg" alt="zubr.in" />
        </a>
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
                  >тг
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
                    <img src="/imgs/bntu/inst.png" alt="instagram bntu97" />
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
                    />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="bntu-footer">
        <span class="bntu-footer-text">© 2021 Все права защищены</span>
      </footer>
    </section>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useQuery } from "@urql/vue";
import { formatDate } from "@zubr-life/main/src/date";

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
        attachments
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
  position: relative;
  font-family: Fira Sans sans-serif !important;
  margin-top: 110px;
}

.bntu-sections {
  display: flex;
  flex-direction: column;
}

.home-main {
  width: 100%;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: calc(100vh - 110px);
  height: calc(100vh - 110px);
  background: url("/imgs/bntu/landing_back.png");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.main-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
  max-width: 1480px;
  padding: 0 20px;
  margin-top: -20px;
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
  margin-top: 0;
}

.main-text {
  max-width: 960px;
  font-weight: 500;
  font-size: 22px;
  line-height: 26px;
  color: #ffffff;
  margin-bottom: 40px;
  text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
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
  border: 2px solid #d32121;
  margin-right: 36px;
  transition: all 0.4s ease;
}

.bntu-button-empty {
  background: transparent;
  font-family: Fira Sans;
  font-style: normal;
  font-weight: 900;
  font-size: 18px;
  line-height: 22px;
  /* identical to box height */

  color: #ffffff;
  border: none;
  transition: all 0.4s ease;
  text-decoration: none;
}

.bntu-button-empty:hover {
  text-decoration: underline;
}

.bntu-button-main:hover {
  background: #f50909;
  border: 2px solid #ffffff;
}

.bntu-button-secondary {
  background: transparent;
  border: 2px solid #ffffff;
  transition: all 0.6s ease;
}

.bntu-button-secondary:hover {
  background: #ffffff;
  color: #000;
}

.home-boxes {
  margin-top: 140px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.home-boxes-wrapper {
  width: 100%;
  height: 380px;
  max-width: 1480px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.home-box {
  height: 100%;
  width: calc(100% / 3);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  cursor: pointer;
  transition: all 0.4s ease;
  z-index: 2;
}

.home-box-main-text {
  opacity: 1;
  text-align: center;
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 30px;
  line-height: 36px;
  color: #ffffff;
  text-transform: uppercase;
  text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  transition: all 0.4s ease;
}

.home-box:hover .home-box-main-text {
  opacity: 0;
}

.home-box:hover {
  filter: brightness(77%);
}

.home-box-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  filter: brightness(100%);
  z-index: -1;
  transition: all 0.4s ease;
}

.home-box-main-secondary {
  position: absolute;
  width: 90%;
  text-align: center;
  align-items: center;
  opacity: 0;
  font-family: Fira Sans;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #ffffff;
  transition: all 0.4s ease;
  z-index: 10;
}

.home-box:hover .home-box-background {
  filter: brightness(65%);
}

.home-box:hover .home-box-main-secondary {
  opacity: 1;
}

.home-box-repository {
  background: url("/imgs/bntu/repository.png");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.home-box-people {
  background: url("/imgs/bntu/people.png");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.home-box-problems {
  background: url("/imgs/bntu/problems.png");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.home-boxes-wrapper-mobile {
  display: none;
}

.bntu-news {
  padding: 115px 0 90px 0;
  width: 100%;
  margin-bottom: 115px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.bntu-news-line {
  position: absolute;
  width: 220px;
  height: 100%;
  background: #d32121;
  top: 50%; /* position the top  edge of the element at the middle of the parent */
  left: 50%; /* position the left edge of the element at the middle of the parent */
  border-radius: 0 0 15px 15px;
  transform: translate(-50%, -50%);
  z-index: -2;
  transition: all 0.4s ease;
}

.bntu-news-wrapper {
  width: 100%;
  max-width: 1480px;
  padding: 0 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.bntu-news-contents {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap;
}

.bntu-news-title {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  text-transform: uppercase;
  color: #ffffff;
  margin-bottom: 80px;
}

.bntu-news-content-description {
  font-family: Roboto;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 145%;
  color: #000000;
  text-overflow: ellipsis;
}

.bntu-news-contents {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 60px;
}

.bntu-news-content {
  text-decoration: none;
  width: 30%;
  height: 450px;
  background: #ffffff;
  filter: drop-shadow(0px 0px 20px rgba(0, 0, 0, 0.3));
  border-radius: 15px;
  padding: 25px 25px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
}
.bntu-news-content-wrapper {
  max-height: 90%;
  overflow-y: auto;
}
.bntu-news-content-wrapper::-webkit-scrollbar {
  width: 10px;
  background-color: #f9f9fd;
}

.bntu-news-content-wrapper::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background-color: #d32121;
}

.bntu-news-content-wrapper::-webkit-scrollbar-track {
  box-shadow: inset 0px 0px 6px rgba(0, 0, 0, 0.2);
  border-radius: 10px;
  background-color: #f9f9fd;
}
.bntu-news-content-img {
  width: 100%;
  height: 50%;
  object-fit: cover;
  object-position: top;
  border-radius: 10px;
  margin-bottom: 20px;
}

.bntu-news-content:hover {
  transform: scale(1.03);
}

.bntu-news-content-title {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: 500;
  font-size: 22px;
  line-height: 26px;
  text-transform: uppercase;
  color: #000000;
  margin-bottom: 20px;
}

.bntu-news-content-description {
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 6;
  -webkit-box-orient: vertical;
  margin-top: 20px;
}
.bntu-news-content-description-img {
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  margin-top: 20px;
}

.bntu-news-bottom {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.bntu-news-bottom-more {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: 500;
  font-size: 18px;
  line-height: 22px;
  text-decoration-line: underline;
  color: #d32121;
  transition: all 0.4s ease;
}

.bntu-news-bottom-more:hover {
  text-decoration-line: none;
}

.bntu-button-news:hover + .bntu-news-line {
  border-radius: 0 0 0 0;
  height: calc(120% + 90px);
}

.bntu-zubr {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-bottom: 35px;
}

.bntu-zubr-title {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  color: #828282;
  text-transform: uppercase;
}

.bntu-zubr-link {
  transition: all 0.4s ease;
}

.bntu-zubr-link:hover {
  transform: scale(1.05);
}

.bntu-zubr-link img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.bntu-contacts {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #383838;
  padding: 50px 0;
}

.bntu-contacts-wrapper {
  width: 100%;
  max-width: 1480px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;
}

.bntu-contacts-title {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  /* identical to box height */

  text-decoration-line: underline;

  color: #ffffff;
  margin: 0;
  margin-bottom: 40px;
}

.bntu-contacts-content {
  display: flex;
  justify-content: space-between;
  align-items: unset;
  width: 100%;
}

.bntu-contacts-stach {
  margin-bottom: 30px;
}

.bntu-contacts-stach-title {
  margin: 0;
  font-family: Fira Sans;
  font-style: normal;
  font-weight: 500;
  font-size: 22px;
  line-height: 26px;

  color: #ffffff;
  margin-bottom: 6px;
}

.bntu-contacts-stach-text,
.bntu-contacts-stach-link {
  font-family: Source Sans Pro;
  font-style: normal;
  font-weight: 400;
  font-size: 20px;
  line-height: 25px;

  color: #ffffff;
  transition: all 0.4s ease;
  text-decoration: none;
}

.bntu-contacts-stach-link:hover {
  color: #d32121;
}

.bntu-contacts-social-links {
  display: flex;
  align-items: center;
  justify-content: flex-start;
}

.bntu-contacts-social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 35px;
  height: 35px;
  cursor: pointer;
  border-radius: 50%;
  background: #ffffff;
  margin-right: 15px;
  transition: all 0.4s ease;
}

.bntu-contacts-social-link:hover {
  transform: scale(1.1);
}

.bntu-footer {
  width: 100%;
  padding: 20px 20px;
  background: #000000;
  text-align: center;
}

.bntu-footer-text {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: 500;
  font-size: 22px;
  line-height: 26px;

  color: #cdcfce;
}

@media screen and (max-width: 1200px) {
  .home-main {
    min-height: auto;
    height: 60vh;
  }

  .main-content {
    align-items: center;
    text-align: center;
  }

  .home-boxes {
    margin-top: 70px;
  }

  .home-boxes-wrapper {
    display: none;
  }

  .home-boxes-wrapper-mobile {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 20px;
  }

  .home-box-mobile {
    width: 60%;
    background: #ffffff;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    display: flex;
    align-items: center;
    text-align: center;
    flex-direction: column;
    justify-content: center;
    padding: 40px;
  }

  .home-box-mobile:not(:last-child) {
    margin-bottom: 50px;
  }

  .home-box-mobile-photo {
    width: 100%;
    height: 200px;
    font-family: Fira Sans;
    font-style: normal;
    font-weight: bold;
    font-size: 30px;
    line-height: 24px;
    border-radius: 5px;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
  }

  .home-box-mobile-photo-repository {
    background: url("/imgs/bntu/repository.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  .home-box-mobile-photo-people {
    background: url("/imgs/bntu/people.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  .home-box-mobile-photo-problems {
    background: url("/imgs/bntu/problems.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  .home-box-mobile-text {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: 500;
    font-size: 25px;
    line-height: 25px;
    text-align: center;
    width: 95%;
    color: #000000;
    margin-bottom: 50px;
    margin-top: 50px;
  }

  .home-box-mobile-button {
    all: unset;
    display: block;
    padding: 15px 60px;
    background: #d32121;
    width: fit-content;
    border-radius: 10px;
    color: #fff;
    font-family: Fira Sans;
    font-style: normal;
    font-weight: 600;
    font-size: 25px;
    line-height: 19px;
  }

  .bntu-news-contents {
    flex-direction: column;
  }

  .bntu-news-content {
    width: 50%;
  }

  .bntu-news-content:not(:last-child) {
    margin-bottom: 50px;
  }
}

.main-content-bottom {
  display: none;
}

.home-box-mobile-span {
  display: none;
}

@media screen and (max-width: 767px) {
  .home-main {
    background: transparent;
    flex-direction: column;
    justify-content: flex-start;
  }

  .main-content {
    background: url("/imgs/bntu/people.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: top;
    margin-top: 0;
    padding: 50px 20px;
  }

  .main-content span {
    display: none;
  }

  .main-content div {
    display: none;
  }

  .main-content-bottom {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    padding: 20px;
  }

  .main-content h1 {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: bold;
    font-size: 35px;
    line-height: 40px;
    text-align: left;
    color: #ffffff;
  }

  .main-content-bottom span {
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: 600;
    font-size: 25px;
    line-height: 30px;
    text-align: center;

    color: #000000;
  }

  .home-boxes {
    margin-top: 20px;
  }

  .home-box-mobile {
    width: 90%;
  }

  .bntu-news-content {
    width: 70%;
  }
}

@media screen and (max-width: 560px) {
  .bntu-home {
    margin-top: 60px;
  }

  .home-main {
    height: auto;
  }

  .bntu-contacts-content {
    flex-direction: column;
    align-items: center;
  }

  .bntu-contacts-stach {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .home-box-mobile-text span:last-child {
    display: none;
  }

  .home-box-mobile:not(:last-child) {
    margin-bottom: 20px;
  }

  .home-box-mobile-span {
    display: block;
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: normal;
    font-size: 25px;
    line-height: 30px;
    text-align: center;
    width: 90%;
    color: #000000;
    margin-bottom: 20px;
  }

  .home-box-mobile-text {
    margin: 20px 0;
  }

  .home-box-mobile {
    padding: 20px;
  }

  .bntu-button-main {
    margin-right: 0;
  }

  .home-boxes {
    margin-top: 40px;
  }

  .bntu-news-content {
    width: 80%;
  }
}

@media screen and (max-width: 480px) {
  .main-content h1 {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: bold;
    font-size: 20px;
    line-height: 24px;
    color: #ffffff;
  }

  .main-content-bottom span {
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    line-height: 20px;
    text-align: center;

    color: #000000;
  }

  .bntu-button {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: 900;
    font-size: 16px;
    line-height: 19px;

    color: #ffffff;
  }

  .home-box-mobile-photo {
    height: 122px;
    font-family: Fira Sans;
    font-style: normal;
    font-weight: bold;
    font-size: 20px;
    line-height: 24px;

    color: #ffffff;
  }

  .home-box-mobile-text {
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: 600;
    font-size: 15px;
    line-height: 19px;
    text-align: center;
    width: 100%;
    color: #000000;
    margin: 10px 0;
  }

  .home-box-mobile-button {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: 900;
    font-size: 16px;
    line-height: 19px;

    color: #ffffff;
  }

  .home-box-mobile-span {
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: normal;
    font-size: 15px;
    line-height: 19px;
    text-align: center;

    color: #000000;
  }

  .bntu-news-content {
    width: 100%;
    min-height: 400px;
  }

  .bntu-news-content-title {
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 20px;

    color: #000000;
  }

  .bntu-news-content-description {
    font-family: Roboto;
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 145%;
    /* or 20px */

    color: #000000;
  }

  .bntu-zubr-title {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: bold;
    font-size: 20px;
    line-height: 24px;
    /* identical to box height */

    color: #828282;
  }

  .bntu-news {
    margin-bottom: 20px;
  }

  .bntu-contacts-title {
    font-family: Fira Sans;
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 19px;
    text-decoration-line: underline;

    color: #ffffff;
  }

  .bntu-contacts-stach-title {
    font-family: Source Sans Pro;
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 20px;

    color: #ffffff;
  }

  .bntu-news-bottom {
    flex-direction: column;
  }
}
</style>
