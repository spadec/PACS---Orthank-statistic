<?php
function tohttp( $query ){

  $query_array = array();

  foreach( $query as $key => $key_value ){

      $query_array[] = urlencode( $key ) . '=' . urlencode( $key_value );

  }

  return implode( '&', $query_array );

}

function getage($str) {
  if($str) {
    
  }
  else {
    return "Нет данных";
  }
}



session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Omsk');
include_once "config.php";
if (isset($_GET["lang"])) {
  $lang = $_GET["lang"];
} else { // язык по умолчанию
  $lang = "ru";
}
if ($lang == "ru") {
  $ru_active = "active";
  $kz_active = "";
  $en_active = "";
} elseif ($lang == "kz") {
  $ru_active = "";
  $en_active = "";
  $kz_active = "active";
} elseif ($lang == "en") {
  $ru_active = "";
  $kz_active = "";
  $en_active = "active";
} else {
  $ru_active = "";
  $kz_active = "";
  $ru_active = "";
}

$lang_file = file_get_contents('langs/' . $lang . '.json');
$lng = json_decode($lang_file);
if (isset($_POST['submit'])) {
  $query = $db->getQueryFromFilter($_POST);
} else {
  $query = array('PatientID' => '*');
}
$req = array('Limit' => '2');
$studyes = $db->findDCM4CHEEData($req);
$lastdate = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title><?php echo $lng->title; ?></title>
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <script src="vendor/jquery/jquery.min.js"></script>
  <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
</head>

