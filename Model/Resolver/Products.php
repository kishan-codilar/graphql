<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Codilar\VendorGraphQl\Model\Resolver;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogGraphQl\Model\Resolver\Products\Query\ProductQueryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;



/**
 * Products field resolver, used for GraphQL request processing.
 */
class Products implements ResolverInterface
{
    /**
     * @var ProductQueryInterface
     */
    private $searchQuery;

    /**
     * @var SearchCriteriaBuilders
     */
    private $searchCriteriaBuilders;

    /**
     * @param PProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(

        \Magento\Framework\Api\SearchCriteriaBuilder                   $searchCriteriaBuilders,

        ProductRepositoryInterface                                    
         $productRepository

    )
    {
        $this->searchCriteriaBuilders = $searchCriteriaBuilders;
        $this->productRepository = $productRepository;


    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field       $field,
                    $context,
        ResolveInfo $info,
        array       $value = null,
        array       $args = null
    )
    {
        $searchCriteriaFilter = $this->searchCriteriaBuilders->addFilter('vendor', $args['id'], 'eq')->create();
        $productCollection = $this->productRepository->getList($searchCriteriaFilter);
        $productRecord['items'] = [];
        foreach ($productCollection->getItems() as $VendorProduct) {
            $productId = $VendorProduct->getId();
            $productRecord['items'][$productId] = $VendorProduct->getData();
            $productRecord['items'][$productId] ['model'] = $VendorProduct;
        }

        return $productRecord;
    }

}