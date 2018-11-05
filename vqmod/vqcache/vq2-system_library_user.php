<?php
class User {
	private $user_id;
	private $username;
	private $permission = array();

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['user_group_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = unserialize($user_group_query->row['permission']);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

		public function get_timestamp() {
		return floor(microtime(true)/30);
	}

	public function base32_decode($b32) {

		$lut = array("A" => 0, "B" => 1, "C" => 2, "D" => 3, "E" => 4, "F" => 5, "G" => 6, "H" => 7, "I" => 8, "J" => 9, "K" => 10, "L" => 11, "M" => 12, "N" => 13, "O" => 14, "P" => 15, "Q" => 16, "R" => 17, "S" => 18, "T" => 19, "U" => 20, "V" => 21, "W" => 22, "X" => 23, "Y" => 24, "Z" => 25, "2" => 26, "3" => 27, "4" => 28, "5" => 29, "6" => 30, "7" => 31);
	
		$b32 	= strtoupper($b32);

		if (!preg_match('/^[ABCDEFGHIJKLMNOPQRSTUVWXYZ234567]+$/', $b32, $match))
			throw new Exception('Invalid characters in the string.');

		$l 	= strlen($b32);
		$n	= 0;
		$j	= 0;
		$binary = "";

		for ($i = 0; $i < $l; $i++) {

			$n = $n << 5;
			$n = $n + $lut[$b32[$i]];
			$j = $j + 5;

			if ($j >= 8) {
				$j = $j - 8;
				$binary .= chr(($n & (0xFF << $j)) >> $j);
			}
		}

		return $binary;
	}

	public function oath_hotp($key, $counter)
	{
	    if (strlen($key) < 8)
		throw new Exception('Secret key should be at least 16 characters');

	    $bin_counter = pack('N*', 0) . pack('N*', $counter);
	    $hash 	 = hash_hmac ('sha1', $bin_counter, $key, true);

	    return str_pad($this->oath_truncate($hash), 6, '0', STR_PAD_LEFT);
	}

	public function verify_key($b32seed, $key) {

		$timeStamp = $this->get_timestamp();

		$binarySeed = $this->base32_decode($b32seed);
		if ($this->oath_hotp($binarySeed, $timeStamp) == $key){
			return true;
		}else{
			return false;
		}

	}

	public function oath_truncate($hash)
	{
	    $offset = ord($hash[19]) & 0xf;

	    return (
	        ((ord($hash[$offset+0]) & 0x7f) << 24 ) |
	        ((ord($hash[$offset+1]) & 0xff) << 16 ) |
	        ((ord($hash[$offset+2]) & 0xff) << 8 ) |
	        (ord($hash[$offset+3]) & 0xff)
	    ) % pow(10, 6);
	}

	public function login($username, $password, $uksb_2fa) {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		if ($user_query->num_rows) {
			if($user_query->row['uksb_2fa_secret']){
				$secretey = $user_query->row['uksb_2fa_secret'];
				
				if($this->verify_key($secretey, $uksb_2fa)){
					$this->session->data['user_id'] = $user_query->row['user_id'];
		
					$this->user_id = $user_query->row['user_id'];
					$this->username = $user_query->row['username'];			
		
					$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
		
					$permissions = unserialize($user_group_query->row['permission']);
		
					if (is_array($permissions)) {
						foreach ($permissions as $key => $value) {
							$this->permission[$key] = $value;
						}
					}
		
					return true;
				}else{
					return false;
				}			
			}else{
				$this->session->data['user_id'] = $user_query->row['user_id'];
	
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];			
	
				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
	
				$permissions = unserialize($user_group_query->row['permission']);
	
				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
	
				return true;
			}
			

			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];

			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = unserialize($user_group_query->row['permission']);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';
	}

	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}
	
	public function getGroupId() {
		return $this->user_group_id;
	}	
}