<?php

class TBT_Bss_Model_Test_Suite_Bss_Database extends TBT_Testsweet_Model_Test_Suite_Abstract {

    public function getRequireTestsweetVersion()
    {
        return '1.0.0.0';
    }

    public function getSubject()
    {
        return $this->__('Check database');
    }

    public function getDescription()
    {
        return $this->__('Check Better Store Search database for required tables and columns');
    }

    protected function generateSummary()
    {
        $cr = Mage::getSingleton('core/resource');

        $tableChecks = array();

        $tableChecks[$cr->getTableName('bss_index')] = array(
            'product_id',
            'store_id',
            'pns',
            'merged_sku',
            'merged_name',
            'tag',
            'categories',
            'category_ids',
        );

        $tableChecks[$cr->getTableName('bss_cms_index')] = array(
            'page_id',
            'store_id',
            'content'
        );

        $tableChecks[$cr->getTableName('bss_cms_result')] = array(
            'query_id',
            'page_id',
            'relevance'
        );

        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        foreach ($tableChecks as $table => $columns) {
            $query = "SHOW COLUMNS FROM $table";
            $table_schema = $read->fetchAll($query);

            $table_columns = array();
            foreach ($table_schema as $column_schema) {
                $table_columns[] = $column_schema['Field'];
            }

            if (!empty($table_columns)) {
                $this->addPass($this->__("Table %s found", $table));
            } else {
                $this->addFail($this->__("Table %s is missing", $table));
            }

            foreach ($columns as $column) {
                if (in_array($column, $table_columns)) {
                    $this->addPass($this->__("Table %s has column %s", $table,
                                    $column));
                } else {
                    $this->addFail($this->__("Table %s is missing column %s",
                                    $table, $column));
                }
            }
        }
    }
}
