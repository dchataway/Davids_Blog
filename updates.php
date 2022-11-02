<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php include('includes/head_section.php'); ?>
<title> David's Blog | Updates </title>
</head>
<body>
<div class="container">
	<!-- Navbar -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navbar -->
	
	<div class="content" >

		<h2> Updates </h2>
		<!-- Page wrapper -->
		<div class="post-wrapper">
			<p> I have made the following updates and adaptations to Awa's original tutorial. </p>
			<p> 
			<h3> 1) Cloud Infrastructure </h3>
            <p> Instead of using local infrastructure, I used Azure MySQL Flexible Server and deployed the app using Azure App Service.</p>
			</p>
			<p> 
			<h3> 2) SSL Connection </h3>
            <p> The Azure connection string was erroneous for two reasons: a) $con vs $conn discrepancy, and b) the filename for the CA certificate in the `mysqli_ssl_set` was incorrect. </p>
			<p><i> Note: I am still investigating why App Service cannot connect via SSL to the Azure Flexible MySQL database. It is unclear if it is due to the connection configuration (although I have followed the instructions <a href = "https://learn.microsoft.com/en-ca/azure/mysql/flexible-server/how-to-connect-tls-ssl#disable-ssl-enforcement-on-your-flexible-server"> here</a>) or if it is a limitation of the free version of App Service.</i>
			</p>
			<p> 
			<h3> 3) Admin Dashboard </h3>
            <p> First, I added access control for the admin pages, by adding a check on the user role and redirection if the user does not the necessary permissions. </p>
			<p> Second, Wrote code to display the number of users and posts in the admin dashboard, replacing the static placeholder in the tutorial. </p>
			</p>
            <p> 
			<h3> 3a) Admin User Management </h3>
			<p> The most significant adaptation I made was to allow management of all users (not just admins) by changing the SQL query and associated functions. </p> 
			</p>
			<p>
			<h3> 3b) Admin Post Management </h3>
			<p> First, I modified the UpdatePost function to make the image upload optional. </p> 
            <p> Second, I corrected a bug with the SQL query to switch a post from published to not published.</p>
            <p> Third, I added a query to display the topic of the post in dashboard table.</p>
            <p> Fourth, I changed the foreign key constraint for the post table to accomodate updates, as follows. And I modified the UpdatePost function to demonstrate the correct behavior for updating the topic of a post.
                <pre> <code>
                ALTER TABLE `post_topic` DROP FOREIGN KEY `post_id`;  
                ALTER TABLE `post_topic`  ADD CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
                </code></pre>
                <br>And similarly for the 'topic_id' constraint.</br>
                </p>
			</p>
			<p> 
			<h3> 3c) Post Publishing </h3>
			<p> I resolved an apparent issue in the Publish and Unpublish buttons of the Admin/post dashboard, in which the function to set/toggle "Publish" in the database was not being executed. 
			I think this issue was an incompatibility with the App Service PHP version and the structure of the admin_functions and post_functions scripts (because it worked with my local device running PHP 8.1.4). 
			To fix it, I consolidated the two function scripts and moved all the function blocks to the bottom of the script.   </p> 
			</p> 
		</div>
		<!-- // Page wrapper -->
	</div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>