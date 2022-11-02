<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php 
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}
	$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
<title> David's Blog | About </title>
</head>
<body>
<div class="container">
	<!-- Navbar -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navbar -->
	
	<div class="content" >

		<h2> About </h2>
		<!-- Page wrapper -->
		<div class="post-wrapper">
			<p>
			This blog is an opportunity for me to test developing and deploying the frontend and backend of a blog on Azure. I have adapted the code 
			from <a  style="text-decoration: underline;margin-left: 0px;padding=0px;border-radius: 0px;" 
            				href="https://codewithawa.com/posts/how-to-create-a-blog-in-php-and-mysql-database">a great tutorial by Awa Melvine
							</a> to move to a cloud-based architecture.
			</p>
			<p> 
				The Azure resources I used include:
				<ul> 
				<li>App Service, </li>
				<li> MySQL Database. </li>
				</ul>
			</p> 
		</div>
		<!-- // Page wrapper -->
	</div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>