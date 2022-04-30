<template>
  <div id="page-container" :class="classContainer">
    <!-- Page Loader -->
    <div
      id="page-loader"
      :class="{ show: $store.state.settings.pageLoader }"
    ></div>
    <!-- END Page Loader -->

    <!-- Page Overlay -->
    <div
      id="page-overlay"
      v-if="
        $store.state.layout.sideOverlay && $store.state.settings.pageOverlay
      "
      @click="() => $store.commit('sideOverlay', { mode: 'close' })"
    ></div>
    <!-- END Page Overlay -->

    <!-- Header -->
    <header
      v-if="$store.state.layout.header"
      :class="layoutClasses.header"
      id="page-header"
    >
      <slot name="header"></slot>
    </header>

    <!-- END Header -->

    <!-- Main Container -->
    <div id="main-container">
      <slot name="content"></slot>
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
    <!-- END Main Container -->
  </div>
</template>

<style lang="scss">
// Custom router view transition
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
    // Set default color theme
    this.$store.commit('setColorTheme', {
      theme: this.$store.getters.appColorTheme,
    })
  },
  mounted() {
    let winResize = false

    // Remove side transitions on window resizing
    window.addEventListener('resize', () => {
      clearTimeout(winResize)

      this.$store.commit('setSideTransitions', { transitions: false })

      winResize = setTimeout(() => {
        this.$store.commit('setSideTransitions', { transitions: true })
      }, 500)
    })
  },
}
</script>
