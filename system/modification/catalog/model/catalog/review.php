<?php
class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_review_mail')) {

			$this->load->language('mail/review');
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_product'), $this->db->escape(strip_tags($product_info['name']))) . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), $this->db->escape(strip_tags($data['name']))) . "\n";
			$message .= sprintf($this->language->get('text_rating'), $this->db->escape(strip_tags($data['rating']))) . "\n";
			$message .= $this->language->get('text_review') . "\n";
			$message .= $this->db->escape(strip_tags($data['text'])) . "\n\n";

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo(array($this->config->get('config_email')));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}

	public function addReviewProducts($product_id, $data)
	{
		return $this->db->query("INSERT INTO " . DB_PREFIX . "review_product SET
							review_title = '" . $this->db->escape($data['title']) . "',
							your_nickname = '" . $this->db->escape($data['nickname']) . "',
							your_location = '" . $this->db->escape($data['location']) . "',
							product_id = '" . (int)$product_id . "',
							quality_rating = '" . (int)$data['quality'] . "',
							color_rating = '" . (int)$data['color'] . "',
							value_rating = '" . (int)$data['value'] . "',
							review_summary = '" . $this->db->escape($data['summary']) . "',
							date_added = NOW()");
	}

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getReviewsByProductId1($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.id,r.review_title, r.product_id, r.quality_rating, r.color_rating, r.value_rating, r.review_summary, r.your_nickname, r.your_location , p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review_product r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}

	public function getTotalReviewsByProductId1($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review_product r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}

	public function getAllReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT r.review_title, r.product_id, r.quality_rating, r.color_rating, r.value_rating, r.review_summary, r.your_nickname, r.your_location , p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review_product r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}

	public function checkUserLikeReview($data)
	{
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."review_like WHERE user_id = '" . (int)$data['user_id'] . "' AND review_id = '" . (int)$data['review_id'] . "' ");
		$total = count($query->rows);
		if (!empty($total)) {
			return FALSE;
		}else{
			return TRUE;
		}
	}

	public function addLikeReview($data)
	{
		return $this->db->query("INSERT INTO " . DB_PREFIX . "review_like SET
							user_id = '" . (int)$data['user_id'] . "',
							review_id = '" . (int)$data['review_id'] . "',
							type = '" . (int)$data['type'] . "',
							date_added = NOW()");
	}
	public function getLikeReviewByType($review_id, $type)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."review_like WHERE review_id = '" . (int)$review_id . "' AND type = '" . (int)$type . "' ");
		return $query->row['total'];
	}
}