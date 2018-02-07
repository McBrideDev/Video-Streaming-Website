//  Andy Langton's show/hide/mini-accordion @ http://andylangton.co.uk/jquery-show-hide

// this tells jquery to run the function below once the DOM is ready
$(document).ready(function() {

	// choose text for the show/hide link - can contain HTML (e.g. an image)
	var showText='+';
	var hideText='-';
	// initialise the visibility check
	var is_visible = false;

	// append show/hide links to the element directly preceding the element with a class of "toggle"
	$('.toggle').prev().append(' <a href="#" class="toggleLink">'+hideText+'</a>');

	// hide all of the elements with a class of 'toggle'
	$('.toggle').show();

	// capture clicks on the toggle links
	$('a.toggleLink').click(function() {

	// switch visibility
	is_visible = !is_visible;

	// change the link text depending on whether the element is shown or hidden
	if ($(this).text()==hideText) {
	$(this).text(showText);
	$(this).parent().next('.toggle').slideUp('slow');

	}
	else {
	$(this).text(hideText);
	$(this).parent().next('.toggle').slideDown('slow');
	}

	// return false so any link destination is not followed
	return false;

	});

	//File info
	//
	//
	$('#fileSelectCSV').on('change',function(e){
		var fileName = $(this).val().split('/').pop().split('\\').pop();
		$('#fileSelectCSVInfo').html(fileName)
	})

	function readURL(input, el) {

	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	        	// console.log(e.target.result);
	            $(el).html('<img height="50" width="50" style="margin-left:15px;" src="'+e.target.result+'" />', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$("#fileSelect").change(function(){
	    readURL(this, '#fileSelectInfo');
	});

	$("#fileSelectWall").change(function(){
	    readURL(this, '#fileSelectInfoWall');
	});


});