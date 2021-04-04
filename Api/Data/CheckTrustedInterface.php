<?php declare(strict_types=1);

namespace Shohol\TestTask\Api\Data;

/**
 * Interface CheckTrustedInterface
 *
 * @category Api/Data/Interface
 * @package  Shohol\TestTask\Api\Data
 */
interface CheckTrustedInterface
{
    const TRUSTED = 'trusted';

    /**
     * Get trusted
     *
     * @return bool|null
     */
    public function getTrusted(): bool;

    /**
     * Set trusted
     *
     * @param bool|null $trusted Trusted
     *
     * @return CheckTrustedInterface
     */
    public function setTrusted(bool $trusted = null): CheckTrustedInterface;
}
