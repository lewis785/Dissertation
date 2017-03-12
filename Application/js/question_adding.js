/**
 * Created by Lewis on 15/02/2017.
 */

var qCount = 0;
var maxQ = 1;

function update_numbers(number)
{
    var new_number = $("#question-"+number).find("#question-number").text();
    maxQ -= 1;
    $("#question-"+number).remove();
    for(var i = number+1; i <= qCount; i++) {
        $("#question-"+i).find("#question-number").text(new_number++);
    }
}

// function add_question(type)
// {
//     $.ajax({
//         type: 'POST',
//         url: "../../html/components/questions_create/"+type+"_question.php",
//         dataType: 'json',
//         data: {count: qCount, qnum: maxQ},
//         cache: false,
//         success: function(result){
//             qCount++;
//             maxQ ++;
//             $.when($("#form-area").append(result.data)).then(scroll_bottom());
//         },
//         error: function(xhr, status, error) {
//             alert(xhr);
//         }
//     });
// }

function add_question(type)
{
    // alert(type)
    $.ajax({
        type: 'POST',
        url: "../../php/labs/add_lab_question.php",
        dataType: 'json',
        data: {type:type, id: qCount, qnum: maxQ},
        cache: false,
        success: function(result){
            qCount++;
            maxQ ++;
            $.when($("#form-area").append(result.question)).then(scroll_bottom());
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}


function change_visibility(id)
{
    var btn = $("#visibility-btn-"+id);
    var input = $("#hidden-visibility-"+id);

    btn.toggleClass("btn-success btn-danger");

    if (input.val() == "false")
    {
        btn.text("Is Private");
        input.val("true");
    }
    else
    {
        btn.text("Is Public");
        input.val("false");
    }
}


function scroll_bottom(){
    $("html, body").animate({ scrollTop: $(document).height() }, "slow")
}