<body id="page-top">
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="index.php">antis:PACS</a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>
    <!-- Navbar -->
    <div class="btn-group  ml-auto switcher">
      <label class="btn btn-light sw" data-s="1">
        <a href="./ajax/setServer.php?type=1">Orthank</a> 
      </label>
      <label  class="btn btn-light sw" data-s="2">
        <a href="./ajax/setServer.php?type=2">DCM4CHEE</a>
      </label>
    </div>
    <ul class="navbar-nav ml-auto ml-md-20">
      <li class="nav-item mx-1"><a class="<?php echo $kz_active; ?>" href="index.php?lang=kz">KZ</a></li>
      <li class="nav-item mx-1"><a class="<?php echo $ru_active; ?>" href="index.php?lang=ru">RU</a></li>
      <li class="nav-item mx-1"><a class="<?php echo $en_active; ?>" href="index.php?lang=en">EN</a></li>
    </ul>
  </nav>
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-table"></i>
          <span><?php echo $lng->main; ?></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $config['protocol'] . $config['host'] . ":" . $config['port'] . $config['viewer']; ?>">
          <i class="fas fa-fw fa-folder"></i>
          <span><?php echo $lng->osimis; ?></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-chart-area"></i>
          <span><?php echo $lng->stat; ?></span></a>
      </li>
    </ul>
    <div id="content-wrapper">
      <div class="container-fluid">
        <?php if (count($studyes) >= 100) : ?>
          <div class="alert alert-warning" role="alert">
            <?php echo $lng->msgLagreOrthank; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button></div>
        <?php endif; ?>
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            <?php echo $lng->MO; ?>: <?php if ($studyes) {
                                        echo $db->getPatients();
                                      } ?></div>
          <div class="card-body">
            <form action="" method="post" id="filter" name="filter">
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label><?php echo $lng->patientID; ?></label>
                    <input value="<?php if (isset($_POST['iin'])) echo $_POST['iin']; ?>" type="text" name="iin" class="form-control">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label><?php echo $lng->patientName; ?></label>
                    <input value="<?php if (isset($_POST['FIO'])) echo $_POST['FIO']; ?>" type="text" name="FIO" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row align-items-end">
                <div class="col-4">
                  <div class="form-group">
                    <label><?php echo $lng->birthDate; ?></label>
                    <input type="text" value="<?php if (isset($_POST['patientBDate'])) echo $_POST['patientBDate']; ?>" name="patientBDate" class="form-control">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label><?php echo $lng->studyDate; ?></label>
                    <input type="text" value="<?php if (isset($_POST['studyDate'])) echo $_POST['studyDate']; ?>" name="studyDate" value="" class="form-control">
                  </div>
                </div>
                <div class="col-1" id="searchButtonBlock">
                  <div class="form-group">
                    <input name="submit" class="btn btn-primary" type="submit" value="Поиск" />
                  </div>
                </div>
                <div class="col-2" id="clearButtonBlock">
                  <div class="form-group">
                    <input name="clear" class="btn btn-danger" value="Очистить" />
                  </div>
                </div>
              </div>
            </form>
            <div class="table-responsive">
            <pre>
                   
            </pre> 
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th><?php echo $lng->patientName; ?></th>
                    <th><?php echo $lng->patientID; ?></th>
                    <th><?php echo $lng->sex; ?></th>
                    <th><?php echo $lng->age; ?></th>
                    <th><?php echo $lng->birthDate; ?></th>
                    <th><?php echo $lng->studyDate; ?></th>
                    <th><?php echo $lng->description; ?></th>
                    <th><?php echo $lng->serial; ?></th>
                    <th><?php echo $lng->view; ?></th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th><?php echo $lng->patientName; ?></th>
                    <th><?php echo $lng->patientID; ?></th>
                    <th><?php echo $lng->sex; ?></th>
                    <th><?php echo $lng->age; ?></th>
                    <th><?php echo $lng->birthDate; ?></th>
                    <th><?php echo $lng->studyDate; ?></th>
                    <th><?php echo $lng->description; ?></th>
                    <th><?php echo $lng->serial; ?></th>
                    <th><?php echo $lng->view; ?></th>
                  </tr>
                </tfoot>
                <tbody>
                <?php
                   
                    if(isset($_POST)) {
                      
                      $response = $db->getInstancesGet(tohttp($db->getQueryFromFilter($_POST)));
                      if($response) {
                        foreach($response as $el) {
                          echo "<tr>";
                          echo "<td>".$el->{'00100010'}->Value[0]->Alphabetic."</td>";  //Name patient
                          echo "<td>".$el->{'00100020'}->Value[0]."</td>";              //ID patient
                          echo "<td>".$el->{'00100040'}->Value[0]."</td>";              //Sex patient
                          // echo "<td>".$el->{'00080020'}->Value[0]."</td>";
                          echo "<td class='age'></td>";
                          // echo "<td>".$el->{'00080020'}->Value[0]."</td>";
                          
                          $bd=isset($el->{'00100030'}->Value)?$el->{'00100030'}->Value[0]:"";
                          echo "<td class='bd'>".$db->prettyDate($bd,'-')."</td>";
                          echo "<td>".$db->prettyDate($el->{'00080020'}->Value[0],'-')." ".$db->prettyTime($el->{'00080030'}->Value[0] , ':')."</td>"; 
                          echo "<td>".$el->{'0008103E'}->Value[0]."</td>";
                          echo "<td data-series='".$el->{'0020000E'}->Value[0]."'>series</td>";
                          echo "<td><a target='_blank' href=".$db->getViewLink($el->{'0020000D'}->Value[0] , $el->{'0020000E'}->Value[0] , $el->{'00080018'}->Value[0]).">Просмотр</a></td>";
                          echo "</tr>";
                        }
                      }
                      
                    }
                    else{
                      function getAge1($then) {
                        $then_ts = strtotime($then);
                        $then_year = date('Y', $then_ts);
                        $age = date('Y') - $then_year;
                        if(strtotime('+' . $age . ' years', $then_ts) > time()) $age--;
                        return $age;
                    }
                      $response = $db->getInstances($query);
                      foreach($response as $el) {
                          echo "<tr>";
                          echo "<td>".$el->{'00100010'}->Value[0]->Alphabetic."</td>";  //Name patient
                          echo "<td>".$el->{'00100020'}->Value[0]."</td>";              //ID patient
                          echo "<td>".$el->{'00100040'}->Value[0]."</td>";              //Sex patient
                          echo "<td>".$el->{'00100020'}->Value[0]."</td>";
                          $bd=isset($el->{'00100030'}->Value)?$el->{'00100030'}->Value[0]:"";
                          echo "<td class='bd'>".$db->prettyDate($bd,'-')."</td>";
                          echo "<td>".$db->prettyDate($el->{'00080020'}->Value[0],'-')." ".$db->prettyTime($el->{'00080030'}->Value[0] , ':')."</td>"; 
                          echo "<td>".$el->{'0008103E'}->Value[0]."</td>";
                          echo "<td data-series='".$el->{'0020000E'}->Value[0]."'>series</td>";
                          echo "<td><a target='_blank' href=".$db->getViewLink($el->{'0020000D'}->Value[0] , $el->{'0020000E'}->Value[0] , $el->{'00080018'}->Value[0]).">Просмотр</a></td>";
                          echo "</tr>";
                      }
                    }
                ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Последнее обновление в <?php echo $lastdate; ?></div>
        </div>
      </div>
      <!-- /.container-fluid -->
      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © PROFit <?php echo date('Y'); ?></span>
          </div>
        </div>
      </footer>
    </div>
    <!-- /.content-wrapper -->
  </div>

  <!-- /#wrapper -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- Logout Modal-->
  <div class="modal fade" id="SeriesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Серия снимков</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-header">Featured</div>
            <div class="card-body">
              <h5 class="card-title">Special title treatment</h5>
              <p class="card-text"><span id="bodKey">BodyPartExamined</span>:<span id="bodVal"></span></p>
              <p class="card-text"><span id="serKey">SeriesNumber</span>:<span id="serVal"></span></p>
              <a href="#" class="btn btn-primary"><?php echo $lng->view; ?></a>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="vendor/daterangepicker-master/moment.min.js"></script>
  <script src="js/datetime-moment.js"></script>
  <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.js"></script>
  <script>
    $(document).ready(function () {
      $('.sw[data-s="<?php echo $_SESSION['server_type']?>"]').addClass('active').addClass('focus');
      $(document).delegate('[data-series]' , 'click' , function () {
        $.ajax({
          type:'GET',
          url:'http://192.168.10.60:8080/dcm4chee-arc/aets/DCM4CHEE/rs/sudies',
          dataType:'json',
          // data:{SeriesID:$(this).data('series')},
          success:function(response) {
            console.log(response);
          }
        })
      });
    });
  </script>
</body>

</html>