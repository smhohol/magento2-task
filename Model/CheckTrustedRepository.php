<?php declare(strict_types=1);

namespace Shohol\TestTask\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
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
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * CheckTrustedInterface
     *
     * @var CheckTrustedInterface
     */
    protected $trustedField;

    /**
     * CheckTrustedRepository constructor
     *
     * @param CustomerRepositoryInterface $customerRepository CustomerRepositoryInterface
     * @param CheckTrustedInterface   $trustedField   CheckTrustedInterface
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
     * @param CheckTrustedInterface $trustedField Trusted field
     *
     * @return CheckTrustedInterface
     * @throws CouldNotSaveException
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
     * @param Customer $customer Customer
     *
     * @return CheckTrustedInterface
     * @throws NoSuchEntityException
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
}
