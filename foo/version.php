<?php 
header('Content-type: text/xml');


$content=file_get_contents("http://news.google.com/news?ned=us&topic=h&output=rss");
$x = new SimpleXmlElement($content);

echo "<ul>";
 
foreach($x->channel->item as $entry) {
    echo "<li>$entry->title</li>";
}
echo "</ul>";
?>

