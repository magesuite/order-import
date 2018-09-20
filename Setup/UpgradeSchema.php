<?php

namespace MageSuite\OrderImport\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function upgrade(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $tableName = $setup->getTable('orderimport_log');

            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'imported_filename' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => false,
                        'size' => 255,
                        'comment' => 'Imported filename',
                        'default' => ''
                    ]
                ];

                $connection = $setup->getConnection();

                foreach ($columns as $name => $definition) {
                    if(!$connection->tableColumnExists($tableName, $name)) {
                        $connection->addColumn($tableName, $name, $definition);
                    }
                }
            }
        }

        $setup->endSetup();
    }


}