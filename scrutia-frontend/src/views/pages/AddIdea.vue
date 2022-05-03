<template>
  <!-- Page Content -->
  <div class="content">
    <b-row class="justify-content-center">
      <b-col md="8" lg="8" xl="8">
        <!-- Sign Up Block -->
        <base-block
          rounded
          themed
          class="mb-0"
          header-class="bg-primary-dark"
          title="Ajout de l'idée"
        >
          <div class="p-sm-3 px-lg-4 py-lg-5">
            <h1 class="h2 mb-1">Vous avez pris la bonne décision !</h1>
            <p class="text-muted">Remplissez le formulaire suivant</p>

            <!-- Sign Up Form -->
            <b-form @submit.stop.prevent="onSubmit">
              <div class="py-3">
                <div class="form-group">
                  <b-form-input
                    size="lg"
                    class="form-control-alt"
                    id="title"
                    name="title"
                    placeholder="Choisissez un titre..."
                    v-model="$v.form.title.$model"
                    :state="!$v.form.title.$error && null"
                    aria-describedby="title-feedback"
                  ></b-form-input>
                  <b-form-invalid-feedback id="title-feedback">
                    Le titre doit au moins contenir 10 caractères
                  </b-form-invalid-feedback>
                </div>
                <div class="form-group">
                  <b-form-textarea
                    size="lg"
                    class="form-control-alt"
                    id="description"
                    name="description"
                    placeholder="Décrivez votre idée..."
                    v-model="$v.form.description.$model"
                    :state="!$v.form.description.$error && null"
                    aria-describedby="description-feedback"
                    rows="4"
                  ></b-form-textarea>
                  <b-form-invalid-feedback id="description-feedback">
                    La description doit au moins contenir 50 caractères
                  </b-form-invalid-feedback>
                </div>
                <div class="form-group">
                  <b-form-group
                    size="lg"
                    class="form-control-alt"
                    id="tags"
                    name="tags"
                    placeholder="Tags"
                    v-model="$v.form.tags.$model"
                    :state="!$v.form.tags.$error && null"
                    aria-describedby="tags-feedback"
                  >
                    <v-select
                      multiple
                      v-model="vSelectOptionsMultipleSelected"
                      :options="vSelectOptionsMultiple"
                      placeholder="Définissez des tags..."
                    ></v-select>
                  </b-form-group>
                  <b-form-invalid-feedback id="tags-feedback">
                    Vous devez au moins définir un tag
                  </b-form-invalid-feedback>
                </div>
              </div>
              <b-row class="form-group">
                <b-col md="6" xl="5">
                  <b-button type="submit" variant="alt-success" block>
                    <i class="fa fa-fw fa-plus mr-1"></i> Sign Up
                  </b-button>
                </b-col>
              </b-row>
            </b-form>
            <!-- END Sign Up Form -->
          </div>
        </base-block>
        <!-- END Sign Up Block -->
      </b-col>
    </b-row>
  </div>
</template>

<style lang="scss">
// Vue Select + Custom overrides
@import '~vue-select/src/scss/vue-select';
@import './src/assets/scss/vendor/vue-select';
</style>

<script>
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'
import VueSelect from 'vue-select'

export default {
  components: {
    'v-select': VueSelect,
  },
  mixins: [validationMixin],
  data() {
    return {
      form: {
        title: null,
        description: null,
        tags: [],
      },
      vSelectOptionsMultiple: [
        'HTML',
        'CSS',
        'JavaScript',
        'PHP',
        'MySQL',
        'Ruby',
        'Angular',
        'React',
        'Vue.js',
      ],
      vSelectOptionsMultipleSelected: null,
    }
  },
  validations: {
    form: {
      title: {
        required,
        minLength: minLength(10),
      },
      description: {
        required,
        minLength: minLength(50),
      },
      tags: {
        required,
        minLength: minLength(5),
      },
    },
  },
  methods: {
    onSubmit() {
      this.$v.form.$touch()

      if (this.$v.form.$anyError) {
        return
      }

      // Form submit logic
      this.$router.push('/todo')
    },
  },
}
</script>
