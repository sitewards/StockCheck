<?php
/**
 * Sitewards_StockCheck_Model_Cart_Observer
 * 	- Observer is used to look for the Mage_Checkout_Block_Cart
 *
 * @category    Sitewards
 * @package     Sitewards_StockCheck
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 */
class Sitewards_StockCheck_Model_Cart_Observer
{
	/**
	 * Check for stock levels and display a message if required
	 *
	 * @param Varien_Event_Observer $oObserver
	 */
	public function onCoreBlockAbstractToHtmlBefore(Varien_Event_Observer $oObserver)
	{
		if(Mage::getStoreConfig('stockcheck_config/stockcheck_group/disable_ext')) {
			if ($oObserver->getData('block') instanceof Mage_Checkout_Block_Cart) {
				$sProductIdentifierName = Mage::getStoreConfig('stockcheck_config/stockcheck_group/product_identifier_name');
				$oCart = Mage::getSingleton('checkout/cart');
				foreach($oCart->getItems() as $oItem) {
					$oProduct = $oItem->getProduct();
					$sArtnr = $oProduct->getData($sProductIdentifierName);
					if(empty($sArtnr)) {
						$oProduct->load($oProduct->getId());
					}
					$iActualUnits = $oItem->getProduct()->getQty();
					$iPurchasedUnits = $oItem->getQty();

					$aProductStockInfo = $oProduct->getStockAsLight($iPurchasedUnits);

					$sErrorMessage = null;
					switch($aProductStockInfo['id']) {
						case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_id'):
							$sErrorMessage = 'This article is not a stock item and will be replenished in about 2-4 days.';
							break;

						case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_id'):
							if($iActualUnits > 0) {
								$sErrorMessage = 'We currently only have %d units in stock, the remaining %d are on order from the manufacturer, so there should be no delay in delivery';
							} else {
								$sErrorMessage = 'Item is currently out of stock, but has been ordered. There should be no delivery delays';
							}
							break;

						case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_id'):
							if($iActualUnits > 0) {
								$sErrorMessage = 'We currently only have %d units in stock, the remaining %d units will be in stock in 2-4 working days';
							} else {
								$sErrorMessage = 'This item is currently out of stock and is on backorder in 2-4 working days';
							}
							break;
					}
					if(!is_null($sErrorMessage)) {
						$oMessageFactory = Mage::getSingleton('core/message');
						$oMessage = $oMessageFactory->error(
							Mage::helper('checkout')->__(
								$sErrorMessage, $iActualUnits, $iPurchasedUnits - $iActualUnits)
						);
						$oCart->getCheckoutSession()->addQuoteItemMessage($oItem->getId(), $oMessage);
					}
				}
			}
		}
	}
}