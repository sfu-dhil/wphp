<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Manage Orlando records.
 */
class OrlandoManager {
    final public const DELIM = ' || ';

    /**
     * Get the named field for the Orlando data.
     *
     * @param null|string $data
     * @param string $name
     *
     * @return array
     */
    public function getField($data, $name = 'standard') {
        $name = mb_strtolower($name);
        $fields = [];
        if ( ! $data) {
            return $fields;
        }
        $records = explode(' %%% ', $data);

        foreach ($records as $record) {
            $rows = explode(self::DELIM, $record);

            foreach ($rows as $row) {
                list($key, $value) = explode(' = ', $row);
                if (mb_strtolower($key) === $name) {
                    $fields[] = $value;
                }
            }
        }

        return $fields;
    }
}
