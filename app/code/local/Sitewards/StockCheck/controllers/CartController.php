<?php
/**
 * 
 * @category	Mage
 * @package		Sitewards_StockCheck
 * @copyright	Copyright (c) 2011 Sitewards GmbH. (http://www.sitewards.com)
 * @license		OSL
 * @author		David Manners <david.manners@sitewards.com>
 * @version		1.0.0
 * 
 * This class is used to override the Mage_Checkout_CartController and display an error message about stock levels
 */

require_once 'Mage/Checkout/controllers/CartController.php';
class Sitewards_StockCheck_CartController extends Mage_Checkout_CartController {
	/**
	 * 
	 * Override the cart indexAction functio to check for stock levels and display a message if required
	 */
	public function indexAction(){
		$bolExtensionActive = Mage::getStoreConfig('stockcheck_config/stockcheck_group/disable_ext');
		if($bolExtensionActive == true) {
			$strProductIdentifierName	= Mage::getStoreConfig('stockcheck_config/stockcheck_group/product_identifier_name');
			$objCart = $this->_getCart();
			foreach($this->_getCart()->getItems() as $objItem) {
				$objProduct = $objItem->getProduct();
				$strArtnr = $objProduct->getData($strProductIdentifierName);
				if(empty($strArtnr)) {
					$objProduct->load($objProduct->getId());
				}
				$strArtnr = $objProduct->getData($strProductIdentifierName);
				$intActualUnits = $objItem->getProduct()->getQty();
				$intPurchasedUnits = $objItem->getQty();

				$aryProductStockInfo = $objProduct->getStockAsLight($intPurchasedUnits);
				$intProductStock = Mage::Helper(Mage::getStoreConfig('stockcheck_config/stockcheck_group/helper_name'))->hasStock($intPurchasedUnits, $strArtnr);

				$strErrorMessage = null;
				switch($aryProductStockInfo['id']) {
					case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_id'):
						$strErrorMessage = 'This article is not a stock item and will be replenished in about 2-4 days.';
					break;

					case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_id'):
						if($intActualUnits > 0) {
							$strErrorMessage = 'We currently only have %d units in stock, the remaining %d are on order from the manufacturer, so there should be no delay in delivery';
						} else {
							$strErrorMessage = 'Item is currently out of stock, but has been ordered. There should be no delivery delays';
						}
					break;

					case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_id'):
						if($intActualUnits > 0) {
							$strErrorMessage = 'We currently only have %d units in stock, the remaining %d units will be in stock in 2-4 working days';
						} else {
							$strErrorMessage = 'This item is currently out of stock and is on backorder in 2-4 working days';
						}
					break;
				}
				if(!is_null($strErrorMessage)) {
					$objMessageFactory = Mage::getSingleton('core/message');
					$objMessage = $objMessageFactory->error(Mage::helper('checkout')->__($strErrorMessage, $intActualUnits, $intPurchasedUnits - $intActualUnits));
					$objCart->getCheckoutSession()->addQuoteItemMessage($objItem->getId(), $objMessage);
				}
			}
		}
		parent::indexAction();
	}
}