<?php

declare (strict_types = 1);

namespace Codilar\VendorGraphQl\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Api\SearchCriteriaInterface;
use Codilar\Vendor\Model\Vendor;
use Codilar\Vendor\Model\ResourceModel\Vendor\CollectionFactory;


class Products implements ResolverInterface {

     /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    public function __construct(
        ProductRepository $productRepository,
        SearchCriteriaInterface $searchCriteriaInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionFactory $collectionFactory
    ) {
       $this->productRepository=$productRepository;
       $this->searchCriteriaInterface=$searchCriteriaInterface;
       $this->searchCriteriaBuilder=$searchCriteriaBuilder;
       $this->collectionFactory = $collectionFactory;
    }
    /**
     * @inheritdoc
     */
    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
       $vendorId = $this->getVendorId($args);
       $productData = $this->getProductData($vendorId);
       return $productData;
    }
    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getVendorId(array $args): int {
       if (!isset($args['vendor_id'])) {
           throw new GraphQlInputException(__('Vendor id should be specified'));
       }
       return (int) $args['vendor_id'];
    }
    /**
     * @param int $vendorId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    private function getProductData(int $vendorId): array
    {
        $searchCriteriaFilter = $this->searchCriteriaBuilder->addFilter('vendor', $vendorId, 'eq')->create();
        $productCollection = $this->productRepository->getList($searchCriteriaFilter);
        return $productCollection->getItems();
 
    }
}

