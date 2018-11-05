<?php
class Api {
	public $username;
	public $token;
	public $api_id;
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
	
	}
	public function checkApiKey($api_key){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `key` ='" . $this->db->escape($api_key) . "'AND `status` = '1'");
	
		if(!empty($query->row)){
			return true;
		}else{
			return false;
		}
	}
	public function login($username, $password) {
		// $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "api WHERE username = '" . $this->db->escape($username) . "' AND  password = '" . $this->db->escape(md5($password)) . "' AND status = '1'");
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "api WHERE username = '" . $this->db->escape($username) . "' AND  password = '" . $this->db->escape($password) . "' AND status = '1'");
		// var_dump($user_query); die('sa');
		if ($user_query->num_rows) {
	

			$this->api_id = $user_query->row['api_id'];
			$this->username = $user_query->row['username'];

			return true;
		} else {
			return false;
		}
	}
	public function getToken($username){
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "session WHERE username = '" . $this->db->escape($username) . "'  AND status = '1'");

		if ($user_query->num_rows) {
	

			$this->token = $user_query['token'];

			return $this->token;
		} else {
			return '';
		}
	}
	public function getByToken($token){
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "session WHERE token = '" . $this->db->escape($token) . "' ");

		if ($user_query->num_rows) {
			return $user_query;
		} else {
			return '';
		}
	}
	
	public function getByTokenByApiId($api_id){
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "session WHERE  api_id ='".(int)$api_id."'");
		
		if ($user_query->num_rows) {
			return $user_query;
		} else {
			return '';
		}
	}
	public function createToken($username, $api_id, $token){
		$this->db->query("INSERT INTO " . DB_PREFIX . "session 
				SET username = '" . $username . "', 
				api_id ='" . $api_id . "',
				token = '" . $this->db->escape($token) . "', date_added = NOW()");
	}
	
	public function deleteToken($api_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE api_id = '" . (int)$api_id . "'");
	}

}