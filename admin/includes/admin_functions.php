<?php 
// ACCESS CHECK
if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
	//$_SESSION['message'] = "You are an Admin or Author of David's Blog.";
	//exit(0);
} else {
	$_SESSION['message'] = "You are not an Admin or Author. Please return to the home page.";
	// redirect to public area
	header('location: ' . BASE_URL . 'index.php');				
	exit(0);
}

// Admin user variables
$user_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
// general variables
$errors = [];
// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

// Calculate the number of users and posts
// Number of users
$sql = "SELECT COUNT(distinct id) as total FROM users";
$result = mysqli_query($conn, $sql);
$N_users = mysqli_fetch_assoc($result);
// Number of users
$sql = "SELECT COUNT(distinct id) as total FROM posts";
$result = mysqli_query($conn, $sql);
$N_posts = mysqli_fetch_assoc($result);

/* - - - - - - - - - - 
-  Admin users actions
- - - - - - - - - - -*/
// if user clicks the create admin button
if (isset($_POST['create_user'])) {
	createUser($_POST);
}
// if user clicks the Edit User button
if (isset($_GET['edit-user'])) {
	$isEditingUser = true;
	$user_id = $_GET['edit-user'];
	editUser($user_id);
}
// if user clicks the update user button
if (isset($_POST['update_user'])) {
	updateUser($_POST);
}
// if user clicks the Delete user button
if (isset($_GET['delete-user'])) {
	$user_id = $_GET['delete-user'];
	deleteUser($user_id);
}


// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

// Code to publish/unpublish post
// if user clicks the publish post button
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
	$message = "";
	if (isset($_GET['publish'])) {
		$message = "Post published successfully";
		$post_id = $_GET['publish'];
	} else if (isset($_GET['unpublish'])) {
		$message = "Post successfully unpublished";
		$post_id = $_GET['unpublish'];
	}
	togglePublishPost($post_id, $message);
}

/* - - - - - - - - - - 
-  Post actions
- - - - - - - - - - -*/
// if user clicks the create post button
if (isset($_POST['create_post'])) { createPost($_POST); }
// if user clicks the Edit post button
if (isset($_GET['edit-post'])) {
	$isEditingPost = true;
	$post_id = $_GET['edit-post'];
	editPost($post_id);
}
// if user clicks the update post button
if (isset($_POST['update_post'])) {
	updatePost($_POST);
}
// if user clicks the Delete post button
if (isset($_GET['delete-post'])) {
	$post_id = $_GET['delete-post'];
	deletePost($post_id);
}


/* - - - - - - - - - - 
-  Topic actions
- - - - - - - - - - -*/
// if user clicks the Create topic button
if (isset($_POST['create_topic'])) {createTopic($_POST);}
// if user clicks the Edit topic button
if (isset($_GET['edit-topic'])) {
	$isEditingTopic = true;
	$topic_id = $_GET['edit-topic'];
	editTopic($topic_id);
}
// if user clicks the Update topic button
if (isset($_POST['update_topic'])) {
	updateTopic($_POST);
}
// if user clicks the Delete topic button
if (isset($_GET['delete-topic'])) {
	$topic_id = $_GET['delete-topic'];
	deleteTopic($topic_id);
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Returns all users and their corresponding roles
- Previously query was "SELECT * FROM users WHERE role IS NOT NULL";
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function getUsers(){
	global $conn, $roles;
	$sql = "SELECT * FROM users";
	$result = mysqli_query($conn, $sql);
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

	return $users;
}
/* * * * * * * * * * * * * * * * * * * * *
* - Escapes form submitted value, hence, preventing SQL injection
* * * * * * * * * * * * * * * * * * * * * */
function esc(String $value){
	// bring the global db connect object into function
	global $conn;
	// remove empty space sorrounding string
	$val = trim($value); 
	$val = mysqli_real_escape_string($conn, $value);
	return $val;
}
// Receives a string like 'Some Sample String'
// and returns 'some-sample-string'
function makeSlug(String $string){
	$string = strtolower($string);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}

/* - - - - - - - - - - - -
-  Admin users functions
- - - - - - - - - - - - -*/
/* * * * * * * * * * * * * * * * * * * * * * *
* - Receives new data from admin form
* - Create new user
* - Returns all users with their roles 
* * * * * * * * * * * * * * * * * * * * * * */
function createUser($request_values){
	global $conn, $errors, $role, $username, $email;
	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);

	if(isset($request_values['role'])){
		$role = esc($request_values['role']);
	}
	// form validation: ensure that the form is correctly filled
	if (empty($username)) { array_push($errors, "Uhmm...We gonna need the username"); }
	if (empty($email)) { array_push($errors, "Oops.. Email is missing"); }
	if (empty($role)) { array_push($errors, "Role is required for admin users");}
	if (empty($password)) { array_push($errors, "uh-oh you forgot the password"); }
	if ($password != $passwordConfirmation) { array_push($errors, "The two passwords do not match"); }
	// Ensure that no user is registered twice. 
	// the email and usernames should be unique
	$user_check_query = "SELECT * FROM users WHERE username='$username' 
							OR email='$email' LIMIT 1";
	$result = mysqli_query($conn, $user_check_query);
	$user = mysqli_fetch_assoc($result);
	if ($user) { // if user exists
		if ($user['username'] === $username) {
		  array_push($errors, "Username already exists");
		}

		if ($user['email'] === $email) {
		  array_push($errors, "Email already exists");
		}
	}
	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password);//encrypt the password before saving in the database
		$query = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
				  VALUES('$username', '$email', '$role', '$password', now(), now())";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "User created successfully";
		header('location: ' . BASE_URL . '/admin/users.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Takes user id as parameter
* - Fetches the user from database (users table)
* - sets user fields on admin form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editUser($user_id)
{
	global $conn, $username, $role, $isEditingUser, $user_id, $email;

	$sql = "SELECT * FROM users WHERE id=$user_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$user = mysqli_fetch_assoc($result);

	// set form values ($username and $email) on the form to be updated
	$username = $user['username'];
	$email = $user['email'];
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Receives admin request from form and updates in database
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateUser($request_values){
	global $conn, $errors, $role, $username, $isEditingUser, $user_id, $email;
	// get id of the user to be updated
	$user_id = $request_values['user_id'];
	// set edit state to false
	$isEditingUser = false;


	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);
	if(isset($request_values['role'])){
		$role = $request_values['role'];
	}
	// register user if there are no errors in the form
	if (count($errors) == 0) {
		//encrypt the password (security purposes)
		$password = md5($password);

		$query = "UPDATE users SET username='$username', email='$email', role='$role', password='$password' WHERE id=$user_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "User updated successfully";
		header('location: ' . BASE_URL . '/admin/users.php');
		exit(0);
	}
}
// delete user 
function deleteUser($user_id) {
	global $conn;
	$sql = "DELETE FROM users WHERE id=$user_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "User successfully deleted";
		header('location: ' . BASE_URL . '/admin/users.php');
		exit(0);
	}
}

