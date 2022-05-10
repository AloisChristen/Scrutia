<template>
  <div>
    <div class="content">
      <h1 class="content-heading">
        détail de l'idée {{ $route.params.project_id }}
      </h1>

      <b-button variant="primary" class="mr-1 mb-3">
        <i class="fa fa-fw fa-thumbs-up mr-1"></i> Like
      </b-button>
      <base-block rounded>
        <p class="text-center">Mon idée super cool</p>
      </base-block>
      <i class="fa fa-box-open"></i>

      <b-button size="sm" variant="outline-secondary" class="btn-rounded"
        >tag</b-button
      >

      <div style="display=flex"></div>
      <h2 class="h3">Foire aux questions</h2>
      <div role="tablist">
        <base-block rounded bordered header-bg class="mb-1">
          <template #header>
            <a class="text-muted" href="javascript:void(0)" v-b-toggle.faq3-id
              >C'est gratos ?</a
            >
          </template>
          <template #content>
            <b-collapse id="faq3-q1" visible accordion="faq3" role="tabpanel">
              <div class="block-content">
                <p>nasdbaisdbaidbaisdb</p>
                <p>akjdkbasdj b</p>
              </div>
            </b-collapse>
          </template>
        </base-block>
        <base-block rounded bordered header-bg class="mb-1">
          <template #header>
            <a class="text-muted" href="javascript:void(0)" v-b-toggle.faq3-q2
              >C'est gratos ?</a
            >
          </template>
          <template #content>
            <b-collapse id="faq3-q2" visible accordion="faq3" role="tabpanel">
              <div class="block-content">
                <p>nasdbaisdbaidbaisdb</p>
                <p>akjdkbasdj b</p>
              </div>
            </b-collapse>
          </template>
        </base-block>
        <base-block rounded bordered header-bg class="mb-1">
          <template #header>
            <a class="text-muted" href="javascript:void(0)" v-b-toggle.faq3-q3
              >C'est gratos ?</a
            >
          </template>
          <template #content>
            <b-collapse id="faq3-q3" visible accordion="faq3" role="tabpanel">
              <div class="block-content">
                <p>nasdbaisdbaidbaisdb</p>
                <p>akjdkbasdj b</p>
              </div>
            </b-collapse>
          </template>
        </base-block>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'IdeaDetails',
  mounted() {
    // Will be executed when the DOM is ready
    this.fetch_data()
  },

  methods: {
    /**
     * load the data necessary for the page
     */
    fetch_data() {
      console.log('fetching data for page')
      /*
        faire truc du genre:
        axios({ method: "GET", "url": "https://localhost:42/api/getdata" }).then(result => {
            console.log(result.data[0].LoginId);
            this.id = result.data[0].LoginId;
        }, error => {
            console.error(error);
        });

        -> sinon service ? je trouve en local c'est ok en VUE (si pas de traitement necessaire) -> token bearer
        https://stackoverflow.com/questions/67554333/jwt-where-to-store-token-in-vue-js

          exemple trop bien pour le token
          https://stackoverflow.com/questions/45384172/best-practice-for-storing-auth-tokens-in-vuejs

          challenge:
          v-b-toggle.faq3-q1
          <b-collapse id="faq3-q1" visible accordion="faq3" role="tabpanel">
        */
    },

    /**
     * promote this idea to an initiative
     */
    promote() {
      console.log('promote')
      // put sur le projet puis nav
    },

    /**
     * navigate to faq detail
     */
    navigate_to_faq_detail(faq_id) {
      console.log('navigate to faq', faq_id)
      // voir https://router.vuejs.org/guide/essentials/navigation.html
      // donc par exemple: router.push({ name: 'user', params: { username: 'eduardo' } })
    },

    /**
     * press a discussion upvote button
     * @param is_upvote: false if downvote, true if upvote
     */
    upvote_discussion(discussion_id, is_upvote) {
      console.log('upvote discussion', discussion_id, is_upvote)
    },
  },
  data() {
    // computed properties
    return {
      is_loading: false, // is current idea data loading
      current_idea: {
        // to fetch, just an example. -> discuter si la structure serait ok avec Alexis pour faire quelques binding
        title: 'mon idea',
        description: 'lorem ipsum...',
        tags: [{ tag_id: 1, theme: 'environnement' }],
        is_idea_from_user: false, // is this idea from user
        can_be_promoted: false, // can be promoted
        is_liked: false, // is this idea liked by user
        faq_content: [
          // content of the faq -> composant pour l'affichage a faire ensuite
          {
            id: '1', // a voir si on charge tout au final ou si on reste en 2 page (detail fil) -> a reuteisiler dans les truc en dessus -> dom manipulation pour cacher toggle.
            title: 'faq 1',
            date: '29.03.1998',
            author: 'user_id',
            like_current: '10',
            like_self: false,
            dislike_self: true,
            discussion: [
              {
                id: '123214',
                image: 'base64image', // TODO: voir comment on gere les images de personne
                author: 'fred', // -> juste id ?
                text: 'mon avis pas pertinent',
              },
            ],
          },
        ],
      },
    }
  },
}
</script>

<style scoped></style>

<!-- mode demo pour ne pas avoir besoin de server ? -->
<!-- vscode: comment prettify directement ? -->
<!-- commnet on gere les erreurs server -> redirection ? -->
<!-- https://router.vuejs.org/guide/essentials/dynamic-matching.html#catch-all-404-not-found-route -->

<!-- test unitaires (a discuter avec Alexis si il en voit d'autre) -->
<!-- 1. affichage titre -->
<!-- 2. affichage not found -->
<!-- 3. faq dispo -->
<!-- 4. is lked self -->
<!-- 5. is not lked self -->
<!-- 6. is not lked self -->
<!-- autres affichage de boutons en mode disabled-->
