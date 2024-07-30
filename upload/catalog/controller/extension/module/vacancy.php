<?php
class ControllerExtensionModuleVacancy extends Controller {
	public function index() {
        $this->load->language('extension/module/vacancy');
		$this->load->model('extension/module/vacancy');

		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		$data['vacancies'] = array();

		$results = $this->model_extension_module_vacancy->getVacancies();

		foreach ($results as $result) {

			$requirement_data = array();

			if($result['id']) {
				$requirements = $this->model_extension_module_vacancy->getVacancyRequirements($result['id']);

				foreach ($requirements as $requirement) {
					$requirement_data[] = array(
						'name' => $requirement['name'],
					);
				}
			}

			$data['vacancies'][] = array(
				'vacancy_id'  => $result['id'],
				'title'       => $result['title'],
				'description'       => $result['description'],
				'requirements'=> $requirement_data
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/module/vacancy', $data));	
	}
}