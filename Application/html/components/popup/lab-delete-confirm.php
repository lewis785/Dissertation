<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link href="../../../bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="../../../css/main.css" rel="stylesheet">
<!--<script src="../../../bootstrap/js/bootstrap.js"></script>-->


<div id="delete-lab-popup">

    <div id="mask"></div>

    <div class = "popup col-md-3" id="delete-lab-div">

        <div class="col-md-8 col-md-offset-2 popup-text">
            You are about to delete a lab.
        </div>
        <div class="col-md-8 col-md-offset-2 popup-text">
            This will result in all marks relating to the lab to be deleted.
        </div>
        <div class="col-md-8 col-md-offset-2 popup-text">
             Certain you want to delete the lab ?
        </div>

        <div id="popup-btn">
            <button value="Yes" name="event-submit" class="btn btn-danger col-md-3 col-md-offset-2" id="confirm-btn">Yes</button>
            <button value="No" class="btn btn-warning col-md-3 col-md-offset-2" id="cancel-btn" onclick="$('#delete-lab-popup').remove()">No</button>
        </div>

    </div>
</div>

