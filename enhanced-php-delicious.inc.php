<?php

/**
 * This is a class that builds upon PhpDelicious to add some features
 *
 * PHP versions 4 and 5
 *
 * @copyright		Copyright (c) 2007, Dieter Plaetinck
 * @link			http://dieter.plaetinck.be/php_delicious_client
 * @version			v0.5
 * @license			GPL v2. See the LICENSE file
 */

class EnhancedPhpDelicious extends PhpDelicious{
	/************************ public methods - do call! ************************/

	/*
	 * The del.icio.us api does not support fetching tags along with their associated posts.
	 * To achieve that - without triggering a GetPosts query for each single tag - we override
	 * the GetAllTags function from Ed's class and add such a functionality
	 * by fetching all posts (along with their tags) and re-ordering the results to the format we want
	 * so we only need 1 request to achieve our goal.
	 */

	function GetAllTags($associatedPosts = false) {
		if(!$associatedPosts) return parent::GetAllTags();
		else{
			$posts = $this->GetAllPosts();
			$tags = array();
			foreach($posts as $post){
				foreach($post['tags'] as $tagName){
					$found = false;
					foreach($tags as &$tag){
						if($tag['tag'] == $tagName){
							$found = true;
							$tag['count']++;
							$tag['posts'][] = $post;
						}
					}
					if(!$found) $tags[] = array('tag'=>$tagName,'count'=>1,'posts'=> array(0=>$post));
				}
			}
			return $tags;
		}
	}

	/*
	 * Another thing the del.icio.us api does not (yet?) support is getting info about about 1 single tag.
	 * (basically just the count of occurences of that tag)
	 * To do that, we just grab the list of all tags (with the counts) and return only the needed one.
	 */

	 function getTag($tagName, $associatedPosts = false) {
		$tags = $this->GetAllTags($associatedPosts);
		foreach($tags as $tag)
		{
			if($tag['tag'] == $tagName) return $tag;
		}
		return false;
	 }

	 /*
	  * Deleting of tags is currently not yet implemented in the PhpDelicious lib
	  * (probably because it wasn't documented on the del.icio.us website)
	  * It will probably be included in the new version but until then...
	  */

      function DeleteTag($sName) {
         if ($aResult = $this->DeliciousRequest('tags/delete', array('tag' => $sName))) {
            if ($aResult['content'] == 'done') return true;
         }
         return false;
      }
}
?>