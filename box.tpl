<div id="blockmailchimp" class="block">
	<h4>{l s='Newsletter' mod='blockmailchimp'}</h4>
	<div class="block_content">
		<form action="{$mailchimp_subscribeUrl}" method="post">
			<label for="mailchimp_email">{l s='Email' mod='blockmailchimp'}</label>
			<input type="email" value="" name="mailchimp_email" class="required email" id="mailchimp_email" />
			<input type="submit" value="{l s='Subscribe' mod='blockmailchimp'}" name="subscribe" class="button" />
		</form>
		<div class="alert alert-success">
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<strong>{l s='Success!' mod='blockmailchimp'}</strong>
			{l s='Your email has been subscribed to our newsletter successfully.' mod='blockmailchimp'}
		</div>
		<div class="alert alert-error">
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<strong>{l s='Error!' mod='blockmailchimp'}</strong>
			{l s='There was a problem subscribing your email to our newsletter.' mod='blockmailchimp'}
		</div>
	</div>
</div>
