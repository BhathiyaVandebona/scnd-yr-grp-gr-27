<!--This is the sign up page-->
<?php
require_once 'core/init.php';
var_dump(Token::check_token(Input::get('token')));

if(Input::exists()) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		// use the names given to the fields here
		// as the keys
		'firstname' => array(
			'required' => true,
			'min' => 4,
			'max' => 255
		),
		'lastname' => array(
			'required' => true,
			'min' => 4,
			'max' => 255
		),
		'username' => array(
			'required' => true,
			'min' => 4,
			'max' => 255,
			'unique' => 'users'
		),
		'mail' => array(
			'required' => true,
		),
		'password' => array(
			// can add more attributes here for security
			// like strength
			'required' => true,
			'min' => 8	
		),
		're_enterpassword' => array(
			// can add more attributes here for security
			// like strength
			'required' => true,
			'matches' => 'password',
			'min' => 8	
		),
		'session' => array(
			'session_name' => 'user',
			'token_name' => 'token'
		)
	));

	if ($validation->passed()) {
		echo 'Passed';
	} else {
		foreach ($validation->errors() as $error) {
			echo $error, '<br>';
		}
	}
}
?>

<div class="container">
	<form action="" method="POST">
		<div class="field">
			<label for="firstname">First Name</label>
			<!-- 
				Since the users have to re-enter input every time they make a mistake this can be used to avoid the redundancy in
				type the same inputs again and again.
				This goes inside the value field 
			-->
			<input type="text" name="firstname" id="firstname" value="<?php echo(escape(Input::get('firstname')))?>" autocomplete="off"/>
		</div>
		<div class="field">
			<label for="lastname">Last Name</label>
			<input type="text" name="lastname" id="lastname" value="<?php echo escape(Input::get('lastname'))?>" autocomplete="off"/>
		</div>
		<div class="field">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'))?>" autocomplete="off"/>
		</div>
		<div class="field">
			<label for="mail">Email</label>
			<input type="text" name="mail" id="mail" value=""  <?php echo escape(Input::get('mail'))?> autocomplete="off"/>
		</div>
		<!--This is for the phone number of the user but this field was not included in the database hence this is commented out-->
		<!--
		<div class="field">
			<label for="phonenumber">Phone number</label>
			<input type="text" name="phonenumber" id="phonenumber" value="" autocomplete="off"/>
		</div>
		-->	
		<div class="field">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" value="" autocomplete="off"/>
		</div>
		<div class="field">
			<label for="re_enterpassword">Re-Enter password</label>
			<input type="password" name="re_enterpassword" id="re_enterpassword" value="" autocomplete="off"/>
		</div>
		<!--Crossite request forgery prevention-->
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="Register">
	</form>
</div>