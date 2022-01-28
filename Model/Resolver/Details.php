<?php


namespace Codilar\VendorGraphQl\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Codilar\Vendor\Model\Vendor;
use Codilar\Vendor\Model\ResourceModel\Vendor\CollectionFactory;

class Details implements ResolverInterface
{
    /**
     * @var ValueFactory
     */
    private $valueFactory;

     /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Details constructor.
     * @param ValueFactory $valueFactory
     */

 

    public function __construct(
        ValueFactory $valueFactory,
        CollectionFactory $collectionFactory
    )
    {
        $this->valueFactory = $valueFactory;
        $this->collectionFactory = $collectionFactory;
        
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return Value|mixed
     * @throws \Exception
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        try {
            $data = $this->getVendorData();
            $result = function () use ($data) {
                return !empty($data) ? $data : [];
            };
            return $this->valueFactory->create($result);
        } catch (\Exception $exception) {
            throw new \Exception(__($exception->getMessage()));
        }
    }

    /**
     *  Getting data array using data Collection
     */
    private function getVendorData() {

        $collections = $this->collectionFactory->create();
        $collections = $collections->getItems();
        return $collections;
 //or directly put below code
    // $collections = $this->collectionFactory->create()->getItems();

    // return $collections;
    }

}

