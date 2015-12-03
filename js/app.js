
/*
 * Api ajax call function
 * */
function apiCall(url){
    $.get( url, function( data ) {
        data_obj = $.parseJSON(data);
        //console.log( data_obj );
        if(data_obj.status && data_obj.status == 'nok'){
            $("#error_msg").html('this round is finished: '+data_obj.output);
            $('#myModal').modal('show');
            $("#word").val('');
            $("#ghost").val(data_obj.ghost);
            $("#ai_ghost").val(data_obj.ai_ghost);
        }else{
            //session exists
          if(data_obj.user){
                //alert( "Load was performed." + data );
                $("#game_container").show();
                $("#start_container").hide();
                $("#username").html(data_obj.user);
                if(data_obj.word){
                    $("#word").val(data_obj.word);
                }
                $("#ghost").val(data_obj.ghost);
                $("#ai_ghost").val(data_obj.ai_ghost);
                $("#letter").focus();
          }else{
                $("#start_container").show();
                $("#game_container").hide();
          }
        }
        if(data_obj.ghost === "ghost" || data_obj.ai_ghost === "ghost"){
                $("#error_msg").html(data_obj.output+' You lost the game of GHOST');
                $('#myModal').modal('show');
                $("#modal_button").html("START NEW GAME").on( "click", function(event) {
                    //start new game

                    $("#modal_button").html("CLOSE").off( "click");
                    apiCall("api/?new");

                });
        }
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
 *
 * */
$( "#start" ).on( "click", function(event) {
    event.preventDefault();
    var name = $("#user").val();
    if(name == ''){
        $("#error_msg").html('your name is empty');
        $('#myModal').modal('show');
    }else{
        apiCall("api/?user="+name);
    }
    console.log( $( this ).text() );
});
/*
 *
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
    console.log( $( this ).text() );
});
/*
 *
 * */
$( "#new" ).on( "click", function(event) {
    event.preventDefault();

        //alert('word= '+word+letter);
        apiCall("api/?new");

    console.log( $( this ).text() );
});
/*
 * Initial ajax call
 * */
apiCall("api/");


