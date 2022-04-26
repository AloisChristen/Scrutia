<template>
  <!-- Page Content -->
  <div class="hero">
    <div class="hero-inner text-center">
      <div class="bg-white overflow-hidden">
        <div class="content content-full">
          <div class="py-4">
            <!-- Error Header -->
            <h1 class="display-1 font-w600 text-city">404</h1>
            <h2 class="h4 font-w400 text-muted mb-5">
              Nous sommes désolés mais la page que vous cherchez n'a pas été
              trouvée...
            </h2>
            <!-- END Error Header -->
          </div>
        </div>
      </div>
      <div class="content content-full text-muted">
        <!-- Error Footer -->
        <router-link to="/" class="link-fx"> Go Back to home page </router-link>
        <!-- END Error Footer -->
      </div>
    </div>
  </div>
  <!-- END Page Content -->
</template>
