<?php

require_once(dirname(__FILE__) . "/../../core/classes/Security.php");
require_once(dirname(__FILE__) . "/../../courses/classes/Courses.php");

class CoursesTest extends PHPUnit_Framework_TestCase
{
    private $security;
    private $course;

    protected function setUp()
    {
        $this->security = new Security();
        $this->course = new Courses();
    }

    public function usernameProvider()
    {
        $users = [array("student","student"), array("helper","lab helper"), array("lecturer", "lecturer"), array("admin","admin")];
        return $users;
    }


    public function testGetCourseId()
    {
        self::assertTrue($this->course->getCourseId("Software Development 1") === 1, "Wrong id given for Software Development 1");
        self::assertTrue($this->course->getCourseId("Data Management") === 2, "Wrong id given for Data Management");
        self::assertTrue($this->course->getCourseId("Data") === null, "Null was expected for input 'Data'");
        self::assertTrue($this->course->getCourseId(1) === null, "Null was expexted for input '1'");
    }


    /**
     * @dataProvider usernameProvider
     */
    public function testGetCourses($username,$accesslevel)
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION["username"] = $username;
        $_SESSION["accesslevel"] = $this->security->getAccessValue($accesslevel);

        $output = $this->course->getCourses();

        self::assertTrue(is_array($output), "Failed on ".$username);
    }


    public function testCourseFromLabID()
    {
        self::assertTrue($this->course->courseFromLabID(1) === "Software Development 1", "Software Developoment 1 Expected but not returned");
        self::assertTrue($this->course->courseFromLabID(4) === "Games Programming", "Games Programming Expected but not returned");
        self::assertTrue($this->course->courseFromLabID(5) === "Software Development 1", "Software Developoment 1 Expected but not returned");
        self::assertTrue($this->course->courseFromLabID(55) === null, "null Expected but not returned");
        self::assertTrue($this->course->courseFromLabID("Data") === null, "null1 Expected but not returned");
    }
}
