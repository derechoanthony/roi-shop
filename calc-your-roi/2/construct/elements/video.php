<?php
	$videoKey = array_keys(array_column($_SESSION['roiVideos'], 'video_id'),$id);
	$video = $_SESSION['roiVideos'][$videoKey[0]];
?>

<div class="player">
	<a class="popup-iframe" href="<?= $video['source'] ?>"></a>
	<iframe width="425" height="239" style="margin-left: 5px;" src="<?= $video['source'] ?>" frameborder="0" allowfullscreen></iframe>
</div>