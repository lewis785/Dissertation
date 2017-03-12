<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 12/03/2017
 * Time: 19:33
 */
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
        $boolean.= $this->scaleInput("Select Question Value", "max-value[]");
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
        $start_div = "<div class='col-sm-6 col-sm-offset-3 col-mid-8 col-md-offset-2 tile'  id='question-$id'>";
        $close_btn = "<span class='glyphicon glyphicon-remove close-btn' aria-hidden='true' onclick='update_numbers(".$id.")'/>";
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
        return "<div class='form-group row tile-text-input'>
                    <label for='question-label-input' class='col-md-12 col-md-offset-1 col-form-label'>Question</label>
                    <div class='col-md-10 col-md-offset-1'>
                        <input class='form-control question-input' type='text' value='' name='$name'>
                    </div>
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
        return "<div class='form-group col-md-4 col-md-offset-4'>
                 <input id='hidden-visibility-$id' type='hidden' name='visibility[]' value='false'>
                  <button type='button' id='visibility-btn-$id' class='btn btn-success  col-md-6 col-md-offset-3' onclick='change_visibility(" . $id . ")' >Is Public</button>
                </div>";
    }

}
//
//$maker = new LabMaker();
//print_r($maker->booleanQuestion(0,1));
//print_r($maker->startQuestion(1));
//print_r($maker->typeInput("boolean"));
//print_r($maker->textInput("questions[]"));
//print_r($maker->scaleInput("max-mark[]"));
//print_r($maker->visablityButton(1));