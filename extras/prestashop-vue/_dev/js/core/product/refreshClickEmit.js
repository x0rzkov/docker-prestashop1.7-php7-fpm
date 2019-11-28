export default function() {
  let selector = '.product-variants-custom [data-custom-product-attribute]'

  $('body').on('change', selector, () => {
    $("input[name$='refresh']").click()

    this.$nextTick(function () {
      this.themeLoaderShow = true
    })
  })
}
