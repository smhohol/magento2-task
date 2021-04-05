<?php

namespace Shohol\TestTask\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


/**
 * Class Recurring
 *
 * @category Model/Data
 * @package  Shohol\TestTask\Setup
 */
class Recurring implements InstallSchemaInterface
{
    /**
     * Create trusted field in database.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup SchemaSetupInterface
     * @param \Magento\Framework\Setup\ModuleContextInterface $context ModuleContextInterface
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $setup->getTable('customer_entity'),
            'trusted',
            [
                'type' => Table::TYPE_BOOLEAN,
                'comment' => 'Trusted',
                'nullable' => true,
            ]
        );

        $installer->endSetup();
    }
}
