<?php

/**
 * Created by PhpStorm.
 * User: Lewis
 * Date: 08/03/2017
 * Time: 23:47
 */
require_once "AdminButtons.php";
require_once (dirname(__FILE__)."/../students/Student.php");
require_once (dirname(__FILE__)."/../lab_helper/LabHelper.php");
require_once (dirname(__FILE__)."/../courses/Courses.php");

class AdminForms extends AdminButtons
{

    private $Courses;

    function __construct()
    {
        $this->Courses = new Courses();
        parent::__construct();
    }

    //Creates layout for add users admin panel
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
        return json_encode(array("layout"=>$output));
    }



    //Creates layout for the remove users admin panel
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

        return json_encode(array("layout"=>$output));
    }


    //Creates layout for update user access
    public function updateUserForm()
    {
        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");


        return json_encode(array("layout"=>$output));
    }



    //Creates layout for managing students
    public function manageStudents()
    {
        $student_functions = new Student();
        $students = $student_functions->getAllStudents();

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2' id='update-users-div'> <legend>Update User(s) Access</legend>";

        $output .=  "<div class='table-div'><table class='col-md-12 table table-striped table-bordered' id='students-table'>
                    <thead>
                        <tr><td>Name</td><td>Matric Num</td></tr>
                     </thead>";

        foreach($students as $student)
            $output.= $this->addTableRow($student);

        $output.= "</table></div></div>";

        return json_encode(array("layout"=>$output));
    }





    //Creates layout for managing lab helpers
    public function manageLabHelpers()
    {
        $helper_functions = new LabHelper();
        $lab_helpers = $helper_functions->getAllLabHelpers();
        $courses = $this->Courses->get_courses();

        $options = [];

        foreach($courses as $course)
            array_push($options,[$course,$course]);

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2' id='update-users-div'> <legend>Add Lab Helpers To Course</legend>";

        $output .=  "<div class='table-div'><table class='col-md-12 table table-striped table-bordered' id='students-table'>
                    <thead>
                        <tr><td>Name</td><td>Matric Num</td></tr>
                     </thead>";

        foreach($lab_helpers as $helper)
            $output.= $this->addTableRow($helper);

        $output.= "</table></div>";
        $output.= $this->selectInput("Select Course:", "course", $options);
        $output.= "</div>";

        return json_encode(array("layout"=>$output));
    }





    //Creates layout for managing lecturers
    public function manageLecturers()
    {
        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");


        return json_encode(array("layout"=>$output));
    }





    private function addTableRow($user)
    {
        if(sizeof($user) === 4)
            return "<tr> <td>$user[0]</td> <td>$user[1] $user[2]</td> <td>$user[3]</td> </tr>";
        else
            return "<tr> <td>$user[0] $user[1]</td> <td>$user[2]</td> </tr>";
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
//echo $form->manageLabHelpers();