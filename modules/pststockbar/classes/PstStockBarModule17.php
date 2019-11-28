<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2017 Presta.Site
 * @license   LICENSE.txt
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

abstract class PstStockBarModule extends Module implements WidgetInterface
{
    abstract public function renderWidget($hookName, array $configuration);
    abstract public function getWidgetVariables($hookName, array $configuration);
}
