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
 * This class is used to disable Magento´s default Quantity calculations
 */
class Sitewards_StockCheck_Model_Product extends Mage_Catalog_Model_Product {
	protected $strProductIdentifierName;
	protected $strHelperName;
	protected $bolExtensionActive;

	public function __construct() {
		$this->strProductIdentifierName	= Mage::getStoreConfig('stockcheck_config/stockcheck_group/product_identifier_name');
		$this->strHelperName			= Mage::getStoreConfig('stockcheck_config/stockcheck_group/helper_name');
		$this->bolExtensionActive		= Mage::getStoreConfig('stockcheck_config/stockcheck_group/disable_ext');
		parent::__construct();
	}

	/**
	 * 
	 * @return int stock level from external source or Mage_Catalog_Model_Product
	 */
	public function getQty() {
		if($this->bolExtensionActive == true) {
			$strProductIdentifierValue = $this->_getData($this->strProductIdentifierName);

			if(method_exists(Mage::Helper($this->strHelperName), 'getCustomQuantity') == false) {
				Mage::throwException('StockCheck extension not correctly setup. Please create the function getCustomQuantity in the helper '.$this->strHelperName); 
			} else {
				$intCustomQty = Mage::Helper($this->strHelperName)->getCustomQuantity($strProductIdentifierValue);
				if(!is_null($intCustomQty)) {
					return $intCustomQty;
				} else {
					parent::getQty();
				}
			}
		} else {
			parent::getQty();
		}
	}

	/**
	 *
	 * @param int $intQuantity requested level of quantity for a product - defaults to 1
	 * @return array contianing the source and alt text of stock light
	 */
	public function getStockAsLight($intQuantity = 1) {
		if($this->bolExtensionActive != true) {
			return;
		}
		$strProductIdentifierValue = $this->_getData($this->strProductIdentifierName);

		if(method_exists(Mage::Helper($this->strHelperName), 'getStorageType') == false) {
			Mage::throwException('StockCheck extension not correctly setup. Please create the function getStorageType in the helper '.$this->strHelperName); 
		} elseif(method_exists(Mage::Helper($this->strHelperName), 'getProductsStockOrder') == false) {
			Mage::throwException('StockCheck extension not correctly setup. Please create the function getProductsStockOrder in the helper '.$this->strHelperName); 
		} else {
			$intStorageTypeId = Mage::Helper($this->strHelperName)->getStorageType($strProductIdentifierValue, $intQuantity);

			switch($intStorageTypeId) {
				case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_green_id'):
					return array(
						'id'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_green_id'),
						'source'	=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_green_img'),
						'alt'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_green_text'),
						'hover'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_green_hover')
					);
				break;

				case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_id'):
					return array(
						'id'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_id'),
						'source'	=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_img'),
						'alt'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_text'),
						'hover'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_yellow_hover')
					);
				break;

				case Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_id'):
					return array(
						'id'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_id'),
						'source'	=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_img'),
						'alt'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_text'),
						'hover'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_red_hover')
					);
				break;

				default:
					return array(
						'id'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_id'),
						'source'	=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_img'),
						'alt'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_text'),
						'hover'		=> Mage::getStoreConfig('stockcheck_config/stockcheck_img/stock_off_hover')
					);
			}
		}
	}
}