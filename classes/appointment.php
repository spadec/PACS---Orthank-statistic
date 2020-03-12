<?php
// (c)Разработчик ИП PROFit
class orthank {
	protected $config;
	public function __construct($config) {
		$this->config = $config;
	}
	private function SetRequest($service, $post_params=array(),$method="post"){
		$ch1 = curl_init($this->config['host']);
		if($post_params && count($post_params)){
			$post_params = json_encode($post_params);
			$post_params = str_replace("u0022", "\"", $post_params);
			$post_params = str_replace("u0027", "\'", $post_params);
		}
		switch ($method) {
			case 'post':
				curl_setopt($ch1, CURLOPT_POST, true);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_params);
				break;
			case 'get':

				break;
			default:
				# code...
				break;
		}
		curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);       
		curl_setopt($ch1, CURLOPT_USERPWD, $this->config['login'] . ":" . $this->config['pass']);
		curl_setopt($ch1, CURLOPT_URL, $this->config['host'].":".$this->config['port'].$service);       
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec( $ch1 );
		curl_close( $ch1 );
		$response=json_decode($response);
	return $response;
	}
	public function getPatients($patientID=""){
		$service = "/patients/".$patientID;
		return $this->SetRequest($service,"","get");
	}
	public function getStudies($studiesID=""){
		$service = "/studies/".$studiesID;
		return $this->SetRequest($service,"","get");
	}
	public function getSeries($seriesID=""){
		$service = "/series/".$seriesID;
		return $this->SetRequest($service,"","get");
	}

	public function getInstances($instancesID=""){
		$service = "/instances/".$instancesID;
		return $this->SetRequest($service,"","get");
	}
	public function findOrthankData($find){
		$service = "/tools/find";
		return $this->SetRequest($service, $find, "post");
	}
	public function prettyDate($str = null,$sep = null)	{
		$year = $str[0]. $str[1]. $str[2]. $str[3];
		$month = $str[4].$str[5];
		$day = $str[6].$str[7];
		return $day.$sep.$month.$sep.$year;
		//return $year.$sep.$month.$sep.$day;
	}
	public function getViewLink($type,$ID){
		return $this->config['protocol'].$this->config['host'].":".$this->config['port'].$this->config["viewer"]."?".$type."=".$ID;
	}
	public function getSeriesLink(){
		return "#";
	}
	public function getQueryFromFilter($filter){
		$fio = "*"; $iin ="*"; $StudyDate="*"; $patientBDate="*";
		if(isset($filter['studyDate']) && $filter['studyDate']){
		  $StudyDate = $this->getOrthankDate($filter['studyDate']);
		}
		if(isset($filter['iin'])){
		  $iin = $filter['iin'];
		}
		if(isset($filter['FIO'])){
		  $fio = $filter['FIO'];
		}
		if(isset($filter['patientBDate']) && $filter['patientBDate']){
		  $patientBDate = $this->getOrthankDate($filter['patientBDate']);
		}
		return array('PatientID'=>$iin,
					 'StudyDate'=>$StudyDate,
					 'PatientName'=>$fio."*",
					 'PatientBirthDate'=>$patientBDate);
	}
	public function getOrthankDate($date){
		$endString = null;
		$dateArr = explode("|",$date);
		$start = trim($dateArr[0]);
		if(isset($dateArr[1])){
			$end = trim($dateArr[1]);
			$endString = "-".$end[6].$end[7].$end[8].$end[9].$end[3].$end[4].$end[0].$end[1];
		}
		return $start[6].$start[7].$start[8].$start[9].$start[3].$start[4].$start[0].$start[1].$endString;
	}
}



class dcm4chee {
	protected $config;
	public function __construct($config)
	{
		$this->config = $config;
	}
	// $this->config['auth'].'?'.'grant_type=client_credentials&client_id=curl&client_secret='.$this->config['secret']
	public function SetToken() {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://192.168.10.60:8843/auth/realms/dcm4che/protocol/openid-connect/token');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=curl&client_secret=3bf9ddfd-6590-446b-8c8e-83df0ae85ea6");

		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return json_decode($result);
	}

