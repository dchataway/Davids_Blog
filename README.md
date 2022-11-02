# David's Blog: A website to test web development with Azure App Service 
<p> Date: 11/02/2022 </p>
<p> By: David Chataway </p>
<p> Website URL: https://david-web-app-dev.azurewebsites.net/ </p>

## Purpose
Please use this repository to learn how you can build and deploy a blog using cloud infrastructure. My code was adapted from the great tutorial published by Awa Melvine [linked here](https://codewithawa.com/posts/how-to-create-a-blog-in-php-and-mysql-database). I adapted it to utilize Azure resources and also fixed a few bugs.

## How to Use
Although you can choose any infrastructure you'd like, I chose Azure App Service (free F1 plan) and Azure Database for MySQL flexible server (B1s SKU) for cost effectiveness (resulting in $5-$10 per month). App Service has a few tutorials to help you get started ([here](https://learn.microsoft.com/en-us/azure/app-service/quickstart-php?pivots=platform-linux&tabs=cli) and [here](https://docs.microsoft.com/en-us/azure/app-service/configure-language-php?pivots=platform-linux) and I found the implementation to be relatively straightforward.

## Configuration
You will need to create your own config file because I have removed mine from the repository. I recommend modifying the base config.php file that Awa Melvine posted, shown below. 
      
      <?php 
      session_start();
      // connect to database
      $conn = mysqli_connect("localhost", "root", "", "complete-blog-php");
      if (!$conn) {
        die("Error connecting to database: " . mysqli_connect_error());
      }
        // define global constants
      define ('ROOT_PATH', realpath(dirname(__FILE__)));
      define('BASE_URL', 'http://localhost/complete-blog-php/');
    ?>

### Enjoy!
