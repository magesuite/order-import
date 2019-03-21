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
                $setup->getConnection()->addColumn($tableName, 'imported_filename', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'size' => 255,
                    'comment' => 'Imported filename',
                    'default' => ''
                ]);
            }
        }

        if (version_compare($context->getVersion(), '2.0.0') < 0) {
            $tableName = $setup->getTable('orderimport_log');

            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $setup->getConnection()->addColumn($tableName, 'comment', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Comment for the import',
                    'default' => ''
                ]);
            }
        }

        $setup->endSetup();
    }


}