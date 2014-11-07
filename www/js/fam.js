$(document).ready(function()
{
	console.log("[INFO] Ready!"); // Document is now ready for JavaScript

	if(window.history && window.history.pushState) // Check if history is supported
	{
		var page = window.location.pathname.replace($('base').attr('href'), ''); // Get current page for initialization
		if(page == '') // Check if page equals empty (= index)
			page = '.'; // Set page to . instead of empty (required for further scripts to work)

		console.log('[HISTORY] Initializing on ' + page);
		history.replaceState({page: page}, '', page); // Replacing initial empty page with dot in order to enable back button in browser
	}

	$('.qr').each(function() // Execute for each QR code on the page
	{
		$(this).qrcode($(this).data('qr')); // Draw QR code with data from HTML
	});

	$('#navigation a:not(.dropdown-toggle, .navbar-brand)').on('click', function() // Check if any link except dropdown menus are pressed in the main navigation
	{
		if($('#navigation .navbar-toggle').is(':visible')) // If so check if we are on a mobile device (= collapse button is visible)...
			$("#navigation .navbar-toggle").click(); // ...and collapse after an menu entry was pressed.
	});
	$('#footer a:not(.dropdown-toggle, .navbar-brand)').on('click', function() // Check if any link except dropdown menus are pressed in the footer navigation
	{
		if($('#footer .navbar-toggle').is(':visible')) // If so check if we are on a mobile device (= collapse button is visible)...
			$("#footer .navbar-toggle").click(); // ...and collapse after an menu entry was pressed.
	});
});

$(document).on('click', 'a', function(e) // Global event to check if any URL is pressed
{
	if(window.history && window.history.pushState) // Check if history is supported
	{
		var destination = $(this).attr('href'); // Get destination from href tag of link

		if(destination != '#' && !(destination.indexOf("files/") == 0) && !(destination.indexOf("http://") == 0) && !(destination.indexOf("https://") == 0) && !(destination.indexOf("//") == 0)) // Check if URL does not belong to JavaScript (#), does not link to a file and does not link to an external page
		{
			e.preventDefault(); // We are going to change the history manually so we need to prevent the default browser action

			console.log('[HISTORY] Going to ' + destination + ' (push)');
			history.pushState({page: destination}, '', destination); // Push destination to history...
			goto(destination); // ...and navigate to page via AJAX
		}
	}
});

$(window).bind('popstate', function(e) // Global event to check for history popstate (for example: back button in browser)
{
	console.log('[HISTORY] Going to ' + e.originalEvent.state.page + ' (pop)');
	goto(e.originalEvent.state.page); // Since the user went back we need to load the previous page again
});

var request; // This variable is going to store the current AJAX request
function goto(page)
{
	// If page equals dot rewrite it with index
	if(page == '.')
		page = 'index';

	$('#page').fadeOut(250, function() // Fade out the current page
	{
		$('li').removeClass('active'); // Deselect currently active navigation link
		$('dropdown').removeClass('active'); // Deselect currently active dropdown

		if(request != null)
			request.abort(); // Abort the last (most likely unfinished) AJAX request to prevent callback collisions

		request = $.ajax(
		{
			type: 'GET',
			url: 'pages/' + page,
			success: function(data) // Request was successful
			{
				$('.' + page.replace('/', '\\/')).addClass('active'); // Mark page in navigation
				$('.' + page.replace('/', '\\/')).closest('.dropdown').addClass('active'); // Mark dropdown menu of active page

				$('#page').html(data); // Insert request result into page

				// Reinitialize QR codes
				$('.qr').each(function() // Execute for each QR code on the page
				{
					$(this).qrcode($(this).data('qr')); // Draw QR code with data from HTML
				});

				$('#page').fadeIn(250); // Fade page back in
			},
			error: function(xhr, status, error) // Request failed
			{
				$('#page').html(xhr.responseText); // Insert the response into the page (for example: error page)
				$('#page').fadeIn(250); // Fade page back in
			},
			complete: function(xhr, status) // Request completed (fires on success and error)
			{
				if($('#loader').is(":visible")) // Check if loader is visible...
				{
					$('#loader').finish().fadeOut(100, function() // ...if it is fade it out
					{
						$('#loader .slow').finish().fadeOut(100);
					});
				}
				else
				{
					$('#loader').stop(true, false); // ...if it's not stop all currently active animations
					$('#loader .slow').stop(true, false);
				}
			}
		});
	});

	$('#loader').delay(1000).fadeIn(500, function() // Fade in the loading animation after one second without result
	{
		$('#loader .slow').delay(5000).fadeIn(1000); // Fade in a loading description if loading takes five more seconds
	});
}