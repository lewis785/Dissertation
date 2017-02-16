<?php
/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 15/02/2017
 * Time: 20:18
 */

$values = '<option selected value="no-selection">Select Value</option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>';


    $id = $_POST["count"];
    $qNum = $_POST["qnum"];

$data = '<div class="col-sm-6 col-sm-offset-3 col-mid-8 col-md-offset-2 tile"  id="question-'. $id .'">
            <div class="col-12">Question Number: <div id="question-number">'.($qNum).'</div></div>
                <span class="glyphicon glyphicon-remove close-btn" aria-hidden="true" onclick="update_numbers(' .$id . ')"></span>

                <div class="form-group row">
                    <label for="surname-label-input" class="col-md-12 col-md-offset-1 col-form-label">Question</label>
                    <div class="col-md-10 col-md-offset-1">
                        <input class="form-control" type="text" value="" name="surname" id="surname-input">
                    </div>
                </div>
            <div class="form-group col-md-4 col-md-offset-1">
                <label for="sel1">Select list (select one):</label>
                <select class="form-control" name="access" id="sel1">'.
                    $values
                .'</select>
            </div>
            <div class="form-group col-md-4 col-md-offset-2">
                <label for="sel1">Select list (select one):</label>
                <select class="form-control" name="access" id="sel1">'.
                    $values
                .'</select>
            </div>
        </div>';







//    $data = '<div id="question-'. $id .'">' . $question . ' <span class="glyphicon glyphicon-remove" aria-hidden="true"
//    onclick="$('. "'" . '#question-'.$id. "'" . ').remove()"></span> <div>';

    echo json_encode(array('data'=>$data));