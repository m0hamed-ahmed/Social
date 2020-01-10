$(document).ready(function(e){
    $("#Chat").on("submit", function(e){
      e.preventDefault();
      $.ajax({
          type : 'POST',
          url: 'inc/chat/send.php',
          data: new FormData(this),
          contentType: false,
          cahe: false,
          processData: false,
          success:function(data){
            $('#Success').html(data) ; 
          }
      });
    });
});