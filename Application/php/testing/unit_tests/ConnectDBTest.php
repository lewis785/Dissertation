<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 03/04/2017
 * Time: 16:29
 */
require_once(dirname(__FILE__) . "/../../core/classes/ConnectDB.php");

class ConnectDBTest extends PHPUnit_Framework_TestCase
{
    protected $con;

    protected function setUp()
    {
        @session_start();
        $this->con = new ConnectDB();
    }

    protected function tearDown()
    {
        mysqli_close($this->con->link);
        $this->con = null;
    }

    public function testDBConnection()
    {
        $this->assertTrue(!$this->con->link->errno);
    }

    public function testSessionIsRunning()
    {
        $this->assertFalse(session_status() == PHP_SESSION_NONE);

    }

}
