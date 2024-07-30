
<?php
class ModelExtensionModuleVacancy extends Model {

	public function getVacancies()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vacancy WHERE status = 1 ORDER BY id DESC";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getVacancyRequirements($vacancy_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vacancy_requirements WHERE vacancy_id = '" . (int)$vacancy_id . "'");

		return $query->rows;
	}
}