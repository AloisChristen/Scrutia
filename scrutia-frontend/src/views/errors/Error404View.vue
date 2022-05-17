<template>
  <div class="hero">
    <div class="hero-inner text-center">
      <div class="bg-white overflow-hidden">
        <div class="content content-full">
          <div class="py-4">
            <h1 class="display-1 font-w600 text-city">404</h1>
            <h2 class="h4 font-w400 text-muted mb-5">
              Nous sommes désolés mais la page que vous cherchez n'a pas été
              trouvée...
            </h2>
          </div>
        </div>
      </div>
      <div class="content content-full text-muted">
        <router-link to="/" class="link-fx"
          >Retourner sur la page d'accueil</router-link
        >
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
@Component({
  name: 'Error404View',
})
export default class Error404View extends Vue {}
</script>
