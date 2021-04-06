<?php declare(strict_types=1);

namespace Shohol\TestTask\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;

class CustomerGetListOverride
{
    /**
     * @var string
     */
    protected $excludedCustomerId = '30';

    /**
     * @var \Magento\Framework\Api\Search\FilterGroup
     */
    protected $filterGroup;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     */
    public function __construct(
        FilterGroup $filterGroup,
        FilterBuilder $filterBuilder
    ) {
        $this->filterGroup = $filterGroup;
        $this->filterBuilder = $filterBuilder;
    }


    /**
     * Executed before CustomerRepository getList() function
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $subject
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface
     */
    public function beforeGetList(CustomerRepositoryInterface $subject, SearchCriteriaInterface $searchCriteria)
    {
        $this->filterGroup->setFilters(
            [
                $this->filterBuilder
                    ->setField('entity_id')
                    ->setConditionType('neq')
                    ->setValue($this->excludedCustomerId)
                    ->create()
            ]
        );

        $searchCriteria->setFilterGroups([$this->filterGroup]);

        return $searchCriteria;
    }
}
