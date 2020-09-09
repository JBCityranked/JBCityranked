jQuery(document).ready(function($) {
  var return_styles = false;
  $('#saveUniversal').on('click', function(){
    console.log('saving styles');
    $('#saving').fadeIn( "slow" );
    return_styles = $('#universal_stylesheet').val();

    // packup the left overs to send back home
    var data = {
  		'action': 'save_universal',
  		'styles': return_styles,
      '_ajax_nonce': _universal._nonce
  	};

    // Send the leftovers bacak home
  	jQuery.post(
      _universal._ajax_url, // We tell what URL to use from the picnic basket
      data,                 // The leftovers we want to take back home
      function(response) {
        // Then we can read the thank you note that was sent from home to us
        let response_object = JSON.parse(response);
        console.log( response_object);
        $('#saving').fadeOut( "fast" );
        console.log( typeof( response_object ) );
        if (true == response_object.error) {
          $('#error').fadeIn( "slow" );
          $('#error_message').fadeIn( "slow" );
          $('#saving').html(response.errorMessage);

        }
        console.log( typeof( response_object.success ) );
        if (true == response_object.success ) {
          console.log('response.success');
          $('#success').fadeIn( "slow" );
          setTimeout(function(){ $('#success').fadeOut( "slow" ); }, 3000);

        }
	  });
  });
});
