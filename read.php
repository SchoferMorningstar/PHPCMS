<?php
if(!isset($_GET['blog'])) header('Location: list.php');
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
  <title><?php echo $config['siteName']." | ".$_GET['blog']?></title>
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
      include_once($config['rootPath']."includes/Parsedown.php");
      $parser = new Includes\Parsedown();
      $directory = $config['rootPath'].$config['blogDir'].$_GET['blog'];
      $lines = file($directory, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $title = $parser->text($lines[0]);
      $text = "";
      for($i = 1; $i < count($lines); $i++){
        $text .= $parser->text($lines[$i]);
      }
      $time = date("j F Y g:i a", filemtime($directory));
      $html = file_get_contents($config['rootPath'].$config['template']. "/blog.html");
      $html = str_replace(["{{BLOG_TITLE}}", "{{BLOG_TEXT}}", "{{BLOG_TIME}}"], [$title, $text, $time], $html);

      echo $html;
    ?>
  </main>
  <?php
  echo genFooter($config);
  ?>
  
</body>
</html>