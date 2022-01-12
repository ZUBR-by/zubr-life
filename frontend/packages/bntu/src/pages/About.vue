<template>
  <div class="bntu-home">
    <div class="bntu-sections">
      <div class="about-first">
        <div class="about-first-wrapper">
          <img class="about-first-img" src="/about.jpeg" />
          <div class="about-first-content">
            <h2>О нас</h2>
            <p>
              Мы — независимое объединение студентов и работников БНТУ. При
              поддержке ZUBR мы создали альтернативный официальному сайт БНТУ,
              чтобы в открытую рассказывать о проблемах в университете, помогать
              студентам, искать единомышленников и делиться опытом.
            </p>
            <p>
              На нашем сайте вы можете найти литературу и ресурсы, которые
              пригодятся в работе по вашей специальности, выставлять рейтинг
              преподавателям, комментировать и рассказывать о проблемах, читать
              честные новости, присоединятся к тем, кто готов бороться с
              несправедливостью в Беларуси и БНТУ.
            </p>
          </div>
        </div>
      </div>
      <div class="about-second">
        <div class="about-second-wrapper">
          <div class="about-second-content">
            <h3 class="about-second-title">
              Мы всегда нуждаемся в инициативных ребятах, которые готовы
              работать вместе с нами и разделают наши цели. Пишите нам, если вы:
            </h3>
            <ul>
              <li>дизайнер</li>
              <li>программист</li>
              <li>фотограф</li>
              <li>маркетолог и сммщик</li>
              <li>умеете или хотите писать тексты</li>
              <li>готовы обновлять информацию на нашем сайте</li>
              <li>у вас есть идеи, как сделать наш сайт лучше</li>
            </ul>
          </div>
          <div class="about-second-help">
            <img src="/mac.png" alt="help" class="about-second-img" />
            <p>
              Присоединиться к команде можно из любой точки мира. После
              верификации админами вы сможете работать анонимно. Чтобы
              присоединиться к нашей команде — заполните форму
            </p>
            <p>Чтобы присоединиться к нашей команде — заполните форму</p>
            <button class="bntu-button bntu-button-main about-button">
              Перейти
            </button>
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
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.about-first,
.about-second {
  margin-top: 100px;
  margin-bottom: 120px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.about-first-wrapper,
.about-second-wrapper {
  width: 100%;
  max-width: 1480px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.about-first-img {
  width: 50%;
  object-fit: cover;
}
.about-first-content {
  width: 45%;
}
.about-first-content h2 {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: bold;
  font-size: 34px;
  line-height: 41px;
  color: #000000;
  text-transform: uppercase;
  margin-bottom: 40px;
}
.about-first-content p {
  font-family: Source Sans Pro;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 20px;

  color: #383838;
}

.about-second {
  background: #828282;
  margin-top: 0;
}
.about-second-content {
  width: 40%;
}
.about-second-content ul {
  padding-left: 0;
}
.about-second-content ul li {
  font-family: Roboto;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 145%;
  /* or 20px */

  color: #ffffff;
}

.about-second-content ul li:before {
  content: '\002D';
  padding-right: 6px;
  font-size: 16px;
  color: white;
  font-weight: bold;
}
.about-second-wrapper {
  padding: 30px 20px;
}
.about-second-help {
  width: 710px;
  position: relative;
  height: 400px;
  margin-bottom: -40px;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;
  padding-bottom: 70px;
}
.about-second-help img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}
.about-second-help p,
.about-second-help button {
  position: relative;
  z-index: 3;
}
.about-second-help p {
  width: 65%;
  font-family: Fira Sans;
  color: #ffffff;
  font-style: normal;
  font-weight: 500;
  font-size: 18px;
  line-height: 24px;
  text-align: center;
  margin: 0;
  margin-bottom: 15px;
}
.about-second-title {
  font-family: Fira Sans;
  font-style: normal;
  font-weight: 500;
  font-size: 22px;
  line-height: 26px;

  color: #ffffff;
}
.about-button {
  margin-right: 0;
}

@media screen and (max-width: 1090px) {
  .about-first {
    margin-top: 40px;
  }
  .about-first-wrapper {
    flex-direction: column-reverse;
  }
  .about-first-img,
  .about-first-content {
    width: 80%;
  }
  .about-second-wrapper {
    flex-direction: column;
  }
  .about-second-content {
    width: 540px;
  }
}
@media screen and (max-width: 768px) {
  .about-first {
    margin: 20px 0;
  }
  .about-first-content,
  .about-first-img {
    width: 100%;
  }
  .about-second-help {
    width: 100%;
    height: auto;
  }
  .about-second-help img {
    display: none;
  }
  .about-second-content,
  .about-second-help {
    width: 80%;
  }
  .about-second-help p {
    text-align: left;
    width: 100%;
  }
}
@media screen and (max-width: 480px) {
  .about-first-content h2 {
    margin: 10px 0;
  }
  .about-second-wrapper {
    padding-top: 10px;
    padding-bottom: 10px;
  }
  .about-second {
    margin-bottom: 0;
  }
  .about-second-content,
  .about-second-help {
    width: 100%;
  }
}
</style>
