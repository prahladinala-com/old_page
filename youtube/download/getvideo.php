<?php
// YouTube Downloader PHP
// based on youtube-dl in Python http://rg3.github.com/youtube-dl/
// by Ricardo Garcia Gonzalez and others (details at url above)
//
// Takes a VideoID and outputs a list of formats in which the video can be
// downloaded

include_once('common.php');
ob_start();// if not, some servers will show this php warning: header is already set in line 46...

if( ! isset($_GET['videoid']) )
{
	echo '<p>No video id passed in</p>';
	exit;
}

$my_id = $_GET['videoid'];

if( \YoutubeDownloader\YoutubeDownloader::isMobileUrl($my_id) )
{
	$my_id = \YoutubeDownloader\YoutubeDownloader::treatMobileUrl($my_id);
}

$my_id = \YoutubeDownloader\YoutubeDownloader::validateVideoId($my_id);

if ( $my_id === null )
{
    echo '<p>Invalid url</p>';
    exit;
}

if (isset($_GET['type']))
{
	$my_type = $_GET['type'];
}
else
{
	$my_type = 'redirect';
}

if ($my_type == 'Download')
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Youtube Downloader</title>
	<meta name="keywords"
		  content="Video downloader, download youtube, video download, youtube video, youtube downloader, download youtube FLV, download youtube MP4, download youtube 3GP, php video downloader"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</head>
<body>
    
<?php
} // end of if for type=Download

/* First get the video info page for this video id */
// $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
// thanks to amit kumar @ bloggertale.com for sharing the fix
$video_info_url = 'http://www.youtube.com/get_video_info?&video_id=' . $my_id . '&asv=3&el=detailpage&hl=en_US';
$video_info_string = \YoutubeDownloader\YoutubeDownloader::curlGet($video_info_url, $config);

/* TODO: Check return from curl for status code */
$video_info = \YoutubeDownloader\VideoInfo::createFromString($video_info_string);

if ($video_info->getStatus() == 'fail')
{
	echo '<p>Error in video ID: ' . $video_info->getErrorReason() . '</p>';

	if ($config->get('debug'))
	{
		echo '<pre>';
		var_dump($video_info);
		echo '</pre>';
	}
	exit();
}

echo '<div id="info">';

echo '<div style="background-color: #FFFFE2; font-size: 15px; border: 1px solid #ece89c; padding: 5px; line-height: 100%;">
<font color="#D90707">Tips:Download File Not Open???</font>
<br>
<font color="#6E6E6E">
Rename Downloaded file from "videoplayback" to "video_name.mp4/3gp/webm"
<br>
For example: if you download "360p MP4" video then rename
<font color="#010201">"videoplayback"</font>
to
<font color="#020101">"video_name.mp4"</font>
after the download or before download.
</font>
</div>';


echo '</div>';

$my_title = $video_info->getTitle();
$cleanedtitle = $video_info->getCleanedTitle();

if ( $video_info->getStreamMapString() === null )
{
	echo '<p>No encoded format stream found.</p>';
	echo '<p>Here is what we got from YouTube:</p>';
	echo '<pre>';
	var_dump($video_info_string);
	echo '</pre>';
}

$stream_map = \YoutubeDownloader\StreamMap::createFromVideoInfo($video_info);

if ($config->get('debug'))
{
	if ($config->get('multipleIPs') === true)
	{
		echo '<pre>Outgoing IP: ';
		print_r(\YoutubeDownloader\StreamMap::getRandomIp($config));
		echo '</pre>';
	}

	echo '<pre>';
	var_dump($stream_map);
	echo '</pre>';
}

if (count($stream_map->getStreams()) == 0)
{
	echo '<p>No format stream map found - was the video id correct?</p>';
	exit;
}

/* create an array of available download formats */
$avail_formats = $stream_map->getStreams();

if ($config->get('debug'))
{
	echo '<p>These links will expire at ' . $avail_formats[0]['expires'] . '</p>';
	echo '<p>The server was at IP address ' . $avail_formats[0]['ip'] . ' which is an ' . $avail_formats[0]['ipbits'] . ' bit IP address. ';
	echo 'Note that when 8 bit IP addresses are used, the download links may fail.</p>';
}

