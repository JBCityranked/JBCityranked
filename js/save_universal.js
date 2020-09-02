jQuery(document).ready(function($) {
  var return_styles = false;
  $('#saveUniversal').on('click', function(){
    console.log('saving styles');
    return_styles = $('#universal_stylesheet').val();
    var data = {
  		'action': 'save_universal',
  		'styles': return_styles,
      '_ajax_nonce': _universal._nonce
  	};

  	jQuery.post(_universal._ajax_url, data, function(response) {
  		console.log('Got this from the server: ' + response);
  	});
  });
});
