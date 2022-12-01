<?php
namespace Zineone\Z1Connector\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Save implements ButtonProviderInterface {
    /**
     * Get product's special price
     *
     * @return mixed
     */
    public function getButtonData() {
        return ['label' => __('Save'), 'class' => 'save primary', 'data_attribute' => ['mage-init' => ['button' => ['event' => 'save']], 'form-role' => 'save', ], 'sort_order' => 90, ];
    }
}