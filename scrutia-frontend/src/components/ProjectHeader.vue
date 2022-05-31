<template>
  <div class="block-rounded block-transparent block col-xl-10">
    <div class="block-header">
      <h3 class="block-title">Ceci est un titre <small>Subtitle</small></h3>
      <!---->
    </div>
    <div class="block-content" style="padding: 10px 0px 0px 0px">
      <div class="container">
        <div class="row">
          <div class="block-content-left col-xl-2">
            <img
              src="https://via.placeholder.com/120x120"
              alt="..."
              class="img-fluid"
            />
            <p>tag 1 tag 2</p>
            <b-badge :variant="getNextColor()">Pandas</b-badge>
          </div>

          <div class="block-content-right col-xl-10">
            <p>
              Dolor posuere proin blandit accumsan senectus netus nullam
              curae, ornare laoreet adipiscing luctus mauris adipiscing
              pretium eget fermentum, tristique lobortis est ut metus
              lobortis tortor tincidunt himenaeos habitant quis dictumst
              proin odio sagittis purus mi, nec taciti vestibulum quis in
              sit varius lorem sit metus mi.
            </p>
          </div>
        </div>
      </div>

      <!---->
    </div>
  </div>

</template>

<script>
export default {
  name: "ProjectHeader",
  methods: {
    getNextColor() {
      const colors = [
        'primary',
        'secondary',
        'success',
        'info',
        'warning',
        'danger',
        'light',
        'dark',
      ]
      const index = Math.floor(Math.random() * colors.length)
      return colors[index]
    },
  }
}
</script>

<style scoped>

</style>
