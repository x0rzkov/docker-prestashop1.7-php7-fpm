/*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

// Main Function
var Main = function () {

	/**
	** Click Event
	*/
	var runEvent = function () {
		$seo_link = $('#link-Seo');
		$seo_link.live('click', function (e) {
			e.preventDefault();
			$seo = $('#product-seo');
			$ggpreview = $('#ggpreview');

			if($ggpreview.length == 0) {
				$seo.find('.row').append( '<div class="col-lg-9 col-lg-offset-3"><div id="ggpreview" class="rc" data-hveid="73"><h3 class="r"><a href="/url?sa=t&amp;rct=j&amp;q=&amp;esrc=s&amp;so data-href="https://www.prestashop.com/fr/modules-gratuits-partenaires">Modules PrestaShop Gratuits</a></h3><div class="s"><div><div class="f kv _SWb" style="white-space:nowrap"><cite class="_Rm">https://www.<b>prestashop</b>.com/fr/modules-gratuits-partenaires</cite><div class="action-menu ab_ctl"><a class="_Fmb ab_button" href="#" id="am-b8" aria-label="Détails du résultat" aria-expanded="false" aria-haspopup="true" role="button" jsaction="ab.tdd;keydown:ab.hbke;keypress:ab.mskpe" data-ved="0CEsQ7B0wCA"><span class="mn-dwn-arw"></span></a><div class="action-menu-panel ab_dropdown" role="menu" tabindex="-1" jsaction="keydown:ab.hdke;mouseover:ab.hdhne;mouseout:ab.hdhue" data-ved="0CEwQqR8wCA" style="visibility: hidden;"><ul><li class="action-menu-item ab_dropdownitem" role="menuitem"><a class="fl" href="http://webcache.googleusercontent.com/search?q=cache:rBCvLUxkp_oJ:https://www.prestashop.com/fr/modules-gratuits-partenaires+&amp;cd=9&amp;hl=fr&amp;ct=clnk&amp;gl=fr">En&nbsp;cache</a></li></ul></div></div></div><div class="f slp"></div><span class="st">En sassociant à des leaders du secteur, <em>PrestaShop</em> vous garantit un accès aux  ...  <span class="ft">de partenariat dédié aux agences · Vendre sur <em>Addons</em> · Modules gratuits</span> &nbsp;...</span></div></div></div></div>' );
			}
		});
	};

	return {
		init: function () {
			runEvent();
		}
	};
}();

// Load functions
$(function() {
	Main.init();
});