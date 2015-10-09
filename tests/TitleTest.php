<?php
require_once 'vivo.php';
require_once 'vivo_card.php';

/**
* SPARQL Learning materials:
*
*   http://www.cambridgesemantics.com/semantic-university/sparql-by-example
*   http://www.w3.org/TR/sparql11-query/#optionals
*   https://jena.apache.org/tutorials/sparql_data.html
*/

class TitleTest extends PHPUnit_Framework_TestCase {
    // The goal of this test is to verify that we do not
    // assign the title "Graduate Research Professor" to profiles without a title

    function test_get_card_data() {

        // Has title
        $personURI = "http://vivo.ufl.edu/individual/n25562";
        $data = VivoCard::get_card_data($personURI);
        $this->assertEquals('VIVO Project Director', $data['vivoTitle']);

        // Has no title
        $personURI = "http://vivo.ufl.edu/individual/n5623984433";
        $data = VivoCard::get_card_data($personURI);
        $this->assertEquals('', $data['vivoTitle']);
    }
}
