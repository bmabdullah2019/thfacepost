<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team https://www.openteknik.com/contact
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.opensource-socialnetwork.org/
 */
 
namespace Ossn\Component\BusinessPage; 
class Likes extends \OssnLikes {
    /**
     * Like a page
     *
     * @params integer $subject_id Id of item which users liked
     * @params integer $guid Guid of user
	 * @params string  $type Subject type
     *
     * @return boolean
     */
    public function Like($subject_id = '', $guid = '', $type = '', $reaction_type = 'like') {
       	return parent::Like($subject_id, $guid, "business:page", $reaction_type);
    }
    /**
     * Unlike a page
     *
     * @params integer $subject_id ID of item which users liked
     * @params integer $guid Guid of user
	 * @params string  $type Subject type
     *
     * @return boolean
     */
    public function Unlike($subject_id = '', $guid = '', $type = '') {
       	return parent::UnLike($subject_id, $guid, "business:page");
    }	
    /**
     * Check if user disliked item or not
     *
     * @params integer $subject_id Id of item which users liked
     * @params integer $guid Guid of user
	 * @params string  $type Subject type
     *
     * @return boolean
     */
    public function isLiked($subject_id = '', $guid = '', $type = '') {
       return parent::isLiked($subject_id, $guid, "business:page");
    }	
    /**
     * Get likes
     *
     * @params integer $subject_id Id of item which users liked
	 * @params string  $type Subject type
     *
     * @return object
     */
    public function GetLikes($subject_id = '', $type = '', array $wheres = array()) {
			return parent::GetLikes($subject_id, "business:page", $wheres);
    }
    /**
     * Count likes
     *
     * @params integer $subject_id Id of item which users liked
	 * @params string  $type Subject type
     *
     * @return integer;
     */
    public function CountLikes($subject_id = '', $type = '') {
         return parent::CountLikes($subject_id, "business:page");
    }	
    /**
     * Delte subject likes
     *
     * @params integer $subject_id Id of item which users liked
	 * @params string  $type Subject type
     *
     * @return bool;
     */
	  public function deleteLikes($subject_id = '', $type = '') {
		 return parent::deleteLikes($subject_id, "business:page"); 
	  }
}//class
