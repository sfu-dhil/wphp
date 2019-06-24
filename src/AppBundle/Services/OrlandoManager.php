<?php

namespace AppBundle\Services;

/**
 * Description of OrlandoManager
 *
 *
 */
class OrlandoManager {

    const DELIM = ' || ';

    public function getField($data, $name = 'standard') {
        $name = strtolower($name);
        $fields = [];
        if (!$data) {
            return $fields;
        }
        $records = explode(' %%% ', $data);
        foreach ($records as $record) {
            $rows = explode(self::DELIM, $record);
            foreach ($rows as $row) {
                list($key, $value) = explode(" = ", $row);
                if (strtolower($key) === $name) {
                    $fields[] = $value;
                }
            }
        }
        return $fields;
    }

}
