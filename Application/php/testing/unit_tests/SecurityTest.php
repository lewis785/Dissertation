<?php

require_once(dirname(__FILE__) . "/../../core/classes/Security.php");

class SecurityTest extends PHPUnit_Framework_TestCase
{
    private $security;

    protected function setUp()
    {
        $this->security = new Security();
    }

    public function hasDataProvider() {
        $access_array = ["student", "lab helper", "lecturer", "admin"];
        $output_array = [];
        foreach ($access_array as $index1 => $current)
        {
            foreach ($access_array as $index2 => $required)
                array_push($output_array, array($current, $required, $index1>=$index2));
            array_push($output_array, array($current, "fake access", false));
            array_push($output_array, array("fakse access", $current, false));
        }
        return $output_array;
    }

    public function greaterThanDataProvider() {
        $access_array = ["student", "lab helper", "lecturer", "admin"];
        $output_array = [];
        foreach ($access_array as $index1 => $current)
        {
            foreach ($access_array as $index2 => $required)
                array_push($output_array, array($current, $required, $index1>$index2));
            array_push($output_array, array($current, "fake access", false));
            array_push($output_array, array("fakse access", $current, false));
        }
        return $output_array;
    }

    /**
     * @dataProvider hasDataProvider
     */
    public function testHasAccessLevel($user_access, $required_acess, $expected)
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION["accesslevel"] = $this->security->getAccessValue($user_access);

        $result = $this->security->hasAccessLevel($required_acess);
        $this->assertEquals($expected, $result, $user_access . " and " . $required_acess . ": Failed");
    }


    /**
     * @dataProvider greaterThanDataProvider
     */
    public function testHasGreaterAccessThan($a,$b,$expected)
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION["accesslevel"] = $this->security->getAccessValue($a);

        $result = $this->security->hasGreaterAccessThan($b);
        $this->assertEquals($expected, $result, $a . " and " . $b . ": Failed");
    }

}
