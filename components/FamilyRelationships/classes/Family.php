<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OPENTEKNIK LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE  https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
class Family extends OssnObject {
		public function addMember($friend_guid, $type, $privacy = OSSN_PUBLIC){
					if(!empty($friend_guid) && !empty($type)){
							$this->type               = 'user';
							$this->subtype    		  = 'family:member';
							$this->owner_guid         = ossn_loggedin_user()->guid;
							
							$this->data->member_guid  = $friend_guid;
							$this->data->privacy_from = $privacy;
							$this->data->request_type = $type;
							$this->data->privacy_to	  = OSSN_FRIENDS;
							$this->data->pending	  = true;
							if($guid = $this->addObject()){
									if(class_exists('OssnNotifications') && !empty($friend_guid)){
										$notification = new OssnNotifications;
										$notification->add('family:member:add', ossn_loggedin_user()->guid, $guid, $guid, $friend_guid);
									}								
									ossn_trigger_callback('family', 'member:add', array(
												'guid' => $guid,												
									));
									return true;
							}
					}
					return false;
		}
		public function getType(){
			if(isset($this->request_type) && $this->member_guid == $this->user_profile->guid){
						$user = ossn_user_by_guid($this->owner_guid);
						switch($this->request_type){
								case 'Mother':
								case 'Father':
										if(isset($this->user_profile->gender) && $user->gender == 'male'){
											return ossn_print('family:relationship:type:son');
										}
										if(isset($this->user_profile->gender) && $user->gender == 'female'){
											return ossn_print('family:relationship:type:daughter');
										}
										return ossn_print('family:relationship:type:children');
									break;
								case 'Grandmother':
								case 'Grandfather':
										if(isset($this->user_profile->gender) && $user->gender == 'male'){
											return ossn_print('family:relationship:type:grandson');
										}
										if(isset($this->user_profile->gender) && $user->gender == 'female'){
											return ossn_print('family:relationship:type:granddaughter');
										}
									break;									
								case 'Brother':
								case 'Sister':
										if(isset($this->user_profile->gender) && $user->gender == 'male'){
											return ossn_print('family:relationship:type:brother');
										}
										if(isset($this->user_profile->gender) && $user->gender == 'female'){
											return ossn_print('family:relationship:type:sister');
										}
								break;
								case 'Auntie':
								case 'Uncle':
										if(isset($this->user_profile->gender) && $user->gender == 'male'){
											return ossn_print('family:relationship:type:nephew');
										}
										if(isset($this->user_profile->gender) && $user->gender == 'female'){
											return ossn_print('family:relationship:type:niece');
										}
								break;								
								case 'Nephew':
								case 'Niece':
										if(isset($this->user_profile->gender) && $user->gender == 'male'){
											return ossn_print('family:relationship:type:uncle');
										}
										if(isset($this->user_profile->gender) && $user->gender == 'female'){
											return ossn_print('family:relationship:type:auntie');
										}
								break;										
						}
			}
			if(isset($this->request_type)){
				$locale = str_replace(' ', '', $this->request_type);
				return ossn_print('family:relationship:type:'.strtolower($locale));		
			}
		}
		public function getRequstsTo(array $params = array()){
						$guid = ossn_loggedin_user()->guid;
						$default = array(
								'type' => 'user',
								'subtype' => 'family:member',
								'page_limit' => false,
								'entities_pairs' => array(
												array(
												  	'name' => 'pending',
													'value' => true,
												  ),
												array(
												  	'name' => 'member_guid',
													'value' => $guid,
 											  	)
								)
						);
						
						$options = array_merge($default, $params);
						return $this->searchObject($options);			
		}
		public function getMembers($friend_guid = '', array $params = array()){
						$guid = ossn_loggedin_user()->guid;
						if(!empty($friend_guid)){
							$guid = $friend_guid;	
						}
						$default = array(
								'type' => 'user',
								'subtype' => 'family:member',
								'page_limit' => false,
								'entities_pairs' => array(									
												array(
												  	'name' => 'member_guid',
													'value' => true,
													'wheres' => 'emd0.value IS NOT NULL',
 											  	),
												array(
												  	'name' => 'pending',
													'value' => true,
													'wheres' => 'emd1.value IS NOT NULL',
												  ),														
								),								
								'wheres' => array("((o.owner_guid={$guid}) OR (emd0.value={$guid}) AND emd1.value='')"),
						);
						$options = array_merge($default, $params);
						return $this->searchObject($options);			
		}
}