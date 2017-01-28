var usernumber = 0;



function searchUsers(){

  var user = $("#usersearch").val();

  $.ajax({
   type: 'POST',
   url: "../../PHP/Admin/searchUsers.php",
   dataType: 'json',
   data: {namesearch:user},
   cache: false,
   success: function(data){

    $("#usertable tbody tr").remove();

    for (var i=0; i<data.length; i++){
      var id = data[i].userid;
      var username = data[i].username;
      var firstname = data[i].first;
      var surname = data[i].surname;
      var email = data[i].email;
      var dob = data[i].dj;
      var count = data[i].numberofusers;



      var html ="<tr onclick='userselected("+id+")'><td>"+id+"</td><td>"+username+"</td><td>"+firstname+"</td><td>"+surname+"</td><td>"+email+"</td><td>"+dob+"</td></tr>";

      $('#usertable').append(html);
      usernumber += 1;

    }
        // alert(count);
        if (id === undefined)
          count = 0;

        $('div#num').html(count);

      },
      error: function (error) {
        // alert('Search Error ' + eval(error));
      }
    });

}


function checkKey(){

  $("#usersearch").keyup(function() {
    searchUsers();
  });

}

function userselected(id){

  if (name != undefined && name != null) {
    window.location = '/HTML/admin/userinfo.php?userid=' + id;
  }


}



function deleteUser(){

  var userid = $("#userid").text();
  var name = $("#username").text();

  alert(userid+name);
  $.ajax({
   type: 'POST',
   url: "../../PHP/Admin/admindeleteuser.php",
   data: {userid:userid, username:name},
   cache: false,
   success: function(data){

    alert(name + " " + userid + "has been deleted");

  },
  error: function (error) {
    alert('Delete Error ' + eval(error));
  }
});

}

function genusers(){

 $('#usergen').toggleClass("btn-primary").toggleClass("btn-warning");
 $('#usergen').html("Loading...");

 $.ajax({
   type: 'POST',
   url: "../../Database/usergenerator.php",
   data: {},
   cache: false,
   success: function(data){
    $('#usergen').toggleClass("btn-warning").toggleClass("btn-success");
    $('#usergen').prop('disabled', true)
    $('#usergen').html("Insert Successful");
    
    var total = data.total;
    alert(total+" Users have been Inserted");

  },
  error: function (error) {
    alert("Problem has occured");
  }
});

}

function updatecourses(){

  $('#courseupdate').toggleClass("btn-primary").toggleClass("btn-warning");
  $('#courseupdate').html("Loading...");

  $.ajax({
   type: 'POST',
   url: "../../php/admin/schoolGrades.php",
   data: "",
   cache: false,
   success: function(data){
    $('#courseupdate').toggleClass("btn-warning").toggleClass("btn-success");
    $('#courseupdate').prop('disabled', true)
    $('#courseupdate').html("Insert Successful");

  },
  error: function (error) {
    alert("Problem has occured Update Failed");
  }
});

}



function updatefromfile(){

 $('#fileupdate').toggleClass("btn-primary").toggleClass("btn-warning");
 $('#fileupdate').html("Loading...");

 $.ajax({
   type: 'POST',
   url: "../../php/readFile.php",
   data: "",
   cache: false,
   success: function(data){

    $('#fileupdate').toggleClass("btn-warning").toggleClass("btn-success");
    $('#fileupdate').prop('disabled', true)
    $('#fileupdate').html("Insert Successful");

  },
  error: function (error) {
    alert("Problem has occuredUpdate Failed");
  }
});

}

$(document).ready(function(){

  $('[data-toggle="popover"]').popover({

    placement : 'top',
    delay:{
      show: "100",
    },
    trigger : 'hover'

  });

});



