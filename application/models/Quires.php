<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	class Quires extends CI_Model {
		
		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		public function show($table_name){
			$query = $this->db->get($table_name);
			return $query->result(); 
		}
		public function show_where($table_name){
			$this->db->where('flag_status',0);
			$query = $this->db->get($table_name);
			return $query->result(); 
		}

		function insert_batch($table_name, $data)
		{
			$this->db->insert_batch($table_name, $data);
		}
	function insert_mlecs_record($records,$reviewer)
	{
		$table_id = 1;
		$this->db->select('table_id');
		$this->db->from('mlecs_record');
		$query = $this->db->get();
		$existing_records = $query->result();

		foreach ($records['mlecs_record_f_list_id'] as $index => $processing_id) {
			foreach ($existing_records as $record) {
				if ($record->table_id == $table_id) {
					$table_id++;
				}
			}
			$insertedRecord = array(
				'table_id' => $table_id,
				'mlecs_record_f_list_id' => $records['mlecs_record_f_list_id'][$index],
				'ao_date' => $records['ao_date']
			);
			$cra_records_succ = $this->db->insert('mlecs_record', $insertedRecord);
		}

		$this->db->insert('mlecs_reviewer_sign',$reviewer);
		$record_id = $records['mlecs_record_f_list_id'];
		$this->db->where_in('mlecs_list_id', $record_id);
		$this->db->set('flag_status', 1);
		$this->db->update('mlecs_list');

	}

	public function update_field($id, $field, $value)
	{
		$this->db->where('mlecs_list_id', $id);
		$this->db->set($field, $value);
		$this->db->update('mlecs_list');
		return ($this->db->affected_rows() > 0);
	}

	}