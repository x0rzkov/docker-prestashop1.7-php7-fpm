/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL DREAM ME UP
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL DREAM ME UP is strictly forbidden.
 *
 *   .--.
 *   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
 *   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
 *   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
 *        w w w . d r e a m - m e - u p . f r       '
 *
 * @author    Dream me up <prestashop@dream-me-up.fr>
 * @copyright 2007 - 2016 Dream me up
 * @license   All Rights Reserved
 */
function populate_caracts()
{
	var attr_group = getE('caract_group');
	var selected = getE('caract_selected').value;
	if (!attr_group)
		return;
	var attr_name = getE('caracte');
	var number = attr_group.options.length ? attr_group.options[attr_group.selectedIndex].value : 0;

	if (!number)
	{
		attr_name.options.length = 0;
		attr_name.options[0] = new Option('---', 0);
		return;
	}

	var list = caracts[number];
	attr_name.options.length = 0;

	for (i = 0; i < list.length; i += 2)
	{
		if (selected !== "")
		{
			if (list[i] === selected)
				attr_name.options[i / 2] = new Option(list[i + 1], list[i], true);
			else
				attr_name.options[i / 2] = new Option(list[i + 1], list[i]);
		}
		else
			attr_name.options[i / 2] = new Option(list[i + 1], list[i]);
	}
}

function populate_attrs()
{
	var attr_group = getE('attribute_group');
	var selected = getE('attribute_selected').value;
	if (!attr_group)
		return;
	var attr_name = getE('attribute');
	var number = attr_group.options.length ? attr_group.options[attr_group.selectedIndex].value : 0;

	if (!number)
	{
		attr_name.options.length = 0;
		attr_name.options[0] = new Option('---', 0);
		return;
	}

	var list = attrs[number];
	attr_name.options.length = 0;

	for (i = 0; i < list.length; i += 2)
	{
		if (selected !== "")
		{
			if (list[i] === selected)
				attr_name.options[i / 2] = new Option(list[i + 1], list[i],true);
			else
				attr_name.options[i / 2] = new Option(list[i + 1], list[i]);
		}
		else
			attr_name.options[i / 2] = new Option(list[i + 1], list[i]);
	}
}

//PRICE

function getTax(id)
{
	if (noTax)
		return 0;
	var selectedTax = document.getElementById('id_tax_rules_group_price_'+id);
	var taxId = selectedTax.options[selectedTax.selectedIndex].value;
	return taxesArray[taxId];
}

function calcPriceTI(id)
{
	var tax = getTax(id);
	var priceTE = parseFloat(document.getElementById('priceTE_price_'+id).value.replace(/,/g, '.'));
	var newPrice = priceTE * ((tax / 100) + 1);
	document.getElementById('priceTI_price_'+id).value = (isNaN(newPrice) === true || newPrice < 0) ? '' : ps_round(newPrice, priceDisplayPrecision);
	document.getElementById('finalPrice_'+id).innerHTML = (isNaN(newPrice) === true || newPrice < 0) ? '' : ps_round(newPrice, priceDisplayPrecision).toFixed(priceDisplayPrecision);
	document.getElementById('finalPriceWithoutTax_'+id).innerHTML = (isNaN(priceTE) === true || priceTE < 0) ? '' : (ps_round(priceTE, priceDisplayPrecision) + getEcotaxTaxExcluded(id)).toFixed(priceDisplayPrecision);
	document.getElementById('priceTI_price_'+id).value = (parseFloat(document.getElementById('priceTI_price_'+id).value) + getEcotaxTaxIncluded(id)).toFixed(priceDisplayPrecision);
	document.getElementById('finalPrice_'+id).innerHTML = parseFloat(document.getElementById('priceTI_price_'+id).value).toFixed(priceDisplayPrecision);
}

function getEcotaxTaxIncluded(id)
{
	return parseFloat(document.getElementById('ecotax_price_'+id).value !== '' ? document.getElementById('ecotax_price_'+id).value : 0);
}

function getEcotaxTaxExcluded(id)
{
	return getEcotaxTaxIncluded(id) / ecotaxTaxRate;
}

function unitPriceWithTax(id)
{
	var tax = getTax(id);
	var priceWithTax = parseFloat(document.getElementById('unit_price_price_'+id).value.replace(/,/g, '.'));
	var newPrice = priceWithTax * ((tax / 100) + 1);
	$('#unit_price_with_tax_'+id).html((isNaN(newPrice) === true || newPrice < 0) ? '0.00' : ps_round(newPrice, priceDisplayPrecision).toFixed(priceDisplayPrecision));
}

function calcPriceTE(id)
{
	var tax = getTax(id);
	var priceTI = parseFloat(document.getElementById('priceTI_price_'+id).value.replace(/,/g, '.'));
	var newPrice = ps_round(priceTI - getEcotaxTaxIncluded(id), priceDisplayPrecision) / ((tax / 100) + 1);
	document.getElementById('priceTE_price_'+id).value = (isNaN(newPrice) === true || newPrice < 0) ? '' : ps_round(newPrice.toFixed(6), 6);
	document.getElementById('finalPrice_'+id).innerHTML = (isNaN(newPrice) === true || newPrice < 0) ? '' : ps_round(priceTI.toFixed(2), priceDisplayPrecision);
	document.getElementById('finalPriceWithoutTax_'+id).innerHTML = (isNaN(newPrice) === true || newPrice < 0) ? '' : ps_round(newPrice.toFixed(priceDisplayPrecision), priceDisplayPrecision) + getEcotaxTaxExcluded(id);
}

function unitySecond(id)
{
	unit_price = $('#unity_price_'+id).val();
	$('#unity_second_'+id).html(unit_price);
	if (unit_price != '') {
		$('.unit_price').show();
	}
}

//PRIX LISTING

function getTaxlisting(id)
{
	if (noTax)
		return 0;
	var taxId = document.getElementById('id_tax_rules_group_listing'+id).value;
	return taxesArray[taxId];
}

function getEcotaxTaxIncludedlisting(id)
{
	return parseFloat(document.getElementById('ecotax_listing'+id).value !== '' ? document.getElementById('ecotax_listing'+id).value : 0);
}

if (typeof(countDown) === 'undefined') {
	function countDown($source, $target) {
		var max = $source.attr("data-maxchar");
		$target.html(max-$source.val().length);

		$source.keyup(function(){
			$target.html(max-$source.val().length);
		});
	}
}