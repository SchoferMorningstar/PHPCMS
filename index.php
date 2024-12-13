<?php
require_once "./includes/all.php";
use function Includes\genNav;
use function Includes\lang;
use function Includes\genHeader;
use function Includes\getBlogs;
use function Includes\showBlog;
use function Includes\genFooter;
session_start();
$config = file_get_contents("./config/config.json");
$config = json_decode($config, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $config['siteName']?></title>
  <style>
    <?php
    echo file_get_contents($config['rootPath'].$config['template']."/style.css");
    ?>
  </style>
  
</head>
<body>
  <?php
  echo genHeader($config);
  echo lang($config['language'],genNav($config), $config);
  ?>
  <main>
    <?php
      $blogs = getBlogs($config);
      foreach($blogs as $blog){
        echo showBlog($config, $blog);
      }
    ?>
  </main>
  <?php
  echo genFooter($config);
  ?>
  
</body>
</html>