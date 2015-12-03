
/*
 * Api ajax call function
 * */
function apiCall(url){
    $.get( url, function( data ) {
        data_obj = $.parseJSON(data);
        //console.log( data_obj );
        //round finished
        if(data_obj.status && data_obj.status == 'nok'){
            $("#error_msg").html('This round is finished: '+data_obj.output);
            $('#myModal').modal('show');
            $("#word").val('');
            $("#ghost").val(data_obj.ghost);
            $("#ai_ghost").val(data_obj.ai_ghost);
        }else{
          //if session exists (game in progress)
          if(data_obj.user){

                $("#game_container").show();
                $("#start_container").hide();
                $("#username").html(data_obj.user);
                if(data_obj.word){
                    $("#word").val(data_obj.word);
                }
                $("#ghost").val(data_obj.ghost);
                $("#ai_ghost").val(data_obj.ai_ghost);
                $("#letter").focus();
          }else{ //start session
                $("#start_container").show();
                $("#game_container").hide();
          }
        }
        // if player lost?
        if(data_obj.ghost === "ghost" || data_obj.ai_ghost === "ghost"){
                $("#error_msg").html(data_obj.output+' You lost the game of GHOST');
                $('#myModal').modal('show');
                $("#modal_button").html("START NEW GAME").on( "click", function(event) {
                    //start new game

                    $("#modal_button").html("CLOSE").off( "click");
                    apiCall("api/?new");

                });
        }
        // if AI lost?
        if(data_obj.ai_ghost === "ghost"){
                $("#error_msg").html(data_obj.output+' AI lost the game of GHOST');
                $('#myModal').modal('show');
                $("#modal_button").html("START NEW GAME").on( "click", function(event) {
                    //start new game

                    $("#modal_button").html("CLOSE").off( "click");
                    apiCall("api/?new");

                });
        }
    });
}
/*
 * Begin game session
 * */
$( "#start" ).on( "click", function(event) {
    event.preventDefault();

    $("#word").val('');
    $("#ghost").val('');
    $("#ai_ghost").val(data_obj.ai_ghost);

    var name = $("#user").val();
    if(name == ''){
        $("#error_msg").html('your name is empty');
        $('#myModal').modal('show');

    }else{
        apiCall("api/?user="+name);
        $("#user").val('');
    }
    console.log( $( this ).text() );
});
/*
 * Game next step
 * */
$( "#next" ).on( "click", function(event) {
    event.preventDefault();
    var word = $("#word").val();
    var letter = $("#letter").val();
    if(letter == ''){
        $("#error_msg").html('your letter is empty');
        $('#myModal').modal('show');
    }else{
        $("#letter").val('');
        //alert('word= '+word+letter);
        apiCall("api/?word="+word+letter);
    }

});
/*
 * Restart game session
 * */
$( "#new" ).on( "click", function(event) {
    event.preventDefault();

        //alert('word= '+word+letter);
        apiCall("api/?new");

});
/*
 * Initial ajax call
 * */
apiCall("api/");


