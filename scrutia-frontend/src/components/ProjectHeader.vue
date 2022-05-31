<template>
<div>
  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="8">
      <base-block rounded class="mb-0" transparent>
        <h1 class="font-w400">{{ title }}</h1>
      </base-block>
    </b-col>
  </b-row>
  <div class="block-rounded block-transparent block col-xl-10">
    <div class="block-header">
    </div>
    <div class="block-content" style="padding: 10px 0px 0px 0px">
      <div class="container">
        <div class="row">
          <div class="block-content-left col-xl-2">

            <img
              v-if="displayPicture"
              src="https://via.placeholder.com/120x120"
              alt="..."
              class="img-fluid"
            />
            <div>
              <b-badge :variant="getNextColor()" margin-right-15>Pandas</b-badge>
              <b-badge :variant="getNextColor()">Pandas</b-badge>
              <b-badge :variant="getNextColor()">Pandas</b-badge>
            </div>

          </div>

          <div class="block-content-right col-xl-10">
            <p>
              {{description}}
            </p>
          </div>
        </div>
      </div>

      <!---->
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: "ProjectHeader",
  props: {
    title: {
      type: String,
      default: 'Ceci est un titre'
    },
    tagList: {
      type: [String],
      required: true
    },
    description: {
      type: String,
      default: "Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in         sit varius lorem sit metus mi."
    },
    displayPicture: {
      type: Boolean,
      default: false,
    }

  },
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
  margin-right-15 {
    margin-right: 15px;
  }
</style>
