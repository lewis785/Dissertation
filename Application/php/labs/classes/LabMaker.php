<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 12/03/2017
 * Time: 19:33
 */

require_once (dirname(__FILE__)) . "/../../core/classes/ConnectDB.php";

class LabMaker
{

    public function createQuestion($type,$id,$question_num)
    {
        switch ($type)
        {
            case "boolean":
                return $this->booleanQuestion($id, $question_num);
                break;
            case "scale":
                return $this->scaleQuestion($id, $question_num);
                break;
            case "text":
                return $this->textQuestion($id, $question_num);
                break;
            default:
                return $this->unknownQuestion($id,$question_num);
        }
    }


    public function displayEditableLab($labID)
    {
        $con = new ConnectDB();
        $retrieveLabQuestions = mysqli_stmt_init($con->link);
        mysqli_stmt_prepare($retrieveLabQuestions, "SELECT qt.typeName FROM lab_questions AS lq 
                                                    JOIN question_types AS qt ON lq.questionType = qt.questionTypeID
                                                    WHERE lq.labRef = ? ORDER BY  lq.questionID");
        mysqli_stmt_bind_param($retrieveLabQuestions, "s", $labID);
        mysqli_stmt_execute($retrieveLabQuestions);
        $result = mysqli_stmt_get_result($retrieveLabQuestions);

        $output = "<form class='col-lg-12' id='form-area' accept-charset='UTF-8' role='form'  name='create-lab-form' method='post' action='../../php/labs/lab_creator.php'>
                    <input type='hidden' name='update' value='$labID'>";
        $id = 0;
        $qNum =  1;

        while($question = $result->fetch_row()) {
            $output .= json_decode($this->createQuestion($question[0], $id, $qNum))->question;
            $id ++; $qNum++;
        }

        $output .= "</form>";

        return json_encode(array("questions"=>$output));


    }


    private function unknownQuestion($id,$question_num)
    {
        $unknown = "<div id='type-error'>";
        $unknown.= $this->startQuestion($id);
        $unknown.= $this->title("Unknown",$question_num);
        $unknown.= "<div class='unknown-question col-md-6 col-md-offset-3'>Question type does not exist</div></div></div>";

        return json_encode(array('question'=>$unknown));
    }

    private function scaleQuestion($id, $question_num)
    {
        $scale = $this->startQuestion($id);
        $scale.= $this->title("Scale", $question_num);
        $scale.= $this->questionType("scale");
        $scale.= $this->textInput("question[]");
        $scale.= $this->scaleInput("Select Questions Minimum Mark","min-value[]");
        $scale.= $this->scaleInput("Select Questions Maximum Mark","max-value[]");
        $scale.= $this->visablityButton($id);
        $scale.= "</div>";

        return json_encode(array('question'=>$scale));
}

    private function booleanQuestion($id, $question_num)
    {
        $boolean = $this->startQuestion($id);
        $boolean.= $this->title("Boolean", $question_num);
        $boolean.= $this->questionType("boolean");
        $boolean.= $this->textInput("question[]");
        $boolean.= $this->scaleInput("Select Question Mark", "max-value[]");
        $boolean.= $this->visablityButton($id);
        $boolean.= "</div>";

        return json_encode(array('question'=>$boolean));
    }

    private function textQuestion($id, $question_num)
    {

        $text = $this->startQuestion($id);
        $text.= $this->title("Text", $question_num);
        $text.= $this->questionType("text");
        $text.= $this->textInput("question[]");
        $text.= $this->scaleInput("Selectable Mark Value For Question Can Be Zero For No Marked Question", "max-value[]");
        $text.= $this->visablityButton($id);
        $text.= "</div>";

        return json_encode(array('question'=>$text));
    }


    private function startQuestion($id)
    {
        $start_div = "<div class='col-xs-12 col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2 tile'  id='question-$id'>";
        $close_btn = "<span class='remove-btn glyphicon glyphicon-remove close-btn' aria-hidden='true' onclick='update_numbers(".$id.")'/>";
        return $start_div . $close_btn;
    }

    private function title($type, $question_num)
    {
        return "<div class='tile-title'>
                    <span class='col-md-5 col-md-offset-1'><label for='sel1'>Question Number: <span id='question-number'>$question_num</span></label></span>
                    <div class='col-md-5 col-md-offset-1'>Type: $type</div>
                </div>";
    }

    private function questionType($type)
    {
        return "<input type='hidden' name='type[]' value='$type'>";
    }

    private function textInput($name)
    {
        return "<div class='form-group col-md-6 col-md-offset-3 row tile-text-input'>
                    <label for='question-label-input' class='col-md-12 col-md-offset-1 col-form-label'>Question</label>
                    <input class='form-control question-input' type='text' value='' name='$name'>
                </div>";
    }


    private function scaleInput($text, $name)
    {
        $scale = "<div class='form-group col-md-6 col-md-offset-3 tile-scale-input'>
                    <label>$text (select one):</label>
                    <select class='form-control dropdown-input' name='$name' >
                    <option selected value='no-selection'>Select Value</option>";

        for ($i = 0; $i <= 9; $i++)
            $scale .= "<option value='$i'>$i</option>";

        $scale .= "</select></div>";

        return $scale;

    }


    private function visablityButton($id)
    {
        return "<div style='display: table;' class='form-group col-md-12 col-sm-12 col-xs-12 ' id='visible-area' >
                 <input id='hidden-visibility-$id' type='hidden' name='visibility[]' value='false'>
                  <span style='display: table-cell; vertical-align: middle; text-align: right;' class='col-md-4 col-xs-12'>Result is </span> 
                  <button type='button' id='visibility-btn-$id' class='btn btn-success col-md-4 col-xs-12' onclick='change_visibility(" . $id . ")' >Visible</button> 
                  <span class='col-md-4'> to students</span>
                </div>";
    }
}