<?php
// Test case for  vivo.php functions
// @author Andrei Sura
//
// Execution:
//  phpunit VivoToolsTest.php
//  or
//  make test

require_once 'vivo.php';
require_once 'vivo_card.php';

class VivoFunctionsTest extends PHPUnit_Framework_TestCase {

    function test_get_person_id_from_url() {
        $urls = array(
           'bill_hogan' => array(
                'in' => 'https://vivo.ufl.edu/display/n3705822167',
                'out'=> 'n3705822167',
            ),
           'bill_hogan_slash' => array(
                'in' => 'https://vivo.ufl.edu/display/n3705822167/',
                'out'=> 'n3705822167',
            ),
           'bill_hogan_http' => array(
                'in' => 'http://vivo.ufl.edu/display/n3705822167/',
                'out'=> 'n3705822167',
            ),
           'bill_hogan_no_scheme' => array(
                'in' => 'vivo.ufl.edu/display/n3705822167',
                'out'=> 'n3705822167',
            ),
            'elizabeth_shenkman' => array(
                'in' => 'http://vivo.ufl.edu/display/n62720',
                'out'=> 'n62720',
            ),
            'empty' => array(
                'in' => '',
                'out' => '',
            ),
            'slash_ended' => array(
                'in' => '/',
                'out' => '',
            )
        );

        foreach($urls as $data) {
            $result = VivoCard::get_person_id_from_url($data['in']);
            $this->assertEquals($data['out'], $result);
            // print("\n Got [$result] from: " .$data['in']);
        }
    }

    function test_getVIVOPersonData() {
        $url = 'https://vivo.ufl.edu/display/n3705822167';
        $data = getVIVOPersonData($url);
        // print_r($data);
    }

    function test_format_card() {
        # Test no data
        $data = array(
            'vivoImage' => '',
            'vivoName' => '',
            'vivoTitle' => '',
            'vivoDepartment' => '',
            'vivoPhone' => '',
            'vivoFax' => '',
            'vivoEmail' => '',
            'vivoLink' => '',
        );
        $result = VivoCard::format_card($data);
        $this->assertEquals('<div id="vivoPerson"></div>', $result);

        # Test some data
        $data = array(
            'vivoImage' => 'http://school.edu/img.png',
            'vivoName' => 'the name',
            'vivoTitle' => 'the title',
            'vivoDepartment' => 'the department',
            'vivoPhone' => 'the phone',
            'vivoFax' => 'the fakS',
            'vivoEmail' => 'test@school.edu',
            'vivoLink' => 'http://school.edu',
        );
        $result = VivoCard::format_card($data);
        $expected = <<<HTML
<div id="vivoPerson"><img class="vivoImage" src="http://school.edu/img.png" alt="no image"><div class="vivoName">the name</div><div class="vivoTitle">the title</div><div class="vivoDepartment">the department</div><div class="vivoPhone">Phone: the phone</div><div class="vivoFax">Fax: the fakS</div><div class="vivoEmail"><a href="mailto:test@school.edu">test@school.edu</a></div><div class="vivoLink"><a href="http://school.edu">http://school.edu</a></div></div>
HTML;
        $this->assertEquals(trim($expected), trim($result));
    }

}
