<?php declare(strict_types=1);

namespace Shohol\TestTask\Api;

use Magento\Customer\Model\Customer;
use Shohol\TestTask\Api\Data\CheckTrustedInterface;

/**
 * Interface CheckTrustedRepositoryInterface
 *
 * @category Api/Interface
 * @package  Shohol\TestTask\Api
 */
interface CheckTrustedRepositoryInterface
{
    /**
     * Save trusted field
     *
     * @param string $customerEmail Customer email
     * @param \Shohol\TestTask\Api\Data\CheckTrustedInterface $trustedField Trusted field
     *
     * @return \Shohol\TestTask\Api\Data\CheckTrustedInterface
     */
    public function saveTrustedField(string $customerEmail, CheckTrustedInterface $trustedField): CheckTrustedInterface;

    /**
     * Get trusted field
     *
     * @param \Magento\Customer\Model\Customer $customer Customer
     *
     * @return \Shohol\TestTask\Api\Data\CheckTrustedInterface
     */
    public function getTrustedField(Customer $customer): CheckTrustedInterface;

    /**
     * Get all customers Ids, which have the property = true and which registered in the last days
     *
     * @return string
     */
    public function getAllTrustedCustomers();
}
