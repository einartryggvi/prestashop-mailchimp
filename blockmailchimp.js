$(function(){
	$('#blockmailchimp form').submit(function(e) {
		e.preventDefault();
		var $this = $(this);
		$.post($this.attr('action'), $this.serialize(), function(d) {
			if (d.success === true) {
				$this.hide();
				$('#blockmailchimp .alert-success').show();
			}
			else {
				$this.hide();
				$('#blockmailchimp .alert-error').show();
			}
		});
	});
	$('#blockmailchimp .alert .close').click(function(e) {
		e.preventDefault();
		$('#blockmailchimp form').show();
		$('#blockmailchimp .alert').hide();
	});
});