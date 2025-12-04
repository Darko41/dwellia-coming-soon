jQuery(document).ready(function() {	
	
    /*
        Background slideshow
    */
	$('.top-content').backstretch([
	                     "assets/img/backgrounds/1.jpg"
	                   , "assets/img/backgrounds/2.jpg"
	                   , "assets/img/backgrounds/3.jpg"
	                  ], {duration: 3000, fade: 750});
    
    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$('.top-content').backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$('.top-content').backstretch("resize");
    });
    
    /*
        Wow
    */
    new WOW().init();
    
    /*
	    Countdown initializer
	*/
	// SET YOUR LAUNCH DATE HERE (YYYY, MM-1, DD, HH, MM, SS)
	// Example: February 1, 2025 at 00:00:00
	var launchDate = new Date(2026, 4, 1, 0, 0, 0); // Month is 0-indexed (0=Jan, 1=Feb, etc.)

	$('.timer').countdown(launchDate, function(event) {
		$(this).find('.days').text(event.offset.totalDays);
		$(this).find('.hours').text(('0' + event.offset.hours).slice(-2));
		$(this).find('.minutes').text(('0' + event.offset.minutes).slice(-2));
		$(this).find('.seconds').text(('0' + event.offset.seconds).slice(-2));
	}).on('finish.countdown', function() {
		$('.timer-wrapper').html('<h4>🎉 Platforma je sada dostupna! <br><small>Posetite dwellia.rs da započnete</small></h4>');
	});

    console.log('Script loaded, setting up form handler...');

    /*
	    Subscription form - DEBUG VERSION
	*/
	$('.form-inline').submit(function(e) {
	    console.log('Form submitted!');
	    e.preventDefault();

	    var submitBtn = $(this).find('button[type="submit"]');
	    var originalText = submitBtn.text();
	    submitBtn.text('Slanje...').prop('disabled', true);

	    var postdata = $(this).serialize();
	    console.log('Sending data:', postdata);

	    $.ajax({
	        type: 'POST',
	        url: 'assets/subscribe.php',
	        data: postdata,
	        dataType: 'json',
	        success: function(json) {
	            console.log('Server response:', json);
	            if(json.valid == 0) {
	                $('.success-message').hide();
	                $('.error-message').hide();
	                $('.error-message').html('<div class="alert alert-danger">' + json.message + '</div>');
	                $('.error-message').fadeIn('fast', function(){
	                	$('.form-inline').addClass('animated shake').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
	            			$(this).removeClass('animated shake');
	            		});
	                });
	            }
	            else {
	                $('.error-message').hide();
	                $('.success-message').hide();
	                $('.success-message').html('<div class="alert alert-success">' + json.message + '</div>');
	                $('.success-message').fadeIn('fast', function(){
	                	$('.top-content').backstretch("resize");
	                });
	                $('.form-inline').hide();
	            }
	        },
	        error: function(xhr, status, error) {
	            console.log('AJAX error:', error);
	            $('.success-message').hide();
                $('.error-message').hide();
                $('.error-message').html('<div class="alert alert-danger">Greška u konekciji. Pokušajte ponovo.</div>');
                $('.error-message').fadeIn('fast');
	        },
	        complete: function() {
	            submitBtn.text(originalText).prop('disabled', false);
	        }
	    });
	});

});


jQuery(window).load(function() {
	
	/*
		Loader
	*/
	$(".loader-img").fadeOut();
	$(".loader").delay(1000).fadeOut("slow");
	
});