if ($my_type == 'Download')
{
	echo '<p align="center">List of available formats for download:</p>
		';

	/* now that we have the array, print the options */
	foreach ($avail_formats as $avail_format)
	{
		echo '<div class="itemlist">';

		if ($config->get('VideoLinkMode') == 'direct' || $config->get('VideoLinkMode') == 'both')
		{
			$directlink = $avail_format['url'];
			// $directlink = explode('.googlevideo.com/', $avail_format['url']);
			// $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '&ratebypass=yes&gcr=sg';
				echo '<a href="' . $directlink . '&title=(Sodmate.com)' . $cleanedtitle . '" class="mime">Download ';
			echo '(quality: ' . $avail_format['type'] . '-' . $avail_format['quality'];
		}
		else
		{
			echo ' ';
			echo '(quality: ' . $avail_format['quality'];
		}

		
		$size = \YoutubeDownloader\YoutubeDownloader::get_size($avail_format['url'], $config);

		echo ') </a>' .
			'<small><span class="size">' . \YoutubeDownloader\YoutubeDownloader::formatBytes($size) . '</span></small>' .
			'</div>';
	}
	if($config->get('MP3Enable'))
	{
		echo '</ul><p align="center">Convert and Download to .mp3</p><ul>';
		printf('<li><strong><a href="download.php?mime=audio/mp3&token=%s&title=%s&getmp3=true" class="mime">audio/mp3</a> (quality: %s)</strong></li>',
			base64_encode($my_id), $cleanedtitle, $config->get('MP3Quality'));
	}
	echo '<p align="center">Separated video and audio format:</p>';

	foreach ($stream_map->getFormats() as $avail_format)
	{
		echo '<div class="itemlist">';

		if ($config->get('VideoLinkMode') == 'direct' || $config->get('VideoLinkMode') == 'both')
		{
			$directlink = $avail_format['url'];
			// $directlink = explode('.googlevideo.com/', $avail_format['url']);
			// $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '&ratebypass=yes&gcr=sg';
			echo '<a href="' . $directlink . '&title=(Sodmate.com)' . $cleanedtitle . '" class="mime">Download ';
			echo '(quality: ' . $avail_format['type'] . '-' . $avail_format['quality'];
		}
		else
		{
			echo ' ';
			echo '(quality: ' . $avail_format['quality'];
		}

		
		$size = \YoutubeDownloader\YoutubeDownloader::get_size($avail_format['url'], $config);

		echo ') </a>' .
			'<small><span class="size">' . \YoutubeDownloader\YoutubeDownloader::formatBytes($size) . '</span></small>' .
			'</div>';
	}

	echo '</ul>';

	echo '<small>Note that you initiate download either by clicking video format link or click "download" to use this server as proxy.</small>';

 echo '
<!-- BEGIN: Powered by Supercounters.com -->
<center><script type="text/javascript" src="http://widget.supercounters.com/online_t.js"></script><script type="text/javascript">sc_online_t(1373567,"Now Online","170ddb");</script><br><noscript><a href="http://www.supercounters.com/">Free Users Online Counter</a></noscript>
</center>
<!-- END: Powered by Supercounters.com -->

<footer><a href="#" rel="nofollow"> Copyright &copy; 2016-2017 Sodmate.Com All rights reserved.</a></footer>';

	echo '
</body>
</html>';
}
else
{
	/* In this else, the request didn't come from a form but from something else
	 * like an RSS feed.
	 * As a result, we just want to return the best format, which depends on what
	 * the user provided in the url.
	 * If they provided "format=best" we just use the largest.
	 * If they provided "format=free" we provide the best non-flash version
	 * If they provided "format=ipad" we pull the best MP4 version
	 *
	 * Thanks to the python based youtube-dl for info on the formats
	 *   							http://rg3.github.com/youtube-dl/
	 */

	$redirect_url = \YoutubeDownloader\YoutubeDownloader::getDownloadUrlByFormats($avail_formats, $_GET['format']);

	if ( $redirect_url !== null )
	{
		header("Location: $redirect_url");
	}

} // end of else for type not being Download
