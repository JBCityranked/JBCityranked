jQuery(document).ready(function($) {
  var return_styles = false;
  $('#saveUniversal').on('click', function(){
    console.log('saving styles');

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
		    console.log('Got this from the server: ' + response);
        if (response.error) {

        }
        if (response.success) {

        }
	  });
  });
});
