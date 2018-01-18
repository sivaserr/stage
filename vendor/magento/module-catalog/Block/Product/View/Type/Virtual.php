<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Simple product data view
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Catalog\Block\Product\View\Type;

class Virtual extends \Magento\Catalog\Block\Product\View\AbstractView
{
  /*  protected $_stockRegistry;

public function __construct(\Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry)
{
    $this->_stockRegistry = $stockRegistry;
}

public function getStockItem($productId)
{
    return $this->_stockRegistry->getStockItem($productId);
}*/

public function hello(){
    $hello = "hello";
    echo $hello;
}

}
