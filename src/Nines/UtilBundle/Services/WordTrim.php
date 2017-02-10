<?php

namespace Nines\UtilBundle\Services;

use Monolog\Logger;

class WordTrim {
    
    /**
     * @var Logger
     */
    private $logger;
    
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
        $converted = mb_convert_encoding($plain, 'UTF-8', 'HTML-ENTITIES');
        $trimmed = preg_replace("/(^\s+)|(\s+$)/u", "", $converted);
        // \xA0 is the result of converting nbsp.
        $normalized = preg_replace("/[[:space:]\x{A0}]/su", " ", $trimmed);
        $words = preg_split('/\s/u', $normalized, $length+1, PREG_SPLIT_NO_EMPTY);

        if(count($words) <= $length) {
            return implode(' ', $words);
        }
        return implode(' ', array_slice($words, 0, $length)) . $suffix;
    }
    
}