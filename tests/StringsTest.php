<?php

use NFePHP\Common\Strings;

class StringsTest extends \PHPUnit\Framework\TestCase
{
    const TEST_XML_PATH = '/fixtures/xml/';
    
    public function testReplaceSpecialsChars()
    {
        $txtSujo = "Esse é um código cheio de @$#$! , - . ; : / COISAS e 12093876486";
        $txtLimpo = "Esse e um codigo cheio de @ , - . ; : / COISAS e 12093876486";
        $resp = Strings::replaceSpecialsChars($txtSujo);
        $this->assertEquals($txtLimpo, $resp);
    }
    
    public function testClearXmlString()
    {
        $xmlSujo = file_get_contents(__DIR__. self::TEST_XML_PATH . 'NFe/xml-sujo.xml');
        $xmlLimpo1 = file_get_contents(__DIR__. self::TEST_XML_PATH . 'NFe/xml-limpo1.xml');
        $xmlLimpo2 = file_get_contents(__DIR__. self::TEST_XML_PATH . 'NFe/xml-limpo2.xml');
        $txtSujo = "AKJKJ >    < \n JKJS \t lkdlkd \r default:";
        $txtLimpo = "AKJKJ ><  JKJS  lkdlkd  ";
        $resp1 = Strings::clearXmlString($xmlSujo, false);
        $resp2 = Strings::clearXmlString($xmlSujo, true);
        $resp3 = Strings::clearXmlString($txtSujo);
        $this->assertEquals($xmlLimpo1, $resp1);
        $this->assertEquals($xmlLimpo2, $resp2);
        $this->assertEquals($txtLimpo, $resp3);
    }
    
    public function testClearProtocoledXML()
    {
        $xmlSujo = '';
        $xmlLimpo = '';
        $resp1 = Strings::clearProtocoledXML($xmlSujo);
        $this->assertEquals($xmlLimpo, $resp1);
    }
}