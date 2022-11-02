<?php  include('../config.php'); ?>
	<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
	<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
	<title>Admin | Dashboard</title>
</head>
<body>
    <!-- Header section -->
	<div class="header">
		<div class="logo">
			<a href="<?php echo BASE_URL .'admin/dashboard.php' ?>">
				<h1>David's Blog - Admin</h1>
			</a>
		</div>
        <!-- Checks if the session has a user and if so, displays user info and logout button -->
		<?php if (isset($_SESSION['user'])): ?>
			<div class="user-info">
				<span><?php echo $_SESSION['user']['username'] ?></span> &nbsp; &nbsp; 
				<a href="<?php echo BASE_URL . 'logout.php'; ?>" class="logout-btn">logout</a>
			</div>
		<?php endif ?>
	</div>
    <!-- Display the containers of the dashboard -->
	<div class="container dashboard">
		<h1>Welcome</h1>
		<div class="stats">
			<a href="users.php" class="first">
				<span><?php echo $N_users['total']; ?></span> <br>
				<span>Registered users</span>
			</a>
			<a href="posts.php">
				<span><?php echo $N_posts['total']; ?></span> <br>
				<span>Published posts</span>
			</a>
			<a>
				<span>(TO BE COMPLETED)</span> <br>
				<span>Published comments</span>
			</a>
		</div>
		<br><br><br>
		<div class="buttons">
			<a href="users.php">Add Users</a>
			<a href="posts.php">Add Posts</a>
		</div>
	</div>
</body>
</html>