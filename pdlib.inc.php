<?php

/**
 * This is a library with some functions, most of all are just for displaying data
 *
 * PHP versions 4 and 5
 *
 * @copyright		Copyright (c) 2007, Dieter Plaetinck
 * @link			http://dieter.plaetinck.be/php_delicious_client
 * @version			v0.5
 * @license			GPL v2. See the LICENSE file
 */


function input($name = false, $convertToLowerCase = true, $default = false, $yesNo = false){
	//no difference between strings and numbers.. don't think i'll need that
	if($name){
		if($default)	echo ("$name [ $default ] : ");
		else			echo ("$name : ");
	}
	else{
		if($default) echo ("> [ $default ] ");
		else			echo ("> ");
	}
	$result = trim(fgets(STDIN,256));
	if(!$result) $result = $default;
	if($convertToLowerCase)	$result = strtolower($result);
	if($yesNo){
		if($result=='y' || $result == 'Y') $result = true;
		else $result = false;
	}
	return $result;
}

function debug($str, $level = 5){
	global $debug;
	if($debug >= $level){
		echo " ** ";
		if($debug >=5) echo "debug [$level] : ";
		if(is_array($str)){
			echo "\n";
			print_r($str);
		}
		else echo $str."\n";
	}
}

function showPosts($posts = null, $sUrl = true, $sDesc = true, $sNotes = true, $sUpdated = true, $sTags = true, $sHash = false, $sTotal = true){
	//TODO: there is also a field 'count' in a post-array.. where would this be for.. ?
	if($posts && is_array($posts)){
		foreach($posts as $post){
			showPost($post, $sUrl, $sDesc, $sNotes, $sUpdated, $sTags, $sHash);
		}
		if($sTotal) echo " ** total : ".sizeof($posts)."\n";
	}
	else{
		debug("showPosts didn't receive posts in proper format",1);
		debug($posts,2);
	}
}

function showPost($post = null, $sUrl = true, $sDesc = true, $sNotes = true, $sUpdated = true, $sTags = true, $sHash = false){
	debug($post,6);
	if($post && is_array($post)){
		if($sUrl)		echo 'url:     '.$post['url']."\n";
		if($sDesc)		echo 'desc:    '.$post['desc']."\n";
		if($sNotes)		echo 'notes:   '.$post['notes']."\n";
		if($sHash)		echo 'hash:    '.$post['hash']."\n";
		if($sUpdated)	echo 'updated: '.$post['updated']."\n";
		if($sTags)		echo 'tags:   '; showTagsAsString($post['tags']);
	}
	else{
		debug("showPost didn't receive post in proper format",1);
		debug($post,2);
	}
}

function showTags($tags = null, $sCount, $sPosts = false, $sEditor = false, $ll = 0, $ul = 0, $hl = 0, $sTotal = false){
	$totalFound = 0;
	$totalWent = 0;
	$goOn = true;
	if($tags && is_array($tags)){
		foreach($tags as $tag){
			if( (!$hl || $hl > $totalFound) && (!$ll || $tag['count'] >= $ll) && (!$ul || $tag['count'] <= $ul) ){
				if($goOn){
					$goOn = showTag($tag, $sCount, $sPosts, $sEditor);
					$totalWent++;
				}
				$totalFound++;
			}
		}
		if($sTotal) echo " ** Total found: $totalFound .  Total went through: $totalWent \n";
	}
	else{
		debug("showTags didn't receive tags in proper format",1);
		debug($tags,2);
	}
}

function showTagsAsString($tags = null, $sTotal = false){
	$total= 0;
	if($tags && is_array($tags)){
		foreach($tags as $tag){
			echo ' '.$tag;
			$total++;
		}
		echo "\n";
		if($sTotal) echo " ** Total: $total \n";
	}
	else{
		debug("showTagsAsString didn't receive tags in proper format",1);
		debug($tags,2);
	}
}

function showTag($tag = null, $sCount = true, $sPosts = false, $sEditor = false){
	global $pd;
	debug($tag,6);
	$output = false;
	if($tag && is_array($tag)){
		$output = true;
		echo ' '.$tag['tag'];
		if($sCount){
			if(!isset($tag['count'])){
				$tag = $pd->getTag($tag['tag']);
				debug('showTag didnt get array with count in it, i shouldnt fetch this myself, please report',1);
			}
			echo ' ( '.$tag['count']." )\n";
		}
		if($sPosts){
			if(isset($tag['posts'])){
				if(is_array($tag['posts']) && !empty($tag['posts'])){
					echo " ** associated posts: \n";
					showPosts($tag['posts'], true, true, false, false, true, false, true);
				}
				else debug('Hmmm... Associated posts must be shown but none given.  Don\'t want to overload delicious.  Report this bug',1);
			}
			else debug("showTag didn't associated post (in proper format) for tag",1);
		}
		if($sEditor) $output = tagEditor($tag);
	}
	else{
		debug("showTag didn't receive tag in proper format",1);
		debug($tag,2);
	}
	return $output;
}

function tagEditor($tag = null){
	global $pd;
	if($tag && is_array($tag)){
		echo ("[D]elete, [R]ename, [S]kip tag or e[X]it to main shell?\n");
		$do = input();
		if($do=='d'){
			$s = $pd->DeleteTag($tag['tag']);
			echo 'Deletion ';
			if($s) echo 'success';
			else echo 'failed';
			echo "\n";
		}
		if($do =='r'){
			debug('You can enter multiple tags, separated by spaces',1);
			$new = input('New name',false,$tag['tag']);
			if($new && $new != $tag['tag']){
				$success = $pd->RenameTag($tag['tag'],$new);
				echo 'Renaming ';
				if($success) echo 'success';
				else echo 'failed';
			}
			else echo "Tag untouched";
			echo "\n";
		}
		if($do =='x'){
			return false;
		}
	}
	else{
		debug("tagEditor didn't receive tag in proper format",1);
		debug($tag,2);
	}
	return true;
}

?>