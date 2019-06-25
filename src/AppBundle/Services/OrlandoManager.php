<?php

namespace AppBundle\Services;

/**
 * Manage Orlando records.
 */
class OrlandoManager {

    const DELIM = ' || ';

    /**
     * Get the named field for the Orlando data.
     *
     * @param string $data
     * @param string $name
     *
     * @return array
     */
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
