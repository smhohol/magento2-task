<?php declare(strict_types=1);

namespace Shohol\TestTask\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Shohol\TestTask\Api\CheckTrustedRepositoryInterface;
use Shohol\TestTask\Api\Data\CheckTrustedInterface;

/**
 * Class CheckTrustedRepository
 *
 * @category Model/Repository
 * @package  Shohol\TestTask\Model
 */
class CheckTrustedRepository implements CheckTrustedRepositoryInterface
{
    /**
     * Customer repository
     *
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * CheckTrustedInterface
     *
     * @var \Shohol\TestTask\Api\Data\CheckTrustedInterface
     */
    protected $trustedField;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * CheckTrustedRepository constructor
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface   $customerRepository
     * @param \Shohol\TestTask\Api\Data\CheckTrustedInterface   $trustedField
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CheckTrustedInterface $trustedField,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerRepository = $customerRepository;
        $this->trustedField = $trustedField;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Save checkout custom fields
     *
     * @param string $customerEmail Customer email
     * @param \Shohol\TestTask\Api\Data\CheckTrustedInterface $trustedField Trusted field
     *
     * @return \Shohol\TestTask\Api\Data\CheckTrustedInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException If customer with the specified email does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveTrustedField(string $customerEmail, CheckTrustedInterface $trustedField): CheckTrustedInterface
    {
        $customer = $this->customerRepository->get($customerEmail);

        try {
            $customer->setData(
                CheckTrustedInterface::TRUSTED,
                $trustedField->getTrusted()
            );
            $customer->setData('first_failure', time());

            $this->customerRepository->save($customer);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Custom field Trusted could not be saved!'));
        }

        return $trustedField;
    }

    /**
     * Get trusted custom field by given customer id
     *
     * @param \Magento\Customer\Model\Customer $customer Customer
     *
     * @return \Shohol\TestTask\Api\Data\CheckTrustedInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTrustedField(Customer $customer): CheckTrustedInterface
    {
        if (!$customer->getId()) {
            throw new NoSuchEntityException(__('Customer with id %1 does not exist', $customer));
        }

        $this->trustedField->setTrusted(
            $customer->getData(CheckTrustedInterface::TRUSTED)
        );

        return $this->trustedField;
    }

    /**
     * Get all customers Ids, which have the property = true and which registered in the last days
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAllTrustedCustomers()
    {
        $filters[] = $this->filterBuilder
            ->setField(CheckTrustedInterface::TRUSTED)
            ->setConditionType('eq')
            ->setValue('1')
            ->create();

        $filters[] = $this->filterBuilder
            ->setField('created_at')
            ->setConditionType('gteq')
            ->setValue((new \DateTime())->modify('today')->getTimestamp())
            ->create();

        $this->searchCriteriaBuilder->addFilters($filters);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $customersList = $this->customerRepository->getList($searchCriteria);

        $customerIds = [];
        foreach ($customersList->getItems() as $customer) {
            $customerIds[] = (int) $customer->getId();
        }

        // да, знаю, что здесь нужно как-то через interface через doc-блок реализовать и тогда сам движок все отправит.
        // Но, к сожалению, пока нет четкого понимания, как эта "магия" точно работает.
        return json_encode($customerIds);
    }
}
