$(document).ready(function() {
	 $("#contact").validate({
		rules: {
	    	Leave_Blank: { maxlength: 0 },
	    	Your_Name: { required: true },
	    	Email_Address: { required: true, email: true },
	    	Message: { required: true }
		}
	});
	$('#contact').ajaxForm({
		url: '/sandbox/form/scripts/form/mail.php',
		target: '#message',
		success: successMessage,
		clearForm: true,
		resetForm: true
	});
});
function successMessage() {
	$('#message').fadeIn();
}