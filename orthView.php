<?php
session_start();
// $_SESSION['server_type'] = 1;
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
$req = array('Level' => 'Study', 'Limit' => 100, 'Query' => $query);
$studyes = $db->findOrthankData($req);
$lastdate = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Cache-Control" content="no-cache" />
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
                                        echo $db->getStudies($studyes[0])->MainDicomTags->InstitutionName;
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
                  <?php if ($studyes) : ?>
                    <?php for ($i = 0; $i < count($studyes); $i++) : ?>
                      <?php $study = $db->getStudies($studyes[$i]); ?>
                      <?php $series = json_encode($study->Series, JSON_UNESCAPED_UNICODE); ?>
                      <tr>
                        <th><?php echo $study->PatientMainDicomTags->PatientName; ?></th>
                        <th><?php echo $study->PatientMainDicomTags->PatientID; ?></th>
                        <th><?php echo $study->PatientMainDicomTags->PatientSex; ?></th>
                        <th><?php echo $study->PatientMainDicomTags->PatientBirthDate; ?></th>
                        <th><?php echo $study->PatientMainDicomTags->PatientBirthDate; ?></th>
                        <th><?php echo $db->prettyDate($study->MainDicomTags->StudyDate, "-"); ?></th>
                        <th><?php echo $study->MainDicomTags->StudyDescription; ?></th>
                        <th><a  class="series" data-series="<?php echo $series; ?>" data-toggle="modal" data-target="#SeriesModal">Серия</a></th>
                        <th><a target="_blank" href="<?php echo $db->getViewLink("study", $study->ID); ?>">Просмотр</a></th>
                      </tr>

                    <?php endfor; ?>
                  <?php endif; ?>
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
    })
  </script>
</body>

</html>