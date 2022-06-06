<template>
  <div>
    <div class="content">

      <ProjectHeader
        title="sauver les pandas du feu"
        description="composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker."
        :tagList="['d', 'asd', 'env']"
        ideaActionActivated
        canBePromoted
        ></ProjectHeader>



      <ProjectDiscussion v-for="d in discussions"
                          :key="d.id"
                          :title="d.title"
                          :text="d.text"
      />

    </div>
  </div>
</template>

<script>
import ProjectHeader from "@/components/ProjectHeader.vue";
import ProjectDiscussion from "@/components/ProjectDiscussion.vue";
export default {
  name: "IdeaDetailsView",
  components: {
    ProjectHeader,ProjectDiscussion
  },
  data() {
    return {
      discussions: [
        {id: 1, title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200, text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components."},
        {id: 2, title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200, text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components."},
        {id: 3, title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200, text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components."}
      ]
    }
  },

}

</script>
