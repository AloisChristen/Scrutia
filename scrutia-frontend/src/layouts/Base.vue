<template>
  <div id="page-container" :class="classContainer">
    <div
      id="page-loader"
      :class="{ show: $store.state.settings.pageLoader }"
    ></div>
    <div
      id="page-overlay"
      v-if="
        $store.state.layout.sideOverlay && $store.state.settings.pageOverlay
      "
      @click="() => $store.commit('sideOverlay', { mode: 'close' })"
    ></div>
    <header
      v-if="$store.state.layout.header"
      :class="layoutClasses.header"
      id="page-header"
    >
      <slot name="header"></slot>
    </header>
    <div id="main-container">
      <slot name="content"></slot>
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
  </div>
</template>

<style lang="scss">
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>

<script>
export default {
  name: 'BaseLayout',
  props: {
    layoutClasses: Object,
  },
  computed: {
    classContainer() {
      return {
        'enable-page-overlay':
          this.$store.state.layout.sideOverlay &&
          this.$store.state.settings.pageOverlay,
        'page-header-fixed':
          this.$store.state.layout.header &&
          this.$store.state.settings.headerFixed,
        'page-header-dark':
          this.$store.state.layout.header &&
          this.$store.state.settings.headerDark,
        'main-content-boxed':
          this.$store.state.settings.mainContent === 'boxed',
        'main-content-narrow':
          this.$store.state.settings.mainContent === 'narrow',
        'rtl-support': this.$store.state.settings.rtlSupport,
        'side-trans-enabled': this.$store.state.settings.sideTransitions,
        'side-scroll': true,
      }
    },
  },
  created() {
    this.$store.commit('setColorTheme', {
      theme: this.$store.getters.appColorTheme,
    })
  },
}
</script>
