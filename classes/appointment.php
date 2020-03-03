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
	public function SetRequest($service, $post_params=array(),$method="post"){
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
		curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);       
		// curl_setopt($ch1, CURLOPT_USERPWD, $this->config['login'] . ":" . $this->config['pass']);
		curl_setopt($ch1, CURLOPT_URL, $this->config['host'].":".$this->config['port'].$service);       
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec( $ch1 );
		curl_close( $ch1 );
		$response=json_decode($response);
	return $response;
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
	public function getViewLink($study,$series,$obj){
		$params="requestType=WADO&studyUID=".$study."&seriesUID=".$series."&objectUID=".$obj."&contentType=image/jpeg&frameNumber=1";
		return $this->config['protocol'].$this->config['host'].":".$this->config['port'].$this->config["viewer"]."?".$params;
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
		$service = "/dcm4chee-arc/aets/DCM4CHEE/rs/series/".$seriesID;
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