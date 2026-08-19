<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0  https://www.openteknik.com/license/
 * @link      https://www.openteknik.com/
 */
 class Report extends OssnObject {
		public function addReport($guid = '', $reason = '', $type = ''){
				if(empty($guid) || empty($type)){
					return false;	
				}
				$this->title 		= "";
				$this->type  		= 'site';
				$this->subtype 		= 'report';
				$this->owner_guid  	= 1;
				$this->description = $reason;
				
				$this->data->container_guid = $guid;
				$this->data->container_type = $type;
				$this->data->reported_by 	= ossn_loggedin_user()->guid;
				
				if($type == 'message'){
					$message = ossn_get_message($this->data->container_guid);
				}
				if($guid = $this->addObject()){
						//keep original message intact
						if($type == 'message'){
							$storage = ossn_get_userdata("object/{$guid}/report/");
							$storage_path = $storage . 'report.json';
							if(!is_dir($storage)){
									mkdir($storage, 0755, true);	
							}
							$from	 = ossn_user_by_guid($message->message_from);
							$data	 = json_encode(array(
									'message' => $message->message,
									'from' => array(
											'name' => $from->fullname,
											'username' => $from->username,
											'profile' => $from->profileURL(),
									),
									'to' => array(
											'name' => ossn_loggedin_user()->fullname,
											'username' => ossn_loggedin_user()->username,
											'profile' => ossn_loggedin_user()->profileURL(),												  
									),
							));
							file_put_contents($storage_path, $data);
						}						
						return $guid;
				}
				return false;
		}
		public function getAll(array $params = array()) {
				$default             = array();
				$default['type']     = 'site';
				$default['subtype']  = 'report';
				$default['order_by'] = 'o.guid DESC';
				
				$vars = array_merge($default, $params);
				return $this->searchObject($vars);
		}
 }