/**
 * @description Module for progressive enhancing accordions
 * @author Joey Takeda
 */

"use strict";

import Accordion from "../../node_modules/dhilux/js/accordion";

/**
 * IIFE to make accordions happen
 */
(function(){
   // Make the accordions work by passing each details element
   const accordions = [...document.querySelectorAll('details')]
                        .map(detail => new Accordion(detail));
}());