import Vue from 'vue'

var productSmallList = Vue.extend({
  template: '#product-small-list',
  props: ['product', 'withQuantity']
})

export default productSmallList
