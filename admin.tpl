<form method="post">
	<fieldset>
		<legend><img src="../img/admin/contact.gif" />{l s='MailChimp Settings'}</legend>
		<label class="required">{l s='MailChimp API Key'} <sup>*</sup></label>
		<div class="margin-form required">
			<input required="true" type="text" size="33" name="apikey" value="{$mailchimp_apikey}" />
		</div>
		<label class="required">{l s='MailChimp List'} <sup>*</sup></label>
		<div class="margin-form required">
		{if !empty($mailchimp_lists)}
			<select name="listId">
			{foreach from=$mailchimp_lists key=k item=v}
				<option value="{$k}"{if $v == $mailchimp_listid} selected="selected"{/if}>{$v}</option>
			{/foreach}
			</select>
		{/if}
		</div>
		<div class="margin-form">
			<input type="submit" name="submit" value="{l s='Update settings'}" class="button" />
		</div>
	</fieldset>
</form>