<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 23:47
 */
require_once "AdminButtons.php";

class AdminForms extends AdminButtons
{


    public function createUserForm()
    {


        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2' id='insert-users-div'>
                    <form id='add-user-form' method='post' action='../../php/admin/adduser.php'>
                    <legend>Insert User</legend>";
        $output.= $this->textInput("First name", "firstname");
        $output.= $this->textInput("Surname", "surname");
        $output.= $this->textInput("Matriculation Number", "matric");
        $output.= $this->selectInput("User Access Level:","access", $this->accessDropDown());
        $output.= $this->submitButton("addUser()");
        $output.="</form></div>";

        $output.="<div class='col-md-8 col-md-offset-2' id='user-uploader'>
                    <form class='form-horizontal' action='../../php/core/cvs_handling.php' method='post' name='upload_excel' enctype='multipart/form-data'>

                    <!-- Form Name -->
                    <legend>Upload Users File</legend>

                    <!-- File Button -->

                        <label class='col-md-4 control-label' for='filebutton'>Select File</label>
                        <div class='col-md-4'>
                            <input type='file' name='file' id='file' class='input-large'>
                        </div>

                    <!-- Button -->
                        <div class='col-md-4'>
                            <button type='submit' id='submit' name='Import' class='btn btn-primary button-loading' data-loading-text='Loading...'>Import Users</button>
                        </div>

            </form></div>";





        return json_encode(array("form"=>$output));

    }


    public function removeUserForm()
    {

        $users = $this->getAllUsers();

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2' id='remove-users-div'> <legend>Remove User(s)</legend>";
        $output.= $this->textInput("User Search:","user-search");
        $output.= "<form id='remove-user-form' method='post' >";
        $output.= "<div class='table-div'><table class='col-md-12 table table-striped table-bordered' id='users-table'>
                    <thead>
                        <tr><th>Access</th><td>Name</td><td>Matric Num</td></tr>
                     </thead>";

        foreach($users as $user)
            $output.= $this->addTableRow($user);

        $output.= "</table></div>";
        $output.= $this->submitButton("removeUser()");


        $output.= "</form></div>";

        return json_encode(array("form"=>$output));
    }



    private function addTableRow($user)
    {
        return "<tr> <td>$user[0]</td> <td>$user[1] $user[2]</td> <td>$user[3]</td> </tr>";
    }


    private function textInput($label, $name)
    {
        $input = "<div class='form-group row col-md-12'>
                      <label for='$name-label-input' class='col-2 col-form-label'>$label</label>
                          <input class='col-md-12 form-control' type='text' value='' name='$name' id='$name-input'>
                  </div>";
        return $input;
    }
    
    private function selectInput($label, $name, $options = [])
    {
        $input = "<div class='form-group col-md-6 col-md-offset-3'>
                    <label for='$name-lable-input'>$label</label>
                    <select class='form-control access-selector' name='$name' id=''>
                        <option selected value='no-selection'>Select Access Level</option>";

        foreach($options as $option)
        {
          $input.="<option value='$option[0]'>$option[1]</option>";
        }

        $input.="</select></div>";

        return $input;
    }
    
    private function submitButton($run)
    {
        $input = "<buttton onclick='$run' class='col-md-8 col-md-offset-2 btn btn-success'>Submit</buttton>";
        return $input;
    }
    
}
//
//$form = new AdminForms();
//echo $form->createUserForm();