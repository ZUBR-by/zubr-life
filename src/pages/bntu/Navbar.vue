<template>
  <div class="bntu-navbar">
    <div class="bntu-navbar-wrapper">
      <a href="/" class="navbar-img-link">
        <img class="navbar-img" src="/imgs/bntu/logo.jpg" alt="bntu-logo" />
      </a>

      <div class="navbar-bntu-links">
        <template v-for="(route, i) of routes" :key="route.name">
          <a
            class="navbar-bntu-link"
            target="_blank"
            href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
            v-if="i === routes.length - 2"
          >
            Репозиторий
          </a>
          <router-link :to="{ name: route.name }" class="navbar-bntu-link">
            {{ route.label }}
          </router-link>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { routes } from "../../router";

export default {
  data() {
    return {
      hidden: false,
      routes: routes.filter((i) => i.label),
    };
  },
  computed: {
    flag() {
      const map = {
        loshitsa: ["0.png", "1.png", "2.png"],
        vitebsk: ["1.jpg"],
        bntu: ["0.jpg"],
      };

      return (
        "/imgs/" +
        slug +
        "/" +
        map[slug][Math.floor(Math.random() * map[slug].length)]
      );
    },
  },
  mounted() {
    let telegramScript = document.createElement("script");
    telegramScript.setAttribute(
      "src",
      "https://telegram.org/js/telegram-widget.js?14"
    );
    telegramScript.setAttribute("async", "");
    telegramScript.setAttribute(
      "data-telegram-login",
      "zubr_life_" + slug + "_bot"
    );
    telegramScript.setAttribute("data-userpic", "false");
    telegramScript.setAttribute("data-size", "medium");
    telegramScript.setAttribute(
      "data-auth-url",
      import.meta.env.VITE_TELEGRAM_AUTH_URL
    );
    // document.getElementById("widget").appendChild(telegramScript)
  },
};
</script>

<style>
.bntu-navbar {
  width: 100%;
  height: 110px;
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
  background: #fff;
  z-index: 20;
}

.bntu-navbar-wrapper {
  width: 100%;
  height: 100%;
  max-width: 1480px;
  padding: 0 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.navbar-img-link {
  all: initial;
  display: block;
  width: 140px;
  height: 90px;
  cursor: pointer;
  filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.1));
  transition: all 0.4s ease;
}
.navbar-img-link:hover {
  transform: scale(1.1);
}

.navbar-bntu-links {
  display: flex;
  align-items: center;
  height: 100%;
}

.navbar-bntu-link {
  all: initial;
  font-family: Fira Sans;

  font-weight: 500;
  font-size: 22px;
  line-height: 26px;
  cursor: pointer;
  color: #000000;
  transition: all 0.4s ease;
}
.navbar-bntu-link:not(:last-child) {
  margin-right: 35px;
}
.navbar-bntu-link:hover {
  color: #d32121;
}

.navbar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
