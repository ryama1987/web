<template>
  <div>
    <Cover v-if="app && app.cover && app.cover.value" :img="app.cover.value" />
    <div class="Container">
      <div class="Container_Inner">
        <main class="Articles">
          <div class="Articles_Inner">
            <h2 class="Articles_Heading">Recent Articles</h2>
            <ArticleCard
              v-for="article in articles"
              :key="article._id"
              :article="article"
            />
          </div>
          <Pagination :total="total" :current="1" />
        </main>
        <Side :tags="popularTags" :authors="authors" :archives="archives" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getSiteName } from 'utils/head'

export default {
  async asyncData({ $config, store }) {
    await store.dispatch('fetchApp', $config)
    await store.dispatch('fetchArticles', $config)
    await store.dispatch('fetchTags', $config)
    await store.dispatch('fetchAuthors', $config)
    await store.dispatch('fetchArchives', $config)
    return {}
  },
  head() {
    return {
      title: getSiteName(this.app),
    }
  },
  computed: {
    ...mapGetters([
      'app',
      'articles',
      'total',
      'popularTags',
      'authors',
      'archives',
    ]),
  },
}
</script>

<style scoped>
.Container {
  padding: 40px 24px;
}
.Container_Inner {
  display: block;
  margin: 0 auto;
}
@media (min-width: 600px) {
  .Container {
    padding: 60px;
  }
  .Container_Inner {
    max-width: 980px;
  }
}
@media (min-width: 960px) {
  .Container_Inner {
    display: flex;
  }
}
.Articles {
  flex: 1;
}
.Articles_Heading {
  font-size: 2rem;
  margin: 0 0 42px;
  padding: 0;
  line-height: 1.4;
}
@media (min-width: 960px) {
  .Articles {
    margin: 0 40px 0 0;
  }
}
</style>