/* - - - - - - - - - - 
-  Topics functions
- - - - - - - - - - -*/
// get all topics from DB
function getAllTopics() {
	global $conn;
	$sql = "SELECT * FROM topics";
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
}
function createTopic($request_values){
	global $conn, $errors, $topic_name;
	$topic_name = esc($request_values['topic_name']);
	// create slug: if topic is "Life Advice", return "life-advice" as slug
	$topic_slug = makeSlug($topic_name);
	
	// validate form
	if (empty($topic_name)) {
		array_push($errors, "Topic name required");
	}
	// Ensure that no topic is saved twice.
	$topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";
	$result = mysqli_query($conn, $topic_check_query);
	if (mysqli_num_rows($result) > 0) { // if topic exists 
		array_push($errors, "Topic already exists");
	}
	// register topic if there are no errors in the form
	if (count($errors) == 0) {
		$query = "INSERT INTO topics (name, slug) VALUES('$topic_name', '$topic_slug')";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Topic created successfully";
		header('location: ' . BASE_URL . '/admin/topics.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Takes topic id as parameter
* - Fetches the topic from database
* - sets topic fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
// Edit topic
function editTopic($topic_id) {
	global $conn, $topic_name, $isEditingTopic, $topic_id;
	$sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	// set form values ($topic_name) on the form to be updated
	$topic_name = $topic['name'];
}
// Update topic
function updateTopic($request_values) {
	global $conn, $errors, $topic_name, $topic_id;
	$topic_name = esc($request_values['topic_name']);
	$topic_id = esc($request_values['topic_id']);
	// create slug: if topic is "Life Advice", return "life-advice" as slug
	$topic_slug = makeSlug($topic_name);
	// validate form
	if (empty($topic_name)) { 
		array_push($errors, "Topic name required"); 
	}
	// register topic if there are no errors in the form
	if (count($errors) == 0) {
		$query = "UPDATE topics SET name = '$topic_name', slug='$topic_slug' WHERE id=$topic_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Topic update successfully";
		header('location: ' . BASE_URL . '/admin/topics.php');
		exit(0);
	}
}

// Delete topic
function deleteTopic($topic_id) {
	global $conn;
	$sql = "DELETE FROM topics WHERE id=$topic_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Topic successfully deleted";
		header('location: ' . BASE_URL . '/admin/topics.php');
		exit(0);
	}
}



/* - - - - - - - - - - 
-  Post functions
- - - - - - - - - - -*/
// get all posts from DB
function getAllPosts()
{
	global $conn;
	
	// Admin can view all posts
	// Author can only view their posts
	if ($_SESSION['user']['role'] == "Admin") {
		$sql = "SELECT * FROM posts";
	} elseif ($_SESSION['user']['role'] == "Author") {
		$user_id = $_SESSION['user']['id'];
		$sql = "SELECT * FROM posts WHERE user_id=$user_id";
	}
	$result = mysqli_query($conn, $sql);
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['author'] = getPostAuthorById($post['user_id']);
		$post['topic'] = getTopic($post['id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}
// get the author/username of a post
function getPostAuthorById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// return username
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}
// get the topic id of a post
function getTopic($post_id)
{
	global $conn;
	$sql = "SELECT topic_id FROM post_topic WHERE post_id=$post_id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// return topic_id	
		return mysqli_fetch_assoc($result)['topic_id'];
	} else {
		return null;
	}
}

// delete blog post
function togglePublishPost($post_id, $message)
{
	global $conn;
	$sql = "UPDATE posts SET published = CASE published WHEN 1 THEN 0 WHEN 0 THEN 1 ELSE 0 END WHERE id=$post_id";
	// 	$sql = "UPDATE posts SET published=!published WHERE id=$post_id";

	if (mysqli_query($conn, $sql)) {
	$_SESSION['message'] = $message;
	header('location: ' . BASE_URL . '/admin/posts.php');
	exit(0);
	}
}

/* - - - - - - - - - - 
-  Post functions
- - - - - - - - - - -*/
function createPost($request_values)
	{
		global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
		//$errors = [];

		$title = esc($request_values['title']);
		$body = htmlentities(esc($request_values['body']));
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		if (isset($request_values['publish'])) {
			$published = esc($request_values['publish']);
		}
		// create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
		$post_slug = makeSlug($title);
		// validate form
		if (empty($title)) { array_push($errors, "Post title is required"); }
		if (empty($body)) { array_push($errors, "Post body is required"); }
		if (empty($topic_id)) { array_push($errors, "Post topic is required"); }
		// Get image name
	  	$featured_image = $_FILES['featured_image']['name'];
	  	if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
	  	// image file directory
	  	$target = "../static/images/" . basename($featured_image);
        // move_uploaded_file() will attempt the upload, even if it is in a conditional statement, and return false if unsuccessful
	  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
	  		array_push($errors, "Failed to upload image. Please check file settings for your server");
	  	}
		// Ensure that no post is saved twice. 
		$post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
		$result = mysqli_query($conn, $post_check_query);

		if (mysqli_num_rows($result) > 0) { // if post exists
			array_push($errors, "A post already exists with that title.");
		}
		// create post if there are no errors in the form
		if (count($errors) == 0) {
			$query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES(1, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
			if(mysqli_query($conn, $query)){ // if post created successfully
				$inserted_post_id = mysqli_insert_id($conn);
				// create relationship between post and topic
				$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
				mysqli_query($conn, $sql);

				$_SESSION['message'] = "Post created successfully";
				header('location: ' . BASE_URL . '/admin/posts.php');
				exit(0);
			}
		}
	}

/* * * * * * * * * * * * * * * * * * * * *
* - Takes post id as parameter (note - role_id is a poor choice of variable name)
* - Fetches the post from database
* - sets post fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editPost($role_id)
	{
		global $conn, $title, $post_slug, $body, $published, $isEditingPost, $post_id;
		$sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$post = mysqli_fetch_assoc($result);
		// set form values on the form to be updated
		$title = $post['title'];
		$body = $post['body'];
		$published = $post['published'];
	}

	function updatePost($request_values)
	{
		global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

		$title = esc($request_values['title']);
		$body = esc($request_values['body']);
		$post_id = esc($request_values['post_id']);
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		// create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
		$post_slug = makeSlug($title);

		if (empty($title)) { array_push($errors, "Post title is required"); }
		if (empty($body)) { array_push($errors, "Post body is required"); }
	
		//check if an image was included
		if (empty($_FILES['featured_image']['name'])) { 
			$query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, body='$body', published=$published, updated_at=now() WHERE id=$post_id";
		}
		else {
			// Get image name
			$featured_image = $_FILES['featured_image']['name'];
			// image file directory
			$target = "../static/images/" . basename($featured_image);
			// move_uploaded_file() will attempt the upload, even if it is in a conditional statement, and return false if unsuccessful
			if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
				array_push($errors, "Failed to upload image. Please check file settings for your server");
				}	
			$query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
		}
		// if new featured image has been provided
		/*if (isset($_POST['featured_image'])) {
			// Get image name
		  	$featured_image = $_FILES['featured_image']['name'];
		  	// image file directory
		  	$target = "../static/images/" . basename($featured_image);

			if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
		  		array_push($errors, "Failed to upload image. Please check file settings for your server");
		  	}
		}*/

		// register topic if there are no errors in the form
		if (count($errors) == 0) {
			// attach topic to post on post_topic table
			if(mysqli_query($conn, $query)){ // if post created successfully
				if (isset($topic_id)) {
					
					$inserted_post_id = mysqli_insert_id($conn);
					// create relationship between post and topic
					$sql = "UPDATE post_topic SET topic_id = '$topic_id' WHERE post_id = '$post_id';";
					mysqli_query($conn, $sql);
					
					$_SESSION['message'] = "Post created successfully";
					header('location: ' . BASE_URL . '/admin/posts.php');
					exit(0);
				}
			}
			$_SESSION['message'] = "Post updated successfully";
			header('location: ' . BASE_URL . '/admin/posts.php');
			exit(0);
		}
	}
	// delete blog post
	function deletePost($post_id)
	{
		global $conn;
		$sql = "DELETE FROM posts WHERE id=$post_id";
		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "Post successfully deleted";
			header('location: ' . BASE_URL . '/admin/posts.php');
			exit(0);
		}
	}


?>

