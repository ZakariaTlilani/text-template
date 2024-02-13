import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-templating', IndexField)
  app.component('detail-templating', DetailField)
  app.component('form-templating', FormField)
})
