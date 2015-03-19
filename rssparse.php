<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
require_once(dirname(__FILE__) . '/../../../wp-config.php');
nocache_headers();

if (class_exists('SimplePie'))
{
	if (SIMPLEPIE_BUILD < 20080102221556) // SimplePie 1.1
	{
		echo 'This plugin requires a newer version of the <a href="http://wordpress.org/extend/plugins/simplepie-core">SimplePie Core</a> plugin to enable important functionality. Please upgrade the plugin to the latest version.';
	}
}
else
{
	echo 'This plugin relies on the <a href="http://wordpress.org/extend/plugins/simplepie-core">SimplePie Core</a> plugin to enable important functionality. Please download, install, and activate it, or upgrade the plugin if you\'re not using the latest version.';
}
 
// We'll process this feed with all of the default options.
$feed = new SimplePie();
 
// Set which feed to process.
$rssurl = $_GET['url'];
$feed->enable_order_by_date(false);
//somthing like this
//http://beyondthewhiteboard.com/gyms/4860-threefold-crossfit/wods.atom
$feed->set_feed_url($rssurl);
 
// Run SimplePie.
$feed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();

$actual_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
// Let's begin our XHTML webpage code.  The DOCTYPE is supposed to be the very first thing, so we'll keep it on the same line as the closing-PHP tag.
?>

<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom" xmlns:btwb="http://beyondthewhiteboard.com/">
    <id>tag:btwb-plugin,2015-01-01:<?php echo $_SERVER['REQUEST_URI'] ?></id>
    <link rel="self" type="application/atom+xml" href="<?php echo $actual_link ?>"/>
    <title><?php echo $feed->get_title(); ?></title>
    <updated><?php echo $feed->get_feed_tags("http://www.w3.org/2005/Atom","updated")[0]["data"]; ?></updated>
    <?php 
    $lastDate = "test";
    $lastIndes = "-1";
    for ($i = 0; $i < count($feed->get_items()); $i++) {
        $item = $feed->get_items()[$i];
        if ($lastDate == $item->get_item_tags("http://beyondthewhiteboard.com/","assigned")[0]["data"]) {
            //$feed->get_items()[$lastIndex]->set_content($feed->get_items()[$lastIndex] . "\n" . $item->get_content());
            $content[$lastIndex]=$content[$lastIndex] . "\n\n" . $item->get_title() . "\n" . $item->get_content();
            $title[$lastIndex]=$title[$lastIndex] . " &amp; " . $item->get_title();
        } else {
            $lastDate = $item->get_item_tags("http://beyondthewhiteboard.com/","assigned")[0]["data"];
            $lastIndex = $i;
            $content[$i]=$item->get_title()."\n".$item->get_content();
            $title[$i]=$item->get_title();
        }
    }
    $lastDate = "test";
      for ($i = 0; $i < count($feed->get_items()); $i++) {
        $item = $feed->get_items()[$i];
        if ($lastDate != $item->get_item_tags("http://beyondthewhiteboard.com/","assigned")[0]["data"]) {
            $lastDate = $item->get_item_tags("http://beyondthewhiteboard.com/","assigned")[0]["data"];
    ?>
    <entry>
        <id><?php echo $item->get_item_tags("http://www.w3.org/2005/Atom","id")[0]["data"];?></id>
        <published><?php echo $item->get_item_tags("http://www.w3.org/2005/Atom","published")[0]["data"];?></published>
        <updated><?php echo $item->get_item_tags("http://www.w3.org/2005/Atom","updated")[0]["data"];?></updated>
        <link rel="alternate" type="text/html" href="<?php echo $item->get_item_tags("http://www.w3.org/2005/Atom","link")[0]["attribs"][""]["href"]; ?>"/>
        <title><?php echo $item->get_item_tags("http://beyondthewhiteboard.com/","assigned")[0]["data"]." - ".$title[$i]; ?></title>
        <btwb:assigned><?php echo $item->get_item_tags("http://beyondthewhiteboard.com/","assigned")[0]["data"]; ?></btwb:assigned>
        <summary>
            <?php echo $content[$i] ?>
        </summary>
        <author><name><?php echo $item->get_author(); ?></name></author>
    </entry>
    <?php } } ?>
</feed>
