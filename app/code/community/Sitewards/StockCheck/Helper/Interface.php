<?php
/**
 *
 * @category    Sitewards
 * @package     Sitewards_StockCheck
 * @copyright   Copyright (c) 2013 Sitewards GmbH (http://www.sitewards.com/)
 *
 * Interface for helper to help the Sitewards_StockCheck_Model_Product class
 */
interface Sitewards_StockCheck_Helper_Interface
{
	/**
	 *
	 * @param	int|string $mxdProductSku unique identifier for a product - defaults to Magento ProductId
	 * @return	int real time stock level
	 */
	public function getCustomQuantity($mxdProductSku);

	/**
	 *
	 * @param	int|string $mxdProductSku unique identifier for a product - defaults to Magento ProductId
	 * @return	int constant related to real time stock level
	 */
	public function getStorageType($mxdProductSku);

	/**
	 *
	 * @param	int|string $mxdProductSku unique identifier for a product - defaults to Magento ProductId
	 * @return	int current amount of stock taking into account the items on order
	 */
	public function getProductsStockOrder($mxdProductSku);
}
