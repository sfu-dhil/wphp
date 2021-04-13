<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

/**
 * Manage Orlando records.
 */
class OrlandoManager
{
    public const DELIM = ' || ';

    /**
     * Get the named field for the Orlando data.
     *
     * @param string $data
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
