<?php
class ModelUserChangePwd extends Model {
	public function changePassword($userID,$data) {
		//$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['newpwd'])))) . "' WHERE user_id='". (int)$userID ."'");
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['newpwd'])))) . "' WHERE user_id = '" . (int)$userID . "'");
	}	
}
?>