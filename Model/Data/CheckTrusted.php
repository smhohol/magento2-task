<?php declare(strict_types=1);

namespace Shohol\TestTask\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use Shohol\TestTask\Api\Data\CheckTrustedInterface;

/**
 * Class CheckTrusted
 *
 * @category Model/Data
 * @package  Shohol\TestTask\Model\Data
 */
class CheckTrusted extends AbstractExtensibleObject implements CheckTrustedInterface
{
    /**
     * Get trusted
     *
     * @return bool|null
     */
    public function getTrusted(): bool
    {
        return $this->_get(self::TRUSTED);
    }

    /**
     * Set trusted
     *
     * @param bool|null $trusted Trusted
     *
     * @return CheckTrustedInterface
     */
    public function setTrusted(bool $trusted = null): CheckTrustedInterface
    {
        return $this->setData(self::TRUSTED, $trusted);
    }
}
