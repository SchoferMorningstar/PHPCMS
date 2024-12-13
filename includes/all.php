<?php
namespace Includes;
/**
 * Summary of genNav
 * @param array $config Data array from configuration file
 * @return string HTML code for navbar
 */
function genNav($config){
  $navHTML = file_get_contents($config['rootPath'].$config['template']."/nav.html");
  $nav = "";
  foreach($config['nav'] as $navItem){
    if($navItem['scope'] == "all") $nav .= "<li><a href='".$navItem['link']."'>".$navItem['text']."</a></li>";
    else if($navItem["scope"] == "admin") $nav .= (isset($_SESSION['admin']) and $_SESSION['admin'] == true) ? "<li><a href='".$navItem['link']."'>".$navItem['text']."</a></li>": "";
  }
  $nav = str_replace("{{LINKS}}", $nav, $navHTML);

  return $nav;
}

/**
 * Replace the text to language specific text
 * @param string $langKey
 * @param string $text
 * @param array $config
 * @return string
 */
function lang($langKey, $text, $config){
  $lang = file_get_contents($config['rootPath']."languages/$langKey/index.json");
  $lang = json_decode($lang, true);
  foreach($lang as $key => $value){
    $text = str_replace($key, $value, $text);
  }
  return $text;
}

/**
 * Generates header base od config file data
 * @param array $config
 * @return string
 */
function genHeader($config){
  $header = file_get_contents($config['rootPath'].$config['template']."/header.html");
  $header = str_replace(["{{WEBSITE_NAME}}", "{{WEBSITE_SLOGAN}}"], [$config['siteName'], $config['siteSlogan']], $header);

  return $header;
}

/**
 * Gets all the blogs in the blog directory
 * @param array $config
 * @return array|bool
 */
function getBlogs($config){
  $directory = $config['rootPath'].$config['blogDir'];
  $files = array_diff(scandir($directory), array('.', '..'));
  usort($files, function($a, $b) use ($directory) {
    return filemtime($directory . '/' . $a) <=> filemtime($directory . '/' . $b);
  });
  return $files;
}

/**
 * Returns a string with HTML tags that shows blog card
 * @param array $config
 * @param string $blogName
 * @return string
 */
function showBlog($config, $blogName){
  include_once($config['rootPath']."includes/Parsedown.php");
  $parser = new Parsedown();
  $directory = $config['rootPath'].$config['blogDir'].$blogName;
  $file = fopen($directory, "r");
  $title = $parser->text(fgets($file));
  fgets($file);
  $short = $parser->text(fgets($file));
  $time = date("j F Y g:i a", filemtime($directory));
  $html = file_get_contents($config['rootPath'].$config['template']. "/blogs.html");
  $html = str_replace(["{{BLOG_TITLE}}", "{{BLOG_SHORT}}", "{{BLOG_TIME}}", "{{BLOG_FILE}}"], [$title, $short, $time, $blogName], $html);

  return $html;
}


function genFooter($config){
  $footer = file_get_contents($config['rootPath'].$config['template']."/footer.html");
  $footer = str_replace(["{{VERSION}}", "{{WEBSITE_NAME}}"], [$config['version'], $config['siteName']], $footer);

  return $footer;
}