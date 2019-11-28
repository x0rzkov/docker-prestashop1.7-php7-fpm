{extends file=$layout}
{block name='content'}
<div>
	<iframe class="vendor-iframe-placeholder" frameborder="0" onload="resizeIframe(this);" width="100%" scrolling="no" src="index.php?fc=module&module=vendor&controller=VendorDashboard"></iframe>
</div>

<style type="text/css">
.vendor-iframe-placeholder
{
   background: url('{$base_url}modules/vendor/views/img/preloader.gif') 0px 0px no-repeat;
   background-repeat:no-repeat;
   background-position: center center;
}
</style>
<script>
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
{/block}