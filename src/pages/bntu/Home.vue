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
                <span class="bntu-news-content-title">
                  {{ item.title }}
                </span>
                <span class="bntu-news-content-description">
                  {{ item.description.replace(item.title, "") }}
                </span>
              </div>

              <div class="bntu-news-bottom">
                <div class="is-size-7 has-text-grey">
                  {{ formatDate(item.created_at) }}
                </div>
                <router-link
                  :to="{ name: 'activity', params: { id: item.id } }"
                  class="bntu-news-bottom-more"
                  >Читать подробнее</router-link
                >
              </div>
            </div>
          </div>
        </div>
        <router-link class="bntu-button-news bntu-button-empty" :to="'/feed'"
          >К другим новостям</router-link
        >
        <div class="bntu-news-line"></div>
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

.bntu-news {
  padding: 115px 0;
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
  height: calc(120% + 115px);
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
</style>
