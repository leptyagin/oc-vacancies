<?php
class ControllerExtensionModuleVacancy extends Controller
{
	// этот контроллер выводит обзоры от блогеров, рестаранов и т.д.

	private $error = array();

	public function index()
	{
		$this->load->language('extension/module/vacancy');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/vacancy');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('extension/module/vacancy');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/vacancy');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_vacancy->addVacancy($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/vacancy', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}


	public function edit()
	{
		$this->load->language('extension/module/vacancy');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/vacancy');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_vacancy->editVacancy($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('extension/module/vacancy', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('extension/module/vacancy');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/vacancy');

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $vacancy_id) {
				$this->model_extension_module_vacancy->deleteVacancy($vacancy_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('extension/module/vacancy', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('extension/module/vacancy/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$this->load->model('tool/image');
		$data['delete'] = $this->url->link('extension/module/vacancy/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['vacancies'] = array();


		$results = $this->model_extension_module_vacancy->getVacancies();

		foreach ($results as $result) {

			$data['vacancies'][] = array(
				'vacancy_id' => $result['id'],
				'title' => $result['title'],
				'description' => $result['description'],
				'status' => $result['status'],

				'edit'       => $this->url->link('extension/module/vacancy/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/vacancy_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['company_name_error'])) {
			$data['company_name_error'] = $this->error['company_name_error'];
		} else {
			$data['company_name_error'] = array();
		}

		if (isset($this->error['company_image_error'])) {
			$data['company_image_error'] = $this->error['company_image_error'];
		} else {
			$data['company_image_error'] = array();
		}

		if (isset($this->error['description_error'])) {
			$data['description_error'] = $this->error['description_error'];
		} else {
			$data['description_error'] = array();
		}

		if (isset($this->error['frame_error'])) {
			$data['frame_error'] = $this->error['frame_error'];
		} else {
			$data['frame_error'] = array();
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/vacancy', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('extension/module/vacancy/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/module/vacancy/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/module/vacancy', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$vacancy_info = $this->model_extension_module_vacancy->getVacancy($this->request->get['id']);
		}

		$data['user_token'] = $this->session->data['user_token'];


		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($vacancy_info)) {
			$data['title'] = $vacancy_info['title'];
		} else {
			$data['title'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($vacancy_info)) {
			$data['description'] = $vacancy_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
			$data['status'] = true;
		}


		if (isset($this->request->post['requirements'])) {
			$requirements = $this->request->post['requirements'];
		} elseif (isset($this->request->get['id'])) {
			$requirements = $this->model_extension_module_vacancy->getVacancyRequirements($this->request->get['id']);
		} else {
			$requirements = array();
		}


		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		$data['requirements'] = array();

		foreach ($requirements as $requirement) {

			$data['requirements'][] = array(
				'name' => $requirement['name'],
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/vacancy_form', $data));
	}

	protected function validateForm(): bool
	{

		if (utf8_strlen($this->request->post['title']) == 0) {
			$this->error['title_error'] = $this->language->get('error_text');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}


	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'extension/module/vacancy')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
