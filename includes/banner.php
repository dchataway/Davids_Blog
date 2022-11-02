<!--Checks the session to see if a user is available (logged in). If logged in, the username is displayed with the logout link  
	When there is a logged in user, the banner does not get displayed since it is some sort of a welcome screen to guest users. -->
<?php if (isset($_SESSION['user']['username'])) { ?>
	<div class="logged_in_info">
		<span>welcome <?php echo $_SESSION['user']['username'] ?></span>
		|
		<span><a href="logout.php">logout</a></span>
	</div>
<?php }else{ ?>
	<div class="banner">
		<div class="welcome_msg">
			<h1>Welcome to David's test website! </h1>
			<p> 
				This is a website meant to learn front and backend web development. <br> 
				Adapted from <a  style="color:white;text-decoration: underline;margin-left: 0px; padding: 0px; border-radius: 0px;" 
            				href="https://codewithawa.com/posts/how-to-create-a-blog-in-php-and-mysql-database">a tutorial by Awa Melvine.
							</a> <br> 
				Thanks for your interest! <br>
			</p>
			<a href="register.php" class="btn">Register here!</a>
		</div>

		<div class="login_div">
			<form method="post" action="login.php" > <!-- note: the prior code had the action be to redirect to index.php -->
				<h2>Login</h2>
				<div style="width: 60%; margin: 0px auto;">
					<?php include(ROOT_PATH . '/includes/errors.php') ?>
				</div>
				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
				<input type="password" name="password"  placeholder="Password"> 
				<button class="btn" type="submit" name="login_btn">Sign in</button>
			</form>
		</div>
	</div>
<?php } ?>