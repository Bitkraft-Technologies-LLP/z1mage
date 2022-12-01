<?php
namespace Zineone\Z1Connector\Common;

use \Magento\Framework\App\Helper\AbstractHelper;

class Z1Utilities extends AbstractHelper
{
    /**
     * Get Product Category
     *
     * @param ModuleDataSetupInterface $product
     */
    public function getProductCategory($product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryArray = [];
        $categories = $product->getCategoryIds();
        foreach ($categories as $category) {
            $cat = $objectManager->create(\Magento\Catalog\Model\Category::class)->load($category);
            array_push($categoryArray, $cat->getName());
        }
        return implode(":", $categoryArray);
    }
    /**
     * Get Product Original Price
     *
     * @param string $product
     * @return $regularPrice
     */
    public function getProductOriginalPrice($product)
    {
        $originalPrice = 0;
        $regularPrice = $product->getPriceInfo()->getPrice('regular_price')->getValue();
        if ($product->getTypeId() == 'configurable') {
            $basePrice = $product->getPriceInfo()->getPrice('regular_price');
            $regularPrice = $basePrice->getMinRegularAmount()->getValue();
        }
        if ($product->getTypeId() == 'bundle') {
            $regularPrice = $product->getPriceInfo()->getPrice('regular_price')->getMinimalPrice()->getValue();
        }
        if ($product->getTypeId() == 'grouped') {
            $usedProds = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($usedProds as $child) {
                if ($child->getId() != $product->getId()) {
                    $regularPrice+= $child->getPrice();
                }
            }
        }
        return $regularPrice;
    }
    /**
     * Get Product Special Price
     *
     * @param string $product
     * @return $specialPrice
     */
    public function getProductSpecialPrice($product)
    {
        $specialPrice = 0;
        $specialPrice = $product->getPriceInfo()->getPrice('special_price')->getValue();
        if ($product->getTypeId() == 'configurable') {
            $specialPrice = $product->getFinalPrice();
        }
        if ($product->getTypeId() == 'bundle') {
            $specialPrice = $product->getPriceInfo()->getPrice('final_price')->getMinimalPrice()->getValue();
        }
        if ($product->getTypeId() == 'grouped') {
            $usedProds = $product->getTypeInstance(true)->getAssociatedProducts($product);
            foreach ($usedProds as $child) {
                if ($child->getId() != $product->getId()) {
                    $specialPrice+= $child->getFinalPrice();
                }
            }
        }
        return $specialPrice;
    }
}
