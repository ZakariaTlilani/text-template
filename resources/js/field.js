Nova.booting((Vue, router, store) => {
  Vue.component('index-title-template', require('./components/IndexField'))
  Vue.component('detail-title-template', require('./components/DetailField'))
  Vue.component('form-title-template', require('./components/FormField'))
})
