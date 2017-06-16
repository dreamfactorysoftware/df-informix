<?php

namespace DreamFactory\Core\Informix\Database\Query\Processors;

use DreamFactory\Core\Informix\Database\Query\Grammars\InformixGrammar;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;

class InformixProcessor extends Processor
{
    /**
     * Process an "insert get ID" query.
     *
     * @param  Builder $query
     * @param  string  $sql
     * @param  array   $values
     * @param  string  $sequence
     *
     * @return int/array
     */
    public function processInsertGetId(Builder $query, $sql, $values, $sequence = null)
    {
        $sequenceStr = $sequence ?: 'id';
        if (is_array($sequence)) {
            $grammar = new InformixGrammar;
            $sequenceStr = $grammar->columnize($sequence);
        }
        $sql = 'select "' . $sequenceStr . '" from new table (' . $sql;
        $sql .= ')';
        $results = $query->getConnection()->select($sql, $values);
        if (is_array($sequence)) {
            return array_values((array)$results[0]);
        }

        $result = (array)$results[0];
        $id = $result[$sequenceStr];

        return is_numeric($id) ? (int)$id : $id;
    }
}
