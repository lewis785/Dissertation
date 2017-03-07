<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 20/02/2017
 * Time: 16:02
 */

require_once "LabMarking.php";

if(isset($_POST["type"])) {

        $marking = new LabMarking();
        echo( $marking->markingLabsButtons() );

}
