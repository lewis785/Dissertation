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
require_once (dirname(__FILE__)."/../lecturer/Lecturer.php");

class AdminForms extends AdminButtons
{

    private $Courses;

    function __construct()
    {
        $this->Courses = new Courses();
        parent::__construct();
    }


    /********************************************************
     *                                                      *
     *     Start of functions for creating users            *
     *                                                      *
     *******************************************************/

    //Creates layout for add users admin panel
    public function createUserForm()
    {
        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2 admin-function' id='insert-users-div'>
                    <form id='add-user-form' method='post' action='../../php/admin/adduser.php'>
                    <legend>Insert User</legend>";
        $output.= $this->textInput("First name", "firstname");
        $output.= $this->textInput("Surname", "surname");
        $output.= $this->textInput("Matriculation Number", "matric");
        $output.= $this->selectInput("User Access Level:","access", $this->accessDropDown());
        $output.= $this->submitButton("addUser()");
        $output.="</form></div>";

        $output.="<div class='col-md-8 col-md-offset-2 admin-function' id='user-uploader'>
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



    /********************************************************
     *                                                      *
     *     Start of functions for removing users            *
     *                                                      *
     *******************************************************/

    //Creates layout for the remove users admin panel
    public function removeUserForm()
    {

        $users = $this->getAllUsers();

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2 admin-function' id='remove-users-div'> <legend>Remove User(s)</legend>";
        $output.= $this->textInput("User Search:","user-search");
        $output.= "<form id='remove-user-form' method='post' >";
        $output .=  $this->addTableStart("users", 3);

        foreach($users as $user)
            $output.= $this->addTableRow($user);

        $output.= "</table></div>";
        $output.= $this->submitButton("removeUser()");


        $output.= "</form></div>";

        return json_encode(array("layout"=>$output));
    }

    /********************************************************
     *                                                      *
     *     Start of functions for updating users            *
     *                                                      *
     *******************************************************/


    //Creates layout for update user access
    public function updateUserForm()
    {
        $all_users = $this->getAllUsers();
        $access_levels = $this->accessDropDown();

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2 admin-function' id='lab-helpers-div'> <legend>Update User Access</legend>";
        $output .= $this->addTable("update",$all_users, 3);
        $output .= $this->selectInput("Access Level: ", "access-level",$access_levels );
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='update-user-btn' onclick='updateUser()''>Update Access</button>";
        $output .="</div>";



        return json_encode(array("layout"=>$output));
    }


    /********************************************************
     *                                                      *
     *     Start of functions for student manager           *
     *                                                      *
     *******************************************************/


    //Creates layout for managing students
    public function manageStudents()
    {
        $courses = $this->Courses->get_courses();
        $options = [];

        foreach($courses as $course)
            array_push($options,[$course,$course]);

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2 admin-function' id='lab-helpers-div'> <legend>Manage Student On Courses</legend>";

        $output.= $this->selectInput("Select Course:", "course", $options, "courseStudents(this)");

        $output.= "<div id='tables-area'></div> </div>";

        return json_encode(array("layout"=>$output));
    }

    public function studentCoursesTable($course)
    {
        $student_functions = new Student();
        $course_students = $student_functions->studentsOnCourse($course);
        $non_course_students = $student_functions->studentNotOnCourse($course);

        $output = "<h2 class='col-md-8 col-md-offset-2 center'>Students Of The Course</h2>";
        if(sizeof($course_students) > 0)
            $output .= $this->addTable("selected",$course_students, 2);
        else
            $output .= $this->addTableStart("selected",2)."<tr><td colspan=2>No Students</td></table></div>";
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='remove-student-btn' onclick='removeStudent()'>Remove Student From Course</button>";

        $output .= "<h2 class='col-md-8 col-md-offset-2 center'>Students Not Of The Course</h2>";
        $output .= $this->textInput("Student Search: ","student-search","filterStudents(this.value)");
        $output .=  $this->addTable("students",$non_course_students, 2);
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='add-student-btn' onclick='addStudent()''>Add Student To Course</button>";

        return json_encode(array("layout"=>$output));
    }

    public function filterStudentTable($course, $filter)
    {
        $student_functions = new Student();
        $students = $student_functions->studentNotOnCourse($course, $filter);

        return json_encode(array("layout"=>$this->addTable("students", $students, 2)));
    }


    /********************************************************
     *                                                      *
     *     Start of functions for Lab Helper Manager        *
     *                                                      *
     *******************************************************/


    //Creates layout for managing lab helpers
    public function manageLabHelpers()
    {

        $courses = $this->Courses->get_courses();

        $options = [];

        foreach($courses as $course)
            array_push($options,[$course,$course]);

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2 admin-function' id='lab-helpers-div'> <legend>Manage Lab Helpers</legend>";

        $output.= $this->selectInput("Select Course:", "course", $options, "courseLabHelpers(this)");

        $output.= "<div id='tables-area'></div> </div>";

        return json_encode(array("layout"=>$output));
    }

    public function labHelpersTables($course)
    {
        $helper_functions = new LabHelper();
        $course_helpers = $helper_functions->getCourseLabHelpers($course);
        $non_course_helpers = $helper_functions->getNonCourseLabHelpers($course);

        $output = "<h2 class='col-md-8 col-md-offset-2 center'>Lab Helpers Of The Course</h2>";
        if(sizeof($course_helpers) > 0)
            $output .= $this->addTable("selected",$course_helpers, 2);
        else
            $output .= $this->addTableStart("selected",2)."<tr><td colspan=2>No Lab Helpers</td></table></div>";
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='remove-helper-btn' onclick='removeLabHelper()'>Remove Lab Helper</button>";

        $output .= "<h2 class='col-md-8 col-md-offset-2 center'>Lab Helpers Not Of The Course</h2>";
        $output .= $this->textInput("Helper Search: ","helper-search","filterHelpers(this.value)");
        $output .=  $this->addTable("lab-helper",$non_course_helpers, 2);
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='add-helper-btn' onclick='addLabHelper()''>Add Lab Helper</button>";



        return json_encode(array("layout"=>$output));
    }

    public function filterHelpersTable($course, $filter)
    {
        $helper_functions = new LabHelper();
        $helpers = $helper_functions->getNonCourseLabHelpers($course, $filter);

        return json_encode(array("layout"=>$this->addTable("lab-helper", $helpers, 2)));
    }


    /********************************************************
     *                                                      *
     *     Start of functions for lecturer manager          *
     *                                                      *
     *******************************************************/

    //Creates layout for managing lecturers
    public function manageLecturers()
    {
        $courses = $this->Courses->get_courses();
        $options = [];

        foreach($courses as $course)
            array_push($options,[$course,$course]);

        $output = $this->buttonLayout("Back","manageUsersButton()", "warning");
        $output .= "<div class='col-md-8 col-md-offset-2 admin-function' id='lab-helpers-div'> <legend>Manage Lecturer(s)</legend>";

        $output.= $this->selectInput("Select Course:", "course", $options, "courseLecturers(this)");

        $output.= "<div id='tables-area'></div> </div>";

        return json_encode(array("layout"=>$output));
    }


    public function lecturerTable($course)
    {
        $lecturer_functions = new Lecturer();
        $course_lecturers = $lecturer_functions->getLecturersOfCourse($course);
        $non_course_lecturers = $lecturer_functions->getLecturersNotOfCourse($course);

        $output = "<h2 class='col-md-8 col-md-offset-2 center'>Lecturers Of The Course</h2>";
        if(sizeof($course_lecturers) > 0)
            $output .= $this->addTable("selected",$course_lecturers, 1);
        else
            $output .= $this->addTableStart("selected",1)."<tr><td colspan=2>No Lecturers</td></table></div>";
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='remove-lecturer-btn' onclick='removeLecturer()'>Remove Lecturer</button>";

        $output .= "<h2 class='col-md-8 col-md-offset-2 center'>Lecturers Not Of The Course</h2>";
        $output .= $this->textInput("Lecturer Search: ","lecturer-search","filterLecturers(this.value)");
        $output .=  $this->addTable("lecturer",$non_course_lecturers, 1);
        $output.="<button type='button' class='btn btn-info disabled col-md-4 col-md-offset-4' disabled='disabled' id='add-lecturer-btn' onclick='addLecturer()''>Add Lecturer</button>";


        return json_encode(array("layout"=>$output));
    }


    /********************************************************
     *                                                      *
     *     Start of private functions for whole class       *
     *                                                      *
     *******************************************************/

    private function addTable($id, $data, $column_num)
    {
        $table = $this->addTableStart($id, $column_num);

        foreach($data as $row)
            $table.= $this->addTableRow($row);

        $table.= "</table></div>";

        return $table;
    }


    private function addTableStart($id, $rowNum)
    {
        $table =  "<div class='table-div' id='$id-table-div'><table class='col-md-12 table table-striped table-bordered' id='$id-table'>";

        if($rowNum === 1)
            $table.= "<thead> <tr><td>Name</td></tr> </thead>";
        elseif($rowNum === 2)
            $table.= "<thead> <tr><td>Name</td><td>Matric Num</td></tr> </thead>";
        elseif ($rowNum === 3)
            $table.= "<thead> <tr><td>Access</td><td>Name</td><td>Matric Num</td></tr> </thead>";

        return $table;
    }


    private function addTableRow($user)
    {
        $length = sizeof($user);
        if($length === 4)
            return "<tr> <td><div id='access'>$user[0]</div></td> <td>$user[1] $user[2]</td> <td><div id='matric-num'>$user[3]</div></td> </tr>";
        elseif($length === 3)
            return "<tr> <td>$user[0] $user[1]</td> <td><div id='matric-num'>$user[2]</div></td> </tr>";
        elseif($length === 2)
            return "<tr> <td>$user[0] $user[1]</td> </tr>";
    }


    private function textInput($label, $name, $function = null)
    {
        $action = ($function !== null) ? "onkeyup='$function'" : "";

        $input = "<div class='form-group row col-md-12'>
                      <label for='$name-label-input' class='col-2 col-form-label'>$label</label>
                          <input class='col-md-12 form-control' $action type='text' value='' name='$name' id='$name-input'>
                  </div>";
        return $input;
    }




    private function selectInput($label, $name, $options, $function = null)
    {
        $action = ($function != null) ? "onChange='$function'" : "";

        $input = "<div class='form-group col-md-6 col-md-offset-3'>
                    <label for='$name-lable-input'>$label</label>
                    <select class='form-control access-selector' name='$name' $action>
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
//echo $form->lecturerTable("Software Development 1");