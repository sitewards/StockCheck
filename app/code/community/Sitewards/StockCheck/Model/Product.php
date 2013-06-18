<?php
/**
 *
 * @category    Sitewards
 * @package     Sitewards_StockCheck
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 *
 * This class is used to disable Magento´s default Quantity calculations
 */
class Sitewards_StockCheck_Model_Product extends Mage_Catalog_Model_Product
{
	/**
	 * Attribute name on which to identify products
	 *
	 * @var string
	 */
	protected $_sProductIdentifierName;

	/**
	 * Extension active flag
	 *
	 * @var boolean
	 */
	protected $_bExtensionActive;

	/**
	 * StockCheck helper
	 *
	 * @var Sitewards_StockCheck_Helper_Interface
	 */
	protected $_oHelper;

	/**
	 * Define all properties and check helper instance
	 */
	public function __construct() {
		$helperName = Mage::getStoreConfig('stockcheck_config/stockcheck_group/helper_name');
		$this->_sProductIdentifierName	= Mage::getStoreConfig('stockcheck_config/stockcheck_group/product_identifier_name');
		$this->_bExtensionActive		= Mage::getStoreConfig('stockcheck_config/stockcheck_group/disable_ext');
		$this->_oHelper					= Mage::Helper($helperName);
		if (!($this->_oHelper instanceof Sitewards_StockCheck_Helper_Interface)) {
			Mage::throwException(
				Mage::Helper('stockcheck')->__('StockCheck extension not correctly setup. Helper %s should be instance of Sitewards_StockCheck_Helper_Interface.', $helperName)
			);
		}
		parent::__construct();
	}

	/**
	 *
	 * @return int stock level from external source or Mage_Catalog_Model_Product
	 */
	public function getQty() {
		if($this->_bExtensionActive == true) {
			$strProductIdentifierValue = $this->_getData($this->_sProductIdentifierName);
			$intCustomQty = $this->_oHelper->getCustomQuantity($strProductIdentifierValue);
			if(!is_null($intCustomQty)) {
				return $intCustomQty;
			} else {
				parent::getQty();
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
		if($this->_bExtensionActive != true) {
			return;
		}
		$strProductIdentifierValue = $this->_getData($this->_sProductIdentifierName);

		$intStorageTypeId = $this->_oHelper->getStorageType($strProductIdentifierValue, $intQuantity);

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