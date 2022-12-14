<?php require_once('config.php') ?>
<!-- The first include should be config.php -->

<!-- include the public functions file and its code -->
<?php require_once( ROOT_PATH . '/includes/public_functions.php') ?>

<!-- handles registration and login -->
<?php require_once( ROOT_PATH . '/includes/registration_login.php') ?>

<!-- Retrieve all posts from database  -->
<?php $posts = getPublishedPosts(); ?>

<?php require_once(ROOT_PATH . '/includes/head_section.php') ?>
	<title>David's Blog | Home </title>
</head>
<body>
	<!-- container - wraps whole page -->
	<div class="container">
		<!-- navbar -->
        <?php include(ROOT_PATH . '/includes/navbar.php') ?>
		<!-- // navbar -->
        
        <!-- banner -->
        <?php include(ROOT_PATH . '/includes/banner.php') ?>
		
		<!-- Page content -->
		<div class="content">
			<h2 class="content-title">Recent Articles</h2>
			<hr>
			<!-- more content still to come here ... -->
			
			<!-- Loop through and display these posts -->
            <?php foreach ($posts as $post): ?>
            	<div class="post" style="margin-left: 0px;">
            		<img src="<?php echo '/static/images/' . $post['image']; ?>" class="post_image" alt="">
            		
    		        <!-- Display topic along with the post -->
            		<?php if (isset($post['topic']['name'])): ?>
            			<a 
            				href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $post['topic']['id'] ?>"
            				class="btn category">
            				<?php echo $post['topic']['name'] ?>
            			</a>
            		<?php endif ?>
            		
            		<!-- Single_post  page that displays the full post in detail together with comments -->
					<!-- SLUG refers to the last part of a full URL, refering to a specific page -->            		
            		<a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
            			<div class="post_info">
            				<h3><?php echo $post['title'] ?></h3>
            				<div class="info">
            					<span><?php echo date("F j, Y ", strtotime($post["created_at"])); ?></span>
            					<span class="read_more">Read more...</span>
            				</div>
            			</div>
            		</a>
            	</div>
            <?php endforeach ?>
			
		</div>
		<!-- // Page content -->

		<!-- footer -->
        <?php include(ROOT_PATH . '/includes/footer.php') ?>
