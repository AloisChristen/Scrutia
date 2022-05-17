import Vue from 'vue'
import router from './router'
import App from './App.vue'
import store from './store'
import BootstrapVue from 'bootstrap-vue'
import BaseLayoutModifier from '@/components/BaseLayoutModifier.vue'
import BaseBlock from '@/components/BaseBlock.vue'
import BaseBackground from '@/components/BaseBackground.vue'
import BasePageHeading from '@/components/BasePageHeading.vue'
import BaseNavigation from '@/components/BaseNavigation.vue'
import clickRipple from '@/directives/clickRipple'
import toggleClass from '@/directives/toggleClass'

// Register global plugins
Vue.use(BootstrapVue)

// Register global components
Vue.component(BaseLayoutModifier.name, BaseLayoutModifier)
Vue.component(BaseBlock.name, BaseBlock)
Vue.component(BaseBackground.name, BaseBackground)
Vue.component(BasePageHeading.name, BasePageHeading)
Vue.component(BaseNavigation.name, BaseNavigation)

// Register global directives
Vue.directive('click-ripple', clickRipple)
Vue.directive('toggle-class', toggleClass)

// Disable tip shown in dev console when in development mode
Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount('#app')
