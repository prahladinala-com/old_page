<?php
include 'includes/func.php';
include 'includes/info.php';
$yf=ngegrab('https://www.googleapis.com/youtube/v3/videos?key='.$devkey.'&part=snippet,contentDetails,statistics,topicDetails&id='.$_GET['id'].'');
$yf=json_decode($yf);

// WapTube API here.


$mdlink = file_get_contents('http://sodmate.com/download/getvideo.php?videoid='.$_GET['id'].'&type=Download'); 



if($yf){
foreach ($yf->items as $item)
{

$name=$item->snippet->title;
$des = $item->snippet->description;
$date = dateyt($item->snippet->publishedAt);
$channelId = $item->snippet->channelId;
$chtitle = $item->snippet->channelTitle;
$ctd=$item->contentDetails;
$duration=format_time($ctd->duration);
$hd = $ctd->definition;
$st= $item->statistics;
$views = $st->viewCount;
$likes = $st->likeCount;
$dislike = $st->dislikeCount;
$favoriteCount = $st->favoriteCount;
$commentCount = $st->commentCount;
{$title='Download '.$name.' ('.$duration.') in 3GP, MP4, FLV and WEBM Format';}
$tag=$name;
$tag=str_replace(" ",",", $tag);
$dtag=$des;
include 'includes/config.php';
echo '<h3> Download '.$name.'</h3>';


echo ''.$mdlink.'';


echo '';
}
}

?>