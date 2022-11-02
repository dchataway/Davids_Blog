<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php 
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}
	$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
<title> David's Blog | Contact </title>
</head>
<body>
<div class="container">
	<!-- Navbar -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navbar -->
	
	<div class="content" >

		<h2> Contact </h2>
		<!-- Page wrapper -->
		<div class="post-wrapper">
			<p>
			Please reach out to me on  <a  style="text-decoration: underline;margin-left: 0px;padding=0px;border-radius: 0px;" 
            				href="https://www.linkedin.com/in/davidchataway/">LinkedIn
							</a>.
			</p>
		</div>
		<!-- // Page wrapper -->
	</div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>