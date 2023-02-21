import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _2714eff2 = () => interopDefault(import('../pages/search/index.vue' /* webpackChunkName: "pages/search/index" */))
const _2e07f26e = () => interopDefault(import('../pages/archive/_slug/index.vue' /* webpackChunkName: "pages/archive/_slug/index" */))
const _433be7fa = () => interopDefault(import('../pages/article/_slug.vue' /* webpackChunkName: "pages/article/_slug" */))
const _5b984390 = () => interopDefault(import('../pages/author/_slug/index.vue' /* webpackChunkName: "pages/author/_slug/index" */))
const _46d3fc75 = () => interopDefault(import('../pages/page/_page.vue' /* webpackChunkName: "pages/page/_page" */))
const _8a7053be = () => interopDefault(import('../pages/tag/_slug/index.vue' /* webpackChunkName: "pages/tag/_slug/index" */))
const _4007e687 = () => interopDefault(import('../pages/archive/_slug/page/_page.vue' /* webpackChunkName: "pages/archive/_slug/page/_page" */))
const _7574e3c0 = () => interopDefault(import('../pages/author/_slug/page/_page.vue' /* webpackChunkName: "pages/author/_slug/page/_page" */))
const _b32023a2 = () => interopDefault(import('../pages/tag/_slug/page/_page.vue' /* webpackChunkName: "pages/tag/_slug/page/_page" */))
const _1479741b = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/search",
    component: _2714eff2,
    name: "search"
  }, {
    path: "/archive/:slug",
    component: _2e07f26e,
    name: "archive-slug"
  }, {
    path: "/article/:slug?",
    component: _433be7fa,
    name: "article-slug"
  }, {
    path: "/author/:slug",
    component: _5b984390,
    name: "author-slug"
  }, {
    path: "/page/:page?",
    component: _46d3fc75,
    name: "page-page"
  }, {
    path: "/tag/:slug",
    component: _8a7053be,
    name: "tag-slug"
  }, {
    path: "/archive/:slug?/page/:page?",
    component: _4007e687,
    name: "archive-slug-page-page"
  }, {
    path: "/author/:slug?/page/:page?",
    component: _7574e3c0,
    name: "author-slug-page-page"
  }, {
    path: "/tag/:slug?/page/:page?",
    component: _b32023a2,
    name: "tag-slug-page-page"
  }, {
    path: "/",
    component: _1479741b,
    name: "index"
  }],

  fallback: false
}

export function createRouter (ssrContext, config) {
  const base = (config._app && config._app.basePath) || routerOptions.base
  const router = new Router({ ...routerOptions, base  })

  // TODO: remove in Nuxt 3
  const originalPush = router.push
  router.push = function push (location, onComplete = emptyFn, onAbort) {
    return originalPush.call(this, location, onComplete, onAbort)
  }

  const resolve = router.resolve.bind(router)
  router.resolve = (to, current, append) => {
    if (typeof to === 'string') {
      to = normalizeURL(to)
    }
    return resolve(to, current, append)
  }

  return router
}
