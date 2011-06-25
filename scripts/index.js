$(document).ready(function() {
	 $("#contact").validate({
	 	// Set up rules for each field. Reference each one by its "name" not "id"
		rules: {
	    	Leave_Blank: { maxlength: 0 },
	    	Your_Name: { required: true },
	    	Email_Address: { required: true, email: true },
	    	Message: { required: true }
		}
	});
	// Submit form using AJAX and clear the submitted results
	$('#contact').ajaxForm({
		url: '/sandbox/form/scripts/form/mail.php',
		target: '#message',
		success: successMessage,
		clearForm: true,
		resetForm: true
	});
});
// Fade in success message
function successMessage() {
	$('#message').fadeIn();
}