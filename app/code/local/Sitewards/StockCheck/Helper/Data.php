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
 * This class is used to help the Sitewards_StockCheck_Model_Product class and requires the following functions to be created
 * getCustomQuantity,
 * getStorageType,
 * getProductsStockOrder
 */
class Sitewards_StockCheck_Helper_Data extends Mage_Core_Helper_Abstract {
	protected $intStockGreenId;
	protected $intStockYellowId;
	protected $intStockRedId;
	protected $intStockOffId;

	public function __construct() {
		$this->intStockGreenId	= Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_green_id');
		$this->intStockYellowId	= Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_id');
		$this->intStockRedId	= Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_id');
		$this->intStockOffId	= Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_id');
	}

	/**
	 * 
	 * @param	int|string $mxdProductSku unique identifier for a product - defaults to Magento ProductId
	 * @return	int real time stock level
	 */
	public function getCustomQuantity($mxdProductSku) {
		Mage::throwException('StockCheck extension not correctly setup. Please complete the function getCustomQuantity in the helper '.get_class()); 
	}

	/**
	 * 
	 * @param	int|string $mxdProductSku unique identifier for a product - defaults to Magento ProductId
	 * @return	int constant related to real time stock level
	 */
	public function getStorageType($mxdProductSku) {
		Mage::throwException('StockCheck extension not correctly setup. Please complete the function getStorageType in the helper '.get_class()); 
	}

	/**
	 *
	 * @param	int|string $mxdProductSku unique identifier for a product - defaults to Magento ProductId
	 * @return	int current amount of stock taking into account the items on order
	 */
	public function getProductsStockOrder($mxdProductSku) {
		Mage::throwException('StockCheck extension not correctly setup. Please complete the function getProductsStockOrder in the helper '.get_class()); 
	}
}