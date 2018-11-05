<?php
class ControllerApiAnnouncement extends Controller {
	public function index() {
		$data['announcement_bar_bar_text_1'] = $this->config->get('announcement_bar_bar_text_1');
		$data['announcement_bar_bar_text_2'] = $this->config->get('announcement_bar_bar_text_2');
		$data['announcement_bar_bar_text_3'] = $this->config->get('announcement_bar_bar_text_3');
		$data['announcement_bar_status'] = $this->config->get('announcement_bar_status');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
}