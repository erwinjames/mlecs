<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
defined('BASEPATH') or exit('No direct script access allowed');

class Forms extends CI_Controller
{

    protected $content = '';

    public function __construct()
    {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Quires');
    }
 
    public function fwcc_mlecs($form)
    {
        switch (strtolower($form)) {
            case 'mlecs':
                if(isset($_POST['save_record'])){
                    try {
                        if (isset($_FILES['reviewer_sign_img']['name'])) {
                            $uploaddir = './uploads/fwcc/images/';
                            $sign1   = basename($_FILES['reviewer_sign_img']['name']);
                            $uploadfile = $uploaddir . $sign1;
                            move_uploaded_file($_FILES['reviewer_sign_img']['tmp_name'], $uploadfile);
                        } else {
                            $sign1 = '';
                        }
                        if (isset($_FILES['approver_sign_img']['name'])) {
                            $uploaddir = './uploads/fwcc/images/';
                            $sign2   = basename($_FILES['approver_sign_img']['name']);
                            $uploadfile = $uploaddir . $sign2;
                            move_uploaded_file($_FILES['approver_sign_img']['tmp_name'], $uploadfile);
                        } else {
                            $sign2 = '';
                        }
                        $mlecs_list_id = $this->input->post('list_id');
                        $calibrationD = $this->input->post('asofdate');
                        $insert_record = array(
                            'mlecs_record_f_list_id' => $mlecs_list_id,
                            'ao_date' =>  $calibrationD
                        );

                        $table_id = 1;
                        $this->db->select('table_id');
                        $this->db->from('mlecs_record');
                        $query = $this->db->get();
                        $existing_records = $query->result();
                        foreach ($existing_records as $record) {
                            if ($record->table_id == $table_id
                            ) {
                                $table_id++;
                            }
                        }
                            $for_reviewer = array(
                            'table_id' => $table_id,
                            'rev_name' => $this->input->post('reviewer_name'),
                            'rev_sign' => $this->input->post('reviewer_sign'),
                            'rev_sign_image' => $sign1,
                            'rev_position' => $this->input->post('r_position'),
                            'rev_date' => $this->input->post('reviewed_date'),
                            'appr_name' => $this->input->post('approver_name'),
                            'appr_sign' => $this->input->post('approver_sign'),
                            'appr_sign_image' => $sign2,
                            'appr_position' => $this->input->post('a_position'),
                            'appr_date' => $this->input->post('approved_date')
                        );
                        
                        $this->Quires->insert_mlecs_record($insert_record,$for_reviewer);
                        $this->session->set_flashdata('success_msg', 'Inserted Successfully!');
                        redirect('forms/fwcc_mlecs/mlecs');
                    } catch(\Exception $e) {
                        log_message('error', $e->getMessage());
                        redirect($this->agent->referrer());
                    }
                }
                $this->content = 'fwcc/mlecs_form';
                break;
            case 'list':
                $this->content = 'fwcc/mlecs_list';
                break;
            default:
                redirect($this->agent->referrer());
                break;
        }
        $this->load->view($this->content);
    }

    public function mlecs_insert_form()
{
    $data = array(
        'mlecs_list_equip_desc' => $this->input->post('equipmentDesc'),
        'mlecs_list_equip_manuf' => $this->input->post('equipmentManu'),
        'mlecs_list_mlecs_sn' => $this->input->post('serialNum'),
        'mlecs_list_cp' => $this->input->post('calibrationPeriod'),
        'mlecs_list_lcd' => $this->input->post('lastCalibrationDate'),
        'mlecs_list_cd' => $this->input->post('calibrationDueDate'),
        'mlecs_list_cbo' => $this->input->post('calibratingBody')
    );
    $this->Quires->insert_batch('mlecs_list', array($data));
    echo "Equipment added successfully";
}

public function mlecs_show(){
    $data = $this->Quires->show_where('mlecs_list');

    $output='';
    foreach($data as $row){
        $output .= '
            <tr>
                <td>EQ-' . sprintf("%03d", $row->mlecs_list_id ) .'</td>
                <td>'.$row->mlecs_list_equip_desc.'</td>
                <td>'.$row->mlecs_list_equip_manuf.'</td>
                <td>'.$row->mlecs_list_mlecs_sn.'</td>
                <td>'.$row->mlecs_list_cp.'</td>
                <td>'.$row->mlecs_list_lcd.'</td>
                <td>'.$row->mlecs_list_cd.'</td>
                <td>'.$row->mlecs_list_cbo.'</td>
            </tr>
            <input type="hidden" name="list_id[]" value="'.$row->mlecs_list_id.'">
        ';
    }
    
    echo $output;
}

    public function mlecs_show_list()
    {
        $data = $this->Quires->show('mlecs_record');

        $output = '';
        foreach ($data as $row) {
            $output .= '
            <tr>
                <td>' . sprintf("%03d", $row->mlecs_record_f_list_id) . '</td>
                <td>' . $row->ao_date . '</td>
                <td></td>
            </tr>
        ';
        }
        echo $output;
    }


}
