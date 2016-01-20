var Vue = require('vue');
Vue.use(require('vue-resource'));

export default {
    events: Vue.resource('/api/v1/event'),
    locations: Vue.resource('/api/v1/location')
}
