<?php
namespace Zineone\Z1Connector\Plugin;

class ConfigurableView
{
    /**
     * @var $z1Utilities
     */
    protected $z1Utilities;
    /**
     * @param \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities
     */
    public function __construct(\Zineone\Z1Connector\Common\Z1Utilities $z1Utilities)
    {
        $this->z1Utilities = $z1Utilities;
    }
    /**
     * Execute after getting json config
     *
     * @param \Magento\Swatches\Block\Product\Renderer\Configurable $subject
     * @param String $result
     */
    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {
        $jsonResult = json_decode($result, true);
        $jsonResult['skus'] = [];
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $dataObj = [];
            $dataObj["category"] = $this->z1Utilities->getProductCategory($simpleProduct);
            $dataObj["id"] = $simpleProduct->getId();
            $dataObj["sku"] = $simpleProduct->getSku();
            $dataObj["name"] = $simpleProduct->getName();
            $dataObj["original_price"] = $this->z1Utilities->getProductOriginalPrice($simpleProduct);
            $dataObj["sale_price"] = $this->z1Utilities->getProductSpecialPrice($simpleProduct);
            $jsonResult['skus'][$simpleProduct->getId() ] = $dataObj;
        }
        $result = json_encode($jsonResult);
        return $result;
    }
}
