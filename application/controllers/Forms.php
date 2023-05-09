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
    $table_ids = array(); // array to store the unique table_id values
    foreach ($data as $row) {
        // check if the current row's table_id is already in the array
        if (!in_array($row->table_id, $table_ids)) {
            // add the table_id to the array
            $table_ids[] = $row->table_id;
            // display the row
            $output .= '
                <tr>
                    <td>' . sprintf("%03d", $row->mlecs_record_f_list_id) . '</td>
                    <td><a  id="'.$row->table_id.'" class="select-record" data-toggle="modal" data-target="#cartModal">' . $row->ao_date . '</a></td>
                    <td style="text-align:center;"> 
                    <a style="font-size:20px;text-align:center" class="pdfPrint"  id="'.$row->table_id.'"><i class="fa fa-print" aria-hidden="true"></i></a>
                    &nbsp
                    <a style="font-size:20px;text-align:center" class="pdfDownload"  id="'.$row->table_id.'"><i class="fa fa-download" aria-hidden="true"></i></a>
                    </td>
                </tr>
            ';
        }
    }
    echo $output;
}
    
    public function mlecs_show_record_data() {
        // $record_id = $this->input->post('record_id');
        $record_id = 1;
        $this->db->select('*');
        $this->db->from('mlecs_record');
        $this->db->join('mlecs_list', 'mlecs_record.mlecs_record_f_list_id = mlecs_list.mlecs_list_id', 'inner');
        $this->db->where('mlecs_record.mlecs_record_id', $record_id);
        $query = $this->db->get();
        $output='';
        $output.='
        <div class="modal-header border-bottom-0" style="text-align:center">
                            <img width="15%" src="'.base_url("assets/images/logo.png").'" alt="" srcset="">
                            <h5 style="text-align:center;" class="modal-title" id="exampleModalLabel">
                            Master List of Equipment Calibration Schedule
                            </h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-image">
                            <thead>
                                <tr>
                                <th scope="col"> Equipment ID</th>
                                <th scope="col">Equipment Description</th>
                                <th scope="col">Equipment Manufacturer</th>
                                <th scope="col">Serial Number</th>
                                <th scope="col">Calibration Period</th>
                                <th scope="col">Last Calibration Date</th>
                                <th scope="col">Calibration Due Date</th>
                                <th scope="col">Calibrating Body/Organization</th>
                                </tr>
                            </thead>
                            <tbody>';
        if ($query->num_rows() > 0) {
            $record = $query->row();
            $output.='
            <tr>
                <td>' . 'EQ-'. sprintf("%03d", $record->mlecs_record_f_list_id). '</td>
                <td>' . $record->mlecs_list_equip_desc . '</td>
                <td>' . $record->mlecs_list_equip_manuf . '</td>
                <td>' . $record->mlecs_list_mlecs_sn . '</td>
                <td>' . $record->mlecs_list_cp . '</td>
                <td>' . $record->mlecs_list_lcd . '</td>
                <td>' . $record->mlecs_list_cd . '</td>
                <td>' . $record->mlecs_list_cbo . '</td>
             </tr>
            ';
        } else {
            $output = '<tr><td colspan="8">Record not found</td></tr>';
        }
        $output.='  
        </tbody>
        </table> 
    </div>
    <div class="modal-footer border-top-0 d-flex justify-content-between">
        <a id="'.$record_id.'" class="pdfPrint btn btn-success">Print</a>
        <a id="'.$record_id.'" class="pdfDownload btn btn-success">Download</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>';
        echo $output;
    }

    public function pdf()
    {
        $this->load->library('pdf');
        $this->load->helper('file');
        $record_id = $this->input->post('id');
        $this->db->select('*');
        $this->db->from('mlecs_record');
        $this->db->join('mlecs_list', 'mlecs_record.mlecs_record_f_list_id = mlecs_list.mlecs_list_id', 'inner');
        $this->db->join('cra_reviewer_sign', 'mlecs_record.table_id = cra_reviewer_sign.table_id', 'inner');
        $this->db->where('mlecs_record.table_id', $record_id);
        $query = $this->db->get();
        $data = $query->result();
        $logo_image = file_get_contents('assets/images/logo.png');
        $logo_data_uri = 'data:image/png;base64,' . base64_encode($logo_image);
        $html_content = '';
        $html_content .='
        <style>
        div.layout-978 { width: 978px; margin: 0px auto; }
        table {
            border-collapse: collapse;
          }
          
          td, th {
            padding: 8px;
            text-align:center;
          }
        
        .datagrid {
            border-collapse: collapse;
        }
        
        .datagrid thead tr th {
            background: #8080801f;
            color: #fffff;
            font-size: 11px;
            font-weight: normal;
            text-align: center;
            padding: 6px 8px;
            border: 1px solid #c9c9c9;
        }
        .datagrid tbody tr {
            background: #fff;
        }
        
        .datagrid tbody tr td {
            font-size: 10px;
            text-align:center;
            padding: 6px 8px;
            border: 1px solid #c9c9c9;
        }
       .signature-container-wrapper {
            width: 100%;
            max-width: 800px; /* set a maximum width for the table */
            margin: 0 auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 16px;
            }
        .signature-container {
        padding: 10px;
        text-align: center;
        }

        .signature-container hr {
        border: none;
        border-top: 1px solid #000;
        width:160px;
        }

        .signature-container .name {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 5px;
        }

        .signature-container .position {
        margin-bottom: 5px;
        font-size: 12px;
        }

        .signature-container .date {
        margin-top: 5px;
        font-size: 12px;
        }    
        .datagrid tbody tr td.cash {
            text-align: right;
        }
        </style>
        <table class="datagrid">
        <thead>
            <tr>
            <th colspan="8">
            <img width="15%" src="'.$logo_data_uri.'">
            <h5 style="font-size:15px;">Master List of Equipment Calibration Schedule </h5>    
            </th>
            </tr>
        </thead> 
        <thead style="text-align:center;">
            <tr style="text-align:center;">
            <th scope="col"> Equipment ID</th>
            <th scope="col">Equipment Description</th>
            <th scope="col">Equipment Manufacturer</th>
            <th scope="col">Serial Number</th>
            <th scope="col">Calibration Period</th>
            <th scope="col">Last Calibration Date</th>
            <th scope="col">Calibration Due Date</th>
            <th scope="col">Calibrating Body/Organization</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($data as $key => $row) {
            $html_content .= '
        <tr>
            <td>' . 'EQ-'. sprintf("%03d", $row->mlecs_record_f_list_id). '</td>
            <td>' . $row->mlecs_list_equip_desc . '</td>
            <td>' . $row->mlecs_list_equip_manuf . '</td>
            <td>' . $row->mlecs_list_mlecs_sn . '</td>
            <td>' . $row->mlecs_list_cp . '</td>
            <td>' . $row->mlecs_list_lcd . '</td>
            <td>' . $row->mlecs_list_cd . '</td>
            <td>' . $row->mlecs_list_cbo . '</td>
         </tr>
            ';
        }
        $html_content.='
                </tbody>
                </table>
                <br>';
        
        $imageData = $row->rev_sign;
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);
        $file_path = FCPATH . 'assets/sign1.png';
        write_file($file_path, $imageBinary);
        $sign_image = file_get_contents(base_url('assets/sign1.png'));
        $sign_data_uri = 'data:image/png;base64,' . base64_encode($sign_image);
        // sign 2
        $imageData2 = $row->appr_sign;
        $imageData2 = str_replace('data:image/png;base64,', '', $imageData2);
        $imageData2 = str_replace(' ', '+', $imageData2);
        $imageBinary2 = base64_decode($imageData2);
        $file_path2 = FCPATH . 'assets/sign2.png';
        write_file($file_path2, $imageBinary2);
        $sign_image2 = file_get_contents(base_url('assets/sign2.png'));
        $sign_data_uri2 = 'data:image/png;base64,' . base64_encode($sign_image2);
      
        // reviewed date
        $rev_date =  $row->rev_date;
        $rev_formattedDate = date('M j, Y', strtotime($rev_date));
        // approval date
        $appr_date =  $row->appr_date;
        $appr_formattedDate = date('M j, Y', strtotime($appr_date));
        // reviewed date
        $rev_date =  $row->rev_date;
        $rev_timestamp = strtotime($rev_date);
        $rev_formattedDate = date('M j, Y', $rev_timestamp);
        // approval date
        $appr_date =  $row->appr_date;
        $appr_timestamp = strtotime($appr_date);
        $appr_formattedDate = date('M j, Y', $appr_timestamp);
        $html_content .= '
                <table class="signature-container-wrapper">
            <tr>
                <td class="signature-container">
               <h6>Reviewed By:<h6><br> <img width="40%" src="'.$sign_data_uri.'">
                <hr>
                <div class="name">' . $row->rev_name . '</div>
                <div class="position">' . $row->rev_position . '</div>
                <div class="date">' . $rev_formattedDate . '</div>
              
                </td>
                <td class="signature-container">
                <h6>Approved By:<h6><br> <img width="40%" src="'.$sign_data_uri2.'">
                <hr>
                <div class="name">' . $row->appr_name . '</div>
                <div class="position">' . $row->appr_position . '</div>
                <div class="date">' . $appr_formattedDate . '</div>
                </td>
            </tr>
            </table>
            </div>';
            $this->pdf->load_html($html_content);
            $this->pdf->render();
            // Print the PDF
            $this->pdf->stream("pdf_with_image.pdf", array("Attachment" => false));
        }


}
