<template>
  <div class="content" style="margin-bottom: 30px">
    <b-row class="justify-content-center">
      <b-col md="8" lg="8" xl="8">
        <base-block
          rounded
          themed
          class="mb-0"
          header-class="bg-primary-dark"
          title="Ajout de l'idée"
        >
          <template #options>
            <i
              class="fa fa-question"
              v-b-tooltip.hover.bottom="
                'Saisissez un titre à votre idée et ajoutez une description complète qui présente l\'ensemble de vos motivations. Choisissez des tags pour permettre à votre idée d\'être facilement recherchée. Une fois celle-ci publiée, vous devrez atteindre un nombre de soutient s\'élevant à 500 pour pouvoir la convertir en projet d\'initiative.'
              "
            ></i>
          </template>
          <div class="p-sm-2 px-lg-2 py-lg-2">
            <h1 class="h4 mb-1">Vous avez pris la bonne décision !</h1>
            <p class="text-muted">Remplissez le formulaire suivant</p>
            <b-form @submit.stop.prevent="onSubmit">
              <div class="py-3">
                <div class="form-group">
                  <b-form-input
                    size="lg"
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
                  <v-select
                    size="lg"
                    class="form-control-alt"
                    taggable
                    multiple
                    v-model="vSelectOptionsMultipleSelected"
                    :options="vSelectOptionsMultiple"
                    placeholder="Définissez des tags..."
                  ></v-select>
                  <b-form-invalid-feedback id="tags-feedback">
                    Vous devez au moins définir un tag
                  </b-form-invalid-feedback>
                </div>
              </div>
              <b-row class="form-group">
                <b-col md="6" xl="6">
                  <b-button type="submit" variant="alt-success" block>
                    <i class="fa fa-lightbulb mr-1"></i> Publier votre idée
                  </b-button>
                </b-col>
                <b-col md="6" xl="6">
                  <b-button type="submit" variant="alt-warning" block>
                    <i class="fa fa-trash mr-1"></i> Réinitialiser
                  </b-button>
                </b-col>
              </b-row>
            </b-form>
          </div>
        </base-block>
      </b-col>
    </b-row>
  </div>
</template>

<style lang="scss">
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
