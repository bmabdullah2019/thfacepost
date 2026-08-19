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
class Relationship extends OssnObject {
		public function addRelation($friend_guid = 0, $type = "", $since = 0, $privacy = OSSN_PUBLIC){
					if($this->getRequstsTo($friend_guid)){
							return true;	
					}
					if(!empty($type)){
							$this->type               = 'user';
							$this->subtype    		  = 'family:relationship';
							$this->owner_guid         = ossn_loggedin_user()->guid;
							
							$this->data->privacy_from = $privacy;
							$this->data->request_type = $type;
							$this->data->pending	  = true;
							$this->data->member_guid  = $friend_guid;
						    $this->data->pending 	  = true;
						
							if(empty($friend_guid)){
									$this->data->pending = false;
							} else {
								$this->data->privacy_to	  = OSSN_FRIENDS;
								$this->data->since	      = $since;
							}
							if($guid = $this->addObject()){
									if(class_exists('OssnNotifications') && !empty($friend_guid)){
										$notification = new OssnNotifications;
										$notification->add('family:relation:add', ossn_loggedin_user()->guid, $guid, $guid, $friend_guid);
									}
									ossn_trigger_callback('family:relationship', 'add', array(
												'guid' => $guid,												
									));
									return true;
							}
					}
					return false;
		}
		public function getType(){
			if(isset($this->request_type)){
				$locale = str_replace(' ', '', $this->request_type);
				return ossn_print('family:relationship:btype:'.strtolower($locale));		
			}
		}
		public function getRequstsTo($friend_guid = '', array $params = array()){
						$guid = ossn_loggedin_user()->guid;
						if(!empty($friend_guid)){
							$guid = $friend_guid;	
						}
						$default = array(
								'type' => 'user',
								'subtype' => 'family:relationship',
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
		public function getStatus($guid = false, array $params = array()){
						if(empty($guid)){
							$guid = ossn_loggedin_user()->guid;
						}
						$default = array(
								'type' => 'user',
								'subtype' => 'family:relationship',
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