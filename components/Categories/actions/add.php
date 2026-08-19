<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
  $title = input('title');
  $description = input('description');
  
  $category = new Categories;
  $search = $category->searchObject(array(
					'type' => 'site',
					'subtype' => 'user:category',
					'wheres' => "o.title = '{$title}'",
			));
  if($search){
	  ossn_trigger_message(ossn_print('categories:categry:exists'), 'error');
	  redirect(REF);	  	  
  }
  if(!ctype_alpha($title)){
	  ossn_trigger_message(ossn_print('categories:alphabets:only'), 'error');
	  redirect(REF);	  
  }
  if($category->addCategory($title, $description)){
	  ossn_trigger_message(ossn_print('categories:categry:added'));
	  redirect("administrator/component/Categories");
  } else {
	  ossn_trigger_message(ossn_print('categories:categry:add:failed'), 'error');
	  redirect(REF);	  
  }