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




function add_scale_question(quest)
{
    $.ajax({
        type: 'POST',
        url: "../../html/components/questions_create/scale_question.php",
        dataType: 'json',
        data: {q: quest, count: qCount, qnum: maxQ},
        cache: false,
        success: function(result){

        $("#main-text-area").append(result.data);
        qCount++;
        maxQ ++;

        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });

}