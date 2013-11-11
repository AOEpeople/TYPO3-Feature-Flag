<?php

class Tx_FeatureFlag_System_Db_SqlFactory
{
    /**
     * @var string
     */
    const TABLE_MAPPING = 'tx_featureflag_mapping';

    /**
     * @var string
     */
    const TABLE_FLAGS = 'tx_featureflag_domain_model_featureflag';

    /**
     * @param string $table
     * @param string $column
     * @param int $enabled
     * @return string
     */
    public function getSelectStatementForContentElements($table, $column, $enabled)
    {
        $statement = "SELECT $table.uid FROM ";
        $statement .= "$table,";
        $statement .= self::TABLE_MAPPING . ",";
        $statement .= self::TABLE_FLAGS;
        $statement .= " WHERE ";
        $statement .= self::TABLE_MAPPING . ".uid_foreign = " . self::TABLE_FLAGS . ".uid";
        $statement .= " AND ";
        $statement .= "$table.uid = " . self::TABLE_FLAGS . ".uid_local";
        $statement .= " AND ";
        $statement .= self::TABLE_FLAGS . ".enabled = $enabled";
        $statement .= " AND ";
        $statement .= self::TABLE_MAPPING . ".local_table = '$table'";
        $statement .= " AND ";
        $statement .= self::TABLE_MAPPING . ".local_column = '$column'";
        return $statement;
    }

    /**
     * @param string $table
     * @return string
     */
    public function getUpdateStatementForContentElements($table)
    {
        return "UPDATE $table SET hidden = ? WHERE uid IN (?);";
    }
}