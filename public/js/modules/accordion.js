import Accordion from "../../yarn/dhilux/js/accordion";

(function(){
   const accordions = [...document.querySelectorAll('details')].map(detail => new Accordion(detail));
}());