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

$title = "<div class='col-sm-6 col-sm-offset-3 col-mid-8 col-md-offset-2 tile'  id='question-$id'>
        <div class='col-md-5 col-md-offset-1'><label for='sel1'>Question Number: <div id='question-number'>$qNum</div></label></div>
        <div class='col-md-5 col-md-offset-1'>Type: Boolean</div>
        <span class='glyphicon glyphicon-remove close-btn' aria-hidden='true' onclick='update_numbers(".$id.")'></span>";

$type = "<input type='hidden' name='type[]' value='boolean'>";

$question = "  <div class='form-group row'>
                <label for='question-label-input' class='col-md-12 col-md-offset-1 col-form-label'>Question</label>
                <div class='col-md-10 col-md-offset-1'>
                    <input class='form-control question-input' type='text' value='' name='question[]'>
                </div>
            </div>";

 $boolean = "<div class='form-group col-md-6 col-md-offset-3'>
            <label>Select Question Value (select one):</label>
            <select class='form-control dropdown-input' name='max-value[]' >
                $values
            </select>
            </div>";

 $visibility = "<div class='form-group col-md-4 col-md-offset-4'>
                 <input id='hidden-visibility-$id' type='hidden' name='visibility[]' value='false'>
                  <button type='button' id='visibility-btn-$id' class='btn btn-success  col-md-6 col-md-offset-3' onclick='change_visibility(".$id.")' >Public</button>
                </div>";

 $end = "</div>";

 $data = $title . $type . $question . $boolean . $visibility . $end;

echo json_encode(array('data'=>$data));


