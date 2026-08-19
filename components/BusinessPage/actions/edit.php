<?php
 $name = input('name');
 $description = input('description');
 $category = input('category');
 $guid = input('guid');
 
 if(empty($name) || empty($description) || empty($category)){
		ossn_trigger_message(ossn_print('bpage:fileds:required'), 'error');
		redirect(REF);
 }
 $vars = array(
		'name' => $name,
		'description' => $description,
		'phone' => input('phone'),
		'address' => input('address'),
		'website' => input('website'),
		'email' => input('email'),
		'category' => $category,
 );
 if(!filter_var($vars['email'], FILTER_VALIDATE_EMAIL)){
		ossn_trigger_message(ossn_print('email:invalid'), 'error');
		redirect(REF);
 }
 if(!bpage_validate_url($vars['website'])){
		ossn_trigger_message(ossn_print('bpage:website:invalid'), 'error');
		redirect(REF);	 
 }
 $page = new \Ossn\Component\BusinessPage\Page();
 if($page->editPage($guid, $vars)){
		ossn_trigger_message(ossn_print('bpage:edited'));
		redirect("page/view/{$guid}");
 }
 ossn_trigger_message(ossn_print('bpage:edit:failed'), 'error');
 redirect(REF); 