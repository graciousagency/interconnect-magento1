<?php

/**
 * Class Gracious_Interconnect_Http_Request_Data_Order_Item_Factory
 */
class Gracious_Interconnect_Http_Request_Data_Order_Item_Factory extends Gracious_Interconnect_Http_Request_Data_FactoryAbstract
{
    /**
     * @return array
     */
    public function setupData(Mage_Sales_Model_Order $order) {
        $rows = [];
        $orderItems = $order->getAllVisibleItems();

        foreach ($orderItems as $orderItem) {
            /* @var $orderItem Mage_Sales_Model_Order_Item */
            /* @var $product Mage_Catalog_Model_Product */ $product = $orderItem->getProduct();

            if($product !== null) { // Redundancy (Could $product be null?)
                $productTypeId = $product->getTypeId();
//                Mage::log('$productTypeId : '.$productTypeId);

                switch ($productTypeId) {
                    case Gracious_Interconnect_Support_ProductType::SIMPLE:
                    case Gracious_Interconnect_Support_ProductType::VIRTUAL:
                    case Gracious_Interconnect_Support_ProductType::DOWNLOADABLE:
                        $rows[] = $this->setupOrderItemData($order, $orderItem, $product);

                        break;
                }
            }
        }

        return $rows;
    }

    /**
     * @param Mage_Sales_Model_Order_Item $orderItem
     * @return string[]
     */
    protected function setupOrderItemData(Mage_Sales_Model_Order $order, Mage_Sales_Model_Order_Item $orderItem, Mage_Catalog_Model_Product $product)
    {
        /* @var $imageHelper Mage_Catalog_Helper_Image */ $imageHelper = Mage::helper('catalog/image');
        $imageHelper->init($product, 'category_page_list');

        return [
            'emailAddress'      => $order->getCustomerEmail(),
            'orderId'           => $this->generateEntityId($order->getId(),Gracious_Interconnect_Support_EntityType::ORDER),
            'itemId'            => $this->generateEntityId($orderItem->getEntityId(), Gracious_Interconnect_Support_EntityType::ORDER_ITEM),
            'incrementId'       => $order->getIncrementId(),
            'productId'         => $this->generateEntityId($product->getId(),Gracious_Interconnect_Support_EntityType::PRODUCT),
            'productName'       => $product->getName(),
            'productSKU'        => $product->getSku(),
            'category'          => $this->getCategoryNameByProduct($product),
            'subCategory'       => null,
            'quantity'          => (int)$orderItem->getQtyOrdered(),
            'priceInCents'      => Gracious_Interconnect_Support_PriceCents::create($product->getPrice())->toInt(),
            'totalPriceInCents' => Gracious_Interconnect_Support_PriceCents::create($orderItem->getQtyOrdered() * $product->getPrice())->toInt(),
            'orderedAtISO8601'  => Gracious_Interconnect_Support_Formatter::formatDateStringToIso8601($order->getCreatedAt()),
            'productUrl'        => $product->getProductUrl(),
            'productImageUrl'   => $imageHelper->getUrl()
        ];
    }

    /**
     * @param $product Mage_Catalog_Model_Product
     * @return string
     */
    protected function getCategoryNameByProduct(Mage_Catalog_Model_Product $product){
        $categoryNames = [];
        $categories = $product->getCategoryCollection()->addAttributeToSelect("name");

        foreach($categories as $category) {
            /* @var $category Mage_Catalog_Model_Category */
            $categoryNames[] = $category->getName();
        }

        return implode('-', $categoryNames);
    }
}