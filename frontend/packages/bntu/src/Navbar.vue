<template>
  <div class="bntu-navbar">
    <div class="bntu-navbar-wrapper">
      <div class="bntu-nav-img-wrapper">
        <a href="/" class="navbar-img-link">
          <img
            class="navbar-img"
            src="/imgs/bntu/logo.jpg"
            alt="bntu-logo"
            width="140"
            height="90"
          />
        </a>
        <a id="widget"></a>
      </div>

      <div class="navbar-bntu-links">
        <template v-for="(route, i) of routes" :key="route.name">
          <a
            class="navbar-bntu-link"
            target="_blank"
            href="https://drive.google.com/drive/folders/1MReZiH6w3LkclE3cK_RhDtI9W7Gx3SG4?usp=sharing"
            v-if="i === routes.length - 2"
          >
            Репозиторий
          </a>
          <router-link :to="{ name: route.name }" class="navbar-bntu-link">
            {{ route.label }}
          </router-link>
        </template>
      </div>
      <div class="navbar-bntu-links-mobile">
        <div
          class="navbar-bntu-links-mobile-burger-wrapper"
          v-on:click="myFilter()"
          v-bind:class="{ active: isActive }"
        >
          <div class="navbar-bntu-links-mobile-burger"></div>
        </div>

        <div
          class="navbar-bntu-links-mobile-wrapper"
          v-bind:class="{ active: isActive }"
        >
          <template v-for="(route, i) of routes" :key="route.name">
            <a
              class="navbar-bntu-link"
              target="_blank"
              href="https://drive.google.com/drive/folders/1J9NNrlqAKeDWA2a0bLqsvDk7nBmTEX-D"
              v-if="i === routes.length - 2"
              v-on:click="myFilter()"
            >
              Репозиторий
            </a>
            <router-link
              :to="{ name: route.name }"
              class="navbar-bntu-link"
              v-on:click="myFilter()"
            >
              {{ route.label }}
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { routes } from './router';

export default {
  data() {
    return {
      hidden: false,
      routes: routes.filter((i) => i.label),
      isActive: false,
    };
  },
  methods: {
    myFilter: function () {
      this.isActive = !this.isActive;
    },
  },
  computed: {
    flag() {
      const slug = 'bntu';
      const map = {
        bntu: ['0.jpg'],
      };

      return (
        '/imgs/' +
        slug +
        '/' +
        map[slug][Math.floor(Math.random() * map[slug].length)]
      );
    },
  },
  mounted() {
    let telegramScript = document.createElement('script');
    telegramScript.setAttribute(
      'src',
      'https://telegram.org/js/telegram-widget.js?14'
    );
    telegramScript.setAttribute('async', '');
    telegramScript.setAttribute('data-telegram-login', 'zubr_life_bntu_bot');
    telegramScript.setAttribute('data-size', 'small');
    telegramScript.setAttribute(
      'data-auth-url',
      import.meta.env.VITE_TELEGRAM_AUTH_URL
    );
    document.getElementById('widget').appendChild(telegramScript);
  },
};
</script>

<style>
#widget {
  padding: 0 !important;
  margin-left: 10px;
}
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
.bntu-nav-img-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
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
  transform: scale(1.03);
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
  object-fit: cover;
}

.navbar-bntu-links-mobile {
  display: none;
}
.router-link-active {
  color: #d32121 !important;
}
@media screen and (max-width: 1080px) {
  .router-link-active {
    color: black !important;
  }
  .navbar-bntu-links {
    display: none;
  }

  .navbar-bntu-links-mobile {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .navbar-bntu-links-mobile-burger-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 32px;
    cursor: pointer;
    z-index: 2;
  }

  .navbar-bntu-links-mobile-burger-wrapper.active
    .navbar-bntu-links-mobile-burger {
    transform: rotate(45deg);
  }

  .navbar-bntu-links-mobile-burger-wrapper.active
    .navbar-bntu-links-mobile-burger::before {
    transform: rotate(-90deg);
    top: 0;
    opacity: 1;
  }

  .navbar-bntu-links-mobile-burger-wrapper.active
    .navbar-bntu-links-mobile-burger::after {
    opacity: 0;
    transform: rotate(-90deg);
    top: 0;
  }

  .navbar-bntu-links-mobile-burger {
    width: 100%;
    height: 3px;
    background: #000000;
    position: relative;
    transition: all 0.4s ease;
  }

  .navbar-bntu-links-mobile-burger::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 3px;
    background: #000000;
    top: 10px;
    left: 0;
    transition: all 0.4s ease;
  }

  .navbar-bntu-links-mobile-burger::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 3px;
    background: #000000;
    top: -10px;
    left: 0;
    transition: all 0.4s ease;
  }

  .navbar-bntu-links-mobile-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: absolute;
    top: 0;
    left: -100%;
    width: 50vw;
    height: 100vh;
    background: #d32121;
    z-index: 1;
    transition: all 0.5s ease;
  }

  .navbar-bntu-links-mobile-wrapper.active {
    top: 0;
    left: 0;
  }

  .navbar-bntu-link {
    color: #fff;
    margin-right: 0;
    margin-bottom: 40px;
  }

  .navbar-bntu-link:not(:last-child) {
    margin-right: 0;
  }

  .navbar-bntu-link:first-child {
    margin-top: 30px;
  }

  .navbar-bntu-link:hover {
    color: #fff;
  }
}

@media screen and (max-width: 560px) {
  .bntu-navbar {
    height: 60px;
  }

  .navbar-bntu-links-mobile-burger-wrapper {
    width: 40px;
  }

  .navbar-bntu-links-mobile-wrapper {
    width: 70vw;
  }

  .navbar-img-link {
    width: 70px;
    height: 45px;
  }
}

@media screen and (max-width: 480px) {
  .navbar-bntu-links-mobile-wrapper {
    width: 80vw;
  }
}
</style>
