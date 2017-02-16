<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 15/02/2017
 * Time: 18:21
 */


function q_scale($question)
{

    $data = "<div>".$question."<div>";

    echo json_encode($data);

}