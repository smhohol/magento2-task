<?php declare(strict_types=1);

namespace Shohol\TestTask\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
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
     * CheckTrustedRepository constructor
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface   $customerRepository
     * @param \Shohol\TestTask\Api\Data\CheckTrustedInterface   $trustedField
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CheckTrustedInterface $trustedField
    ) {
        $this->customerRepository = $customerRepository;
        $this->trustedField = $trustedField;
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
        $objectManager = ObjectManager::getInstance();

        /** @var \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository */
        $customerRepository = $objectManager->get(CustomerRepositoryInterface::class);

        /** @var \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $objectManager->get(SearchCriteriaBuilder::class);

        $searchCriteria = $searchCriteriaBuilder
            /* ->addFilter('email', 'zzz@zzz.ru') */
            ->addFilter(CheckTrustedInterface::TRUSTED, '1')
            ->addFilter('created_at', (new \DateTime())->modify('today')->getTimestamp(), 'gteq')
            ->create();

        $customersList = $customerRepository->getList($searchCriteria);

        $customerIds = [];
        foreach ($customersList->getItems() as $customer) {
            $customerIds[] = (int) $customer->getId();
        }

        // да, знаю, что здесь нужно как-то через interface через doc-блок реализовать и тогда сам движок все отправит.
        // Но, к сожалению, пока нет четкого понимания, как эта "магия" точно работает.
        header('Content-type: application/json');
        return json_encode($customerIds);
    }
}
