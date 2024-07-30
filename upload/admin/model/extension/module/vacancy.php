<?php

class ModelExtensionModuleVacancy extends Model
{

	public function getVacancies()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vacancy";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getVacancy($vacancy_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "vacancy WHERE id = '" . (int)$vacancy_id . "' ");

		return $query->row;
	}


	public function getVacancyRequirements($vacancy_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vacancy_requirements WHERE vacancy_id = '" . (int)$vacancy_id . "'");

		return $query->rows;
	}


	public function addVacancy(array $data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "vacancy SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', status = '" . (int)$data['status'] . "' ");

		$vacancy_id = $this->db->getLastId();

		if (isset($data['requirement'])) {
			foreach ($data['requirement'] as $require) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "vacancy_requirements SET vacancy_id = '" . (int)$vacancy_id . "', name = '" . $this->db->escape($require['name']) . "'");
			}
		}
	}

	public function editVacancy($vacancy_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "vacancy SET title = '" . $this->db->escape($data['title']) . "', description = '" . trim($this->db->escape($data['description'])) . "', status = '" . (int)$data['status'] . "' WHERE id = '" . (int)$vacancy_id . "'");


		$this->db->query("DELETE FROM " . DB_PREFIX . "vacancy_requirements WHERE vacancy_id = '" . (int)$vacancy_id . "'");


		if (isset($data['requirement'])) {
			foreach ($data['requirement'] as $require) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "vacancy_requirements SET vacancy_id = '" . (int)$vacancy_id . "', name = '" . $this->db->escape($require['name']) . "'");
			}
		}
	}

	public function deleteVacancy($vacancy_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "vacancy_requirements WHERE vacancy_id = '" . (int)$vacancy_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "vacancy WHERE id = '" . (int)$vacancy_id . "'");
	}
}
