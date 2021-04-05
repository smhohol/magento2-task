<?php declare(strict_types=1);

namespace Shohol\TestTask\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Shohol\TestTask\Api\CheckTrustedRepositoryInterface;
use Shohol\TestTask\Api\Data\CheckTrustedInterface;

/**
 * Class CheckTrusted
 *
 * @category Model/Data
 * @package  Shohol\TestTask\Observer
 */
class CheckTrusted implements ObserverInterface
{
    private $checkTrusted;
    private $checkTrustedRepository;
    private $logger;

    public function __construct(
        CheckTrustedInterface $checkTrusted,
        CheckTrustedRepositoryInterface $checkTrustedRepository,
        LoggerInterface $logger
    ) {
        $this->checkTrusted = $checkTrusted;
        $this->checkTrustedRepository = $checkTrustedRepository;
        $this->logger = $logger;
    }


    /**
     * Execute observer method
     *
     * @param \Magento\Framework\Event\Observer $observer Observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $customerEmail = $observer->getEvent()->getCustomer()->getEmail();

        $isTrusted = false;
        if (mb_stripos($customerEmail, 'r') === 0) {
            $isTrusted = true;
        }

        $this->checkTrustedRepository->saveTrustedField($customerEmail, $this->checkTrusted->setTrusted($isTrusted));
    }
}
