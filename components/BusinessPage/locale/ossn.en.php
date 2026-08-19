<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
$en = array(
		'bpage' => 'Business Page',
		'bpage:cover:err1:detail' => 'The cover picture must be at least %s x %s or greater.',
		'bpage:name' => 'Page Name',
		'bpage:desc' => 'Description',
		'bpage:phone' => 'Phone',
		'bpage:website' => 'Website',
		'bpage:address' => 'Address',
		'bpage:type' => 'Page type',
		'bpage:change:photo' => 'Change Photo',
		'bpage:reposition' => 'Reposition',
		'bpage:save:position' => 'Save position',
		'bpage:change:cover' => 'Change Cover',
		
		'bpage:cat:business' => 'Business',
		'bpage:cat:brand' => 'Brand',
		'bpage:cat:product' => 'Product',
		'bpage:cat:artist' => 'Artist',
		'bpage:cat:public:figure' => 'Public Figure',
		'bpage:cat:entertainment' => 'Entertainment',
		'bpage:cat:cause' => 'Cause',
		'bpage:cat:community' => 'Community',
		'bpage:cat:org' => 'Organization',
		
		'bpage:edited' => 'Page has been edited',
		'bpage:edit:failed' => 'Page can not be edited',
		'bpage:deleted' => 'Page has been deleted',
		'bpage:delete:failed' => 'Page can not be deleted',
		
		'bpage:edit' => 'Edit page',
		'bpage:delete' => 'Delete page',
		
		'bpage:list' => 'All Pages',
		'bpage:add' => 'Create new page',
		
		'bpage:cover:upload:error' => 'Can not add cover',
		'ossn:notifications:comments:post:businesspage:wall' => '%s commented on your page post',
		'ossn:notifications:like:post:businesspage:wall' =>  '%s liked your page post',
		
		'bpage:fileds:required' => "Make sure you entered name, description and category",
		'bpage:added' => 'Page has been created',
		'bpage:likedpages' => 'Liked Pages',
		'bpage:mypages' => 'My Pages',	
		'bpage:likes' => '%s likes',
		'bpage:about' => 'About',
		'bpage:delete:warning' => 'Deleting it will permanently remove all of its content, including any associated posts, comments, media, and settings. This action cannot be undone and the data cannot be recovered once deleted.',
		'bpage:ownership' => 'Ownership Change!',
		'bpage:ownership:warning' => 'You can transfer ownership of your page to another user, but the user must be your friend. Once the ownership is transferred, it cannot be reversed unless your friend transfers it back to you.',
		'bpage:select:friends' => 'Select Friend',
		'bpage:website:invalid' => 'Invalid website URL. It should look like: https://yourwebsite.com',
		'bpage:username:note' => "Choose your username wisely! You'll only be able to set it once, and it can't be changed later. Once you've chosen, you can always visit your page at: %s",
);
ossn_register_languages('en', $en);
