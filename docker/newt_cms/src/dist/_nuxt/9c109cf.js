(window.webpackJsonp=window.webpackJsonp||[]).push([[15,6],{318:function(e,t,r){var content=r(325);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(36).default)("13f357ee",content,!0,{sourceMap:!1})},324:function(e,t,r){"use strict";r(318)},325:function(e,t,r){var n=r(35)(!1);n.push([e.i,".Cover[data-v-6eb0d0a9]{width:100%;height:180px;background-size:cover;background-position:50%}@media (min-width:600px){.Cover[data-v-6eb0d0a9]{height:280px}}",""]),e.exports=n},326:function(e,t,r){"use strict";r.r(t);var n={props:{img:{type:String,default:""}}},c=(r(324),r(18)),component=Object(c.a)(n,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("div",{staticClass:"Cover",style:"background-image: url("+e.img+");"},[e._v("\n   \n")])}),[],!1,null,"6eb0d0a9",null);t.default=component.exports},353:function(e,t,r){var content=r(387);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(36).default)("5957b28c",content,!0,{sourceMap:!1})},386:function(e,t,r){"use strict";r(353)},387:function(e,t,r){var n=r(35)(!1);n.push([e.i,".Container[data-v-9e36ea96]{padding:40px 24px}.Container_Inner[data-v-9e36ea96]{display:block;margin:0 auto}@media (min-width:600px){.Container[data-v-9e36ea96]{padding:60px}.Container_Inner[data-v-9e36ea96]{max-width:980px}}@media (min-width:960px){.Container_Inner[data-v-9e36ea96]{display:flex}}.Articles[data-v-9e36ea96]{flex:1}.Articles_Heading[data-v-9e36ea96]{font-size:2rem;margin:0 0 42px;padding:0;line-height:1.4}@media (min-width:960px){.Articles[data-v-9e36ea96]{margin:0 40px 0 0}}",""]),e.exports=n},402:function(e,t,r){"use strict";r.r(t);var n=r(19),c=r(4),o=(r(54),r(298),r(323),r(38),r(28),r(37),r(13),r(52),r(29),r(53),r(47)),l=r(302);function d(object,e){var t=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(object,e).enumerable}))),t.push.apply(t,r)}return t}function f(e){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?d(Object(source),!0).forEach((function(t){Object(n.a)(e,t,source[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(source)):d(Object(source)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(source,t))}))}return e}var v={asyncData:function(e){return Object(c.a)(regeneratorRuntime.mark((function t(){var r,n,c,o,l;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.$config,n=e.store,c=e.redirect,o=e.params,t.next=3,n.dispatch("fetchApp",r);case 3:return t.next=5,n.dispatch("fetchTags",r);case 5:return t.next=7,n.dispatch("fetchAuthors",r);case 7:return t.next=9,n.dispatch("fetchArchives",r);case 9:if(l=Number(o.page),!Number.isNaN(l)){t.next=12;break}return t.abrupt("return",c(302,"/"));case 12:return t.next=14,n.dispatch("fetchArticles",f(f({},r),{},{page:l}));case 14:return t.abrupt("return",{pageNumber:l});case 15:case"end":return t.stop()}}),t)})))()},head:function(){return{title:Object(l.a)(this.app)}},computed:f({},Object(o.b)(["app","articles","total","popularTags","authors","archives"]))},h=(r(386),r(18)),component=Object(h.a)(v,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[e.app&&e.app.cover&&e.app.cover.value?r("Cover",{attrs:{img:e.app.cover.value}}):e._e(),e._v(" "),r("div",{staticClass:"Container"},[r("div",{staticClass:"Container_Inner"},[r("main",{staticClass:"Articles"},[r("div",{staticClass:"Articles_Inner"},[r("h2",{staticClass:"Articles_Heading"},[e._v("Recent Articles")]),e._v(" "),e._l(e.articles,(function(article){return r("ArticleCard",{key:article._id,attrs:{article:article}})}))],2),e._v(" "),r("Pagination",{attrs:{total:e.total,current:e.pageNumber}})],1),e._v(" "),r("Side",{attrs:{tags:e.popularTags,authors:e.authors,archives:e.archives}})],1)])],1)}),[],!1,null,"9e36ea96",null);t.default=component.exports;installComponents(component,{Cover:r(326).default,ArticleCard:r(303).default,Pagination:r(301).default,Side:r(304).default})}}]);