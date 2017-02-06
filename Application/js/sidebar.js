/**
 * Created by Lewis on 29/01/2017.
 */



//Function that adds the sidebar into a div with id = sidebar_area
function include_sidebar (inputarea){

    $(document).ready(function(){
        $("#"+inputarea).load("../components/sidebar.php #sidebar_code");
    });
}



