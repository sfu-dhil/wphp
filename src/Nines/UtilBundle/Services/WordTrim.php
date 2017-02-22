<?php

namespace Nines\UtilBundle\Services;

use Monolog\Logger;

/**
 * Word trimming service for Symfony.
 */
class WordTrim {
    
    /**
     * Monolog logger.
     * 
     * @var Logger
     */
    private $logger;

    /**
     * Set the service's logger.
     * 
     * @param Logger $logger
     */
    public function setLogger(Logger $logger) {
        $this->logger = $logger;
    }
    
    /**
     * Strip tags from HTML and then trim it to a number of words.
     * 
     * @param string $string
     * @param string $length
     * @param string $suffix
     * @return string
     */
    public function trim($string, $length, $suffix = '...') {        
        $plain = strip_tags($string);
        $converted = html_entity_decode($plain, ENT_COMPAT | ENT_HTML401, 'UTF-8');
        $trimmed = preg_replace("/(^\s+)|(\s+$)/u", "", $converted);
        // \xA0 is the result of converting nbsp. Requires the /u flag.
        $normalized = preg_replace("/[[:space:]\x{A0}]/su", " ", $trimmed);
        $words = preg_split('/\s/u', $normalized, $length+1, PREG_SPLIT_NO_EMPTY);

        if(count($words) <= $length) {
            return implode(' ', $words);
        }
        return implode(' ', array_slice($words, 0, $length)) . $suffix;
    }
}