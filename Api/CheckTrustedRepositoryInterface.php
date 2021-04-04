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
     * @param CheckTrustedInterface $trustedField Trusted field
     *
     * @return CheckTrustedInterface
     */
    public function saveTrustedField(string $customerEmail, CheckTrustedInterface $trustedField): CheckTrustedInterface;

    /**
     * Get trusted field
     *
     * @param Customer $customer Customer
     *
     * @return CheckTrustedInterface
     */
    public function getTrustedField(Customer $customer): CheckTrustedInterface;
}
