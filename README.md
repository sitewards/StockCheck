Sitewards_StockCheck
=======

This extension provides an easy to use framework for overriding the standard Magento Product stock model.
It is aimed at developers who want to load the stock level of products in realtime from another source.
* Allows for custom Product Identifier,
* Allows you to specify a custom helper class outside of the StockCheck extension,
* Provides 4 defualt levels of stock, including text and images,

Functions Provided
------------------

The following skeleton functions are provided in the Sitewards_StockCheck_Helper_Data to allow you to extend and use in your Magento instance:
* getCustomQuantity
	* This function is called in the Product object getQty call,
	* The aim of this function is to return an integer value to represent the real time stock level,
* getStorageType
	* This function is called in the Product obkect getStockAsLight call,
	* The aim of this function is to return an integer constant related to one of the default stock levels,
* getProductsStockOrder
	* This function is called in the Product obkect getStockAsLight call,
	* The aim of this function is to return an integer value of the current amount of stock taking into account the items currently on order,

Please note that this is a framework and requires development to fit into Magento.

author: Sitewards Development Team, 12/2012
contact: http://www.sitewards.com