<?php

namespace Shohol\TestTask\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


class Recurring implements InstallSchemaInterface
{
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
