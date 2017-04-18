<?php


require_once(dirname(__FILE__) . "/../../core/classes/Security.php");
require_once(dirname(__FILE__) . "/../../courses/classes/CourseChecks.php");

class CourseChecksTest extends PHPUnit_Framework_TestCase
{

    private $security;
    private $courseCheck;

    protected function setUp()
    {
        $this->security = new Security();
        $this->courseCheck = new CourseChecks();
    }

    public function lecturerTestProvider()
    {
        $users = [array("student","student",false,false),
            array("helper","lab helper",false,false),
            array("lecturer", "lecturer",true, false),
            array("admin","admin",true, true)];
        return $users;
    }

    public function canTestProvider()
    {
        $users = [array("student","student","Software Development 1",false),
            array("helper","lab helper","Software Development 1", true),
            array("lecturer", "lecturer","Software Development 1", true),
            array("admin","admin","Software Development 1", true),
            array("student","student","Software Development 2",false),
            array("helper","lab helper","Software Development 2", true),
            array("lecturer", "lecturer","Software Development 2", false),
            array("admin","admin","Software Development 2", true),
            array("student","student","Fake Course",false),
            array("helper","lab helper","Fake Course", false),
            array("lecturer", "lecturer","Fake Course", false),
            array("admin","admin","Fake Course", true)];
        return $users;
    }

    public function helperTestProvider()
    {
        $users = [array("student","student","Software Development 1",false),
            array("helper","lab helper","Software Development 1", true),
            array("lecturer", "lecturer","Software Development 1", false),
            array("admin","admin","Software Development 1", false),
            array("student","student","Software Development 2",false),
            array("helper","lab helper","Software Development 2", true),
            array("lecturer", "lecturer","Software Development 2", false),
            array("admin","admin","Software Development 2", false),
            array("student","student","Fake Course",false),
            array("helper","lab helper","Fake Course", false),
            array("lecturer", "lecturer","Fake Course", false),
            array("admin","admin","Fake Course", false)];
        return $users;
    }

    private function setupSession($username, $access)
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION["username"] = $username;
        $_SESSION["accesslevel"] = $this->security->getAccessValue($access);
    }


    /**
     * @dataProvider lecturerTestProvider
     */
    public function testIsLecturerOfCourse($username, $access, $expected1, $expected2)
    {
        $this->setupSession($username,$access);
        self::assertEquals($this->courseCheck->isLecturerOfCourse("Software Development 1"), $expected1, "Failed: ".$username." expected ".$expected1);
        self::assertEquals($this->courseCheck->isLecturerOfCourse("Software Development 2"), $expected2, "Failed: ".$username." expected ".$expected2);
    }


    /**
     * @dataProvider helperTestProvider
     */
    public function testIsLabHelperOfCourse($username,$access,$course, $expected)
    {
        $this->setupSession($username, $access);
        self::assertEquals($this->courseCheck->isLabHelperOfCourse($course),$expected, "Failed: ".$username." expected ".$expected);
    }

    /**
     * @dataProvider canTestProvider
     */
    public function testCanMarkCourse($username,$access,$course,$expected)
    {
        $this->setupSession($username,$access);
        self::assertEquals($this->courseCheck->canMarkCourse($course), $expected, "Failed: ".$username." expected ".$expected);
    }
}
