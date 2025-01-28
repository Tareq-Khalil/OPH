<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_company(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `company_list` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "company Name already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `company_list` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `company_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id)){
				$res['msg'] = "New Company successfully saved.";
				$id = $this->conn->insert_id;
			}else{
				$res['msg'] = "Company successfully updated.";
			}
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				if(!is_dir(base_app.'uploads/company_logos'))
					mkdir(base_app.'uploads/company_logos');
				$fname = "uploads/company_logos/{$id}.png";
				$dir_path =base_app. $fname;
				$upload = $_FILES['img']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('image/png','image/jpeg');
				if(!in_array($type,$allowed)){
					$resp['msg'].=" But Image failed to upload due to invalid file type.";
				}else{
					$new_height = 200; 
					$new_width = 200; 
			
					list($width, $height) = getimagesize($upload);
					$t_image = imagecreatetruecolor($new_width, $new_height);
					imagealphablending( $t_image, false );
					imagesavealpha( $t_image, true );
					$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
					imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					if($gdImg){
							if(is_file($dir_path))
							unlink($dir_path);
							$uploaded_img = imagepng($t_image,$dir_path);
							imagedestroy($gdImg);
							imagedestroy($t_image);
					}else{
					$resp['msg'].=" But Image failed to upload due to unkown reason.";
					}
				}
			}

		$this->settings->set_flashdata('success',$res['msg']);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_company(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `company_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Company successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_fee(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `fee_list` where `amount_from` = '{$amount_from}' and `amount_to` = '{$amount_to}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Amount Range already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `fee_list` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `fee_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Amount Charge/Fee successfully saved.");
			else
				$this->settings->set_flashdata('success',"Amount Charge/Fee successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_fee(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `fee_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Amount Charge/Fee  successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function get_fee(){
		extract($_POST);
		$fee_qry = $this->conn->query("SELECT fee FROM fee_list where `amount_from` <= '{$amount}'  and `amount_to` >= '{$amount}' order by unix_timestamp(`date_created`) desc limit 1 ");
		$fee = 0;
		if($fee_qry->num_rows > 0){
			$fee = $fee_qry->fetch_array()['fee'];
		}
		$resp['status'] = 'success';
		$resp['fee'] = $fee;
		$resp['payable'] = floatval($fee) + floatval($amount);
		return json_encode($resp);
	}
	function save_transaction(){
		if(empty($_POST['id'])){
			$prefix = substr(str_shuffle(implode("",range("A","Z"))),0,3);
			$code = $prefix."-".(sprintf("%'.012d",rand(1,999999999999)));
			while(true){
				$check_code = $this->conn->query("SELECT * FROM `transaction_list` where tracking_code ='{$code}' ")->num_rows;
				if($check_code > 0){
					$code = $prefix."-".(sprintf("%'.012d",rand(1,999999999999)));
				}else{
					break;
				}
			}
			$_POST['tracking_code'] = $code;
		}
		$_POST['user_id'] = $this->settings->userdata('id');
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v= $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=", ";
				$data .=" `{$k}` = '{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `transaction_list` set {$data}";
		}else{
			$sql = "UPDATE `transaction_list` set {$data} where id = '{$id}'";
		}
		$save = $this->conn->query($sql);
		if($save){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'An error occured. Error: '.$this->conn->error;
		}
		if($resp['status'] == 'success')
		$this->settings->set_flashdata('success'," Transaction's Details Successfully updated.");

		return json_encode($resp);
	}
	function delete_transaction(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `transaction_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"transaction's Details Successfully saved.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_company':
		echo $Master->save_company();
	break;
	case 'delete_company':
		echo $Master->delete_company();
	break;
	case 'save_fee':
		echo $Master->save_fee();
	break;
	case 'delete_fee':
		echo $Master->delete_fee();
	break;
	case 'get_fee':
		echo $Master->get_fee();
	break;
	case 'save_transaction':
		echo $Master->save_transaction();
	break;
	case 'delete_transaction':
		echo $Master->delete_transaction();
	break;
	default:
		// echo $sysset->index();
		break;
}