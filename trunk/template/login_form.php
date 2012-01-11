<?php

	if($erreur=='login_passwd_incorrect') {
		echo '<div class="ui-widget" id="err_login_pass">
				<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
					'. _('login_passwd_incorrect') .'</p>
				</div>
			</div>';
	}
	elseif($erreur=='login_non_connu') {
		echo '<div class="ui-widget" id="err_login_unknow">
				<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
					'. _('login_non_connu') .'</p>
				</div>
			</div>';
	}

?>

	<div id="login_form" class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget-header ui-corner-all ui-helper-clearfix">
			<span><?php echo _('login_fieldset');?></span>
		</div>
		<div class="ui-dialog-content ui-widget-content">
			<form method="post" action="<?php echo $PHP_SELF; ?>">
				<?php
					if ($return_url)
						echo '<input type="hidden" name="return_url" value="'.$return_url.'"/>';
				?>
				<div class="login">
					<label for="session_username"><?php echo _('divers_login_maj_1'); ?></label>
					<input type="text" id="session_username" name="session_username" size="32" maxlength="99"  value="<?php echo $session_username; ?>"/>
				</div>
				<div class="password">
					<label for="session_password"><?php echo _('password'); ?></label>
					<input type="password" id="session_password" name="session_password" size="32" maxlength="99"  value="<?php //echo $session_password; ?>"/>
				</div>
				<div>
					<button type="submit" class="submit">Login</button>
				</div>
				<div class="php-conges_link"><?php echo '<a href="'.$config_url_site_web_php_conges.'/">PHP_CONGES v '.$config_php_conges_version.'</a>';?></div>
			</form>
		</div>
	</div>
	<style>
		#login_form {width: 550px; margin-top: 150px;}
		#login_form .ui-widget-header{padding: 5px;}
		#login_form form {padding: 5px;}
		#login_form form div{padding: 5px;}
		#login_form label{ width: 200px; float: left; text-align: left;}
		#login_form button {width: 200px; margin-top: 10px;}
		#login_form button span{padding: 0px;}
		#login_form .php-conges_link{text-align: right; margin-bottom: -5px; font-size: 10px;}
		
		#err_login_pass , #err_login_unknow{width: 550px; margin-top: 70px; margin-bottom: -120px; font-size: 1em;}
	</style>
	<script type="text/javascript">
		$('#login_form .submit').button();
	</script>