	public function SetRequest($service, $post_params=array(),$method="post"){
		$token = $this->SetToken();
		
		$ch1 = curl_init($this->config['host']);
		if($post_params && count($post_params)){
			$post_params = json_encode($post_params);
			$post_params = str_replace("u0022", "\"", $post_params);
			$post_params = str_replace("u0027", "\'", $post_params);
		}
		switch ($method) {
			case 'post':
				curl_setopt($ch1, CURLOPT_POST, true);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_params);
				break;
			case 'get':
				// curl_setopt($ch1 ,PARAM)
				// curl_setopt($ch1, CURLOPT_POST, false);
				// curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_params);
				break;
			default:
				# code...
				break;
		}
		// $date=http_build_query($post_params);
		curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token->access_token
		));       
		// curl_setopt($ch1, CURLOPT_USERPWD, $this->config['login'] . ":" . $this->config['pass']);
		curl_setopt($ch1, CURLOPT_URL, $this->config['host'].":".$this->config['port'].$service);       
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec( $ch1 );
		curl_close( $ch1 );
		$response=json_decode($response);
		return $response;
	}

	public function getPicture($study , $series , $obj) {
		
		$token = $this->SetToken();
		$ch1 = curl_init($this->config['host']);    
		
		// $date=http_build_query($post_params);
		curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
			'Accept: multipart/related;type=application/dicom', 
			// 'Content-Type: application/dicom',
			'Authorization: Bearer ' . $token->access_token
		));       
		curl_setopt($ch1, CURLOPT_ENCODING, 'UTF-8');
		// curl_setopt($ch1, CURLOPT_USERPWD, $this->config['login'] . ":" . $this->config['pass']);
		curl_setopt($ch1, CURLOPT_URL, "http://".$this->config['host'].":8080/dcm4chee-arc/aets/DCM4CHEE/rs/studies/".$study."/series/".$series."/instances/".$obj."");       
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec( $ch1 );
		$fd = fopen("../files/".str_replace("." , "" , $study).".dcm", 'w') or die("не удалось создать файл");
		fwrite($fd, $response);
		fclose($fd);

		$file_ot = file("../files/".str_replace("." , "" , $study).".dcm");
		$file = array_splice($file_ot,4);
		file_put_contents("../files/".str_replace("." , "" , $study).".dcm" , implode("" , $file));
		
		curl_close( $ch1 );
		return "/files/".str_replace("." , "" , $study).".dcm";
		// header("location: /download.php?file=".str_replace("." , "" , $study).".dcm");
	}

	public function prettyDate($str = null,$sep = null)	{
		if ($str) {
			$year = $str[0]. $str[1]. $str[2]. $str[3];
			$month = $str[4].$str[5];
			$day = $str[6].$str[7];
			return $day.$sep.$month.$sep.$year;
		}
		else {
			return "";
		}
		//return $year.$sep.$month.$sep.$day;
	}
	public function prettyTime($str=null,$sep=null) {
		$h = $str[0]. $str[1];
		$m = $str[2]. $str[3];
		$s = $str[4]. $str[5];
		return $h.$sep.$m.$sep.$s;
	}
	public function getViewLink($study){
		$token = $this->SetToken();
		
		$ch1 = curl_init($this->config['host']);
		
		// $date=http_build_query($post_params);
		// curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false );
		// curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token->access_token
		));       
		curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);       
		curl_setopt($ch1, CURLOPT_USERPWD, "admin:admin");
		// curl_setopt($ch1, CURLOPT_USERPWD, $this->config['login'] . ":" . $this->config['pass']);
		curl_setopt($ch1, CURLOPT_URL, "http://192.168.10.60:8042/dcm4chee-arc/aets/DCM4CHEE/wado?studyUID=".$study."&seriesUID=".$series."&objectUID=".$obj."&contentType=image/jpeg&frameNumber=1");       
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec( $ch1 );
		curl_close( $ch1 );
		$response=json_decode($response);
		return $response;
	}
	public function getSeriesLink(){
		return "#";
	}
	public function getStudies($studiesID="") {
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/studies?".$studiesID;
		return $this->SetRequest($service,"","get");
	}
	public function getPath($studiesID="" , $seriasID="" , $instancesID="") {
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/studies?";
		return $this->SetRequest($service,"","get");
	}
	public function getPatients($patientsID="") {
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/patients?".$patientsID;
		return $this->SetRequest($service,"","get");
	}
	public function getSeries($seriesID=""){
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/series?".$seriesID;
		return $this->SetRequest($service,"","get");
	}

	public function getInstances($res=""){
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/instances?";
		return $this->SetRequest($service,$res,"get");
	}
	public function getInstancesGet($res){
		// print_r($res);
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/instances?".$res;
		return $this->SetRequest($service,"","get");
	}
	public function findDCM4CHEEData($find){
		return $this->SetRequest("/instances?",$find, "get");
	}

	public function getQueryFromFilter($filter) {
		$fio="*";$iin="*";$StudyDate="*";$patientBDate="*";
		if(isset($filter['studyDate']) && $filter['studyDate']) {
			$StudyDate=$this->getOrthankDate($filter['studyDate']);
		}
		if(isset($filter['iin'])){
			$iin = $filter['iin'];
		  }
		  if(isset($filter['FIO'])){
			$fio = $filter['FIO'];
		  }
		  if(isset($filter['patientBDate']) && $filter['patientBDate']){
			$patientBDate = $this->getOrthankDate($filter['patientBDate']);
		  }
		  return array('PatientID'=>$iin,
					   'StudyDate'=>$StudyDate,
					   'PatientName'=>$fio."*",
					   'PatientBirthDate'=>$patientBDate);
	}

	public function getOrthankDate($date) {
		$endString = null;
		$dateArr = explode("|",$date);
		$start = trim($dateArr[0]);
		if(isset($dateArr[1])){
			$end = trim($dateArr[1]);
			$endString = "-".$end[6].$end[7].$end[8].$end[9].$end[3].$end[4].$end[0].$end[1];					
		}
		return $start[6].$start[7].$start[8].$start[9].$start[3].$start[4].$start[0].$start[1].$endString;
	}
}