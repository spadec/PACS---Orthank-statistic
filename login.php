<?php
    require "./connection.php";
    ob_start();
    session_start();
    if(isset($_POST['submit'])) {
        $login = $_POST['login'];
        $pass = $_POST['pass'];

        $stmt = $conn->query("SELECT login , password ,role FROM users WHERE login='$login' LIMIT 1");
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $isLogin = password_verify($pass , $row['password']);
        if($isLogin){
            $_SESSION['user'] = $login;
            if($row['role']=='0') {
                $_SESSION['admin'] = "adminuser";
                header('location: ./admin/index.php');
            }
            else {
                header('location: ./index.php');
            }
        } else {
            echo '<div class="alert alert-danger">';
                echo '<p class="text-center">Неверный логин или пароль</p>';
            echo '</div>';
        }
    }   
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>antis:PACS - login</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Вход</h3></div>
                                    <div class="card-body">
                                        <form method="POST">
                                            <div class="form-group"><label class="small mb-1" for="inputEmailAddress">Логин</label><input required class="form-control py-4" id="inputEmailAddress" name="login" type="text" placeholder="" /></div>
                                            <div class="form-group"><label class="small mb-1" for="inputPassword">Пароль</label><input required class="form-control py-4" id="inputPassword"  name="pass" type="password" placeholder="" /></div>
                                            <div class="form-group">
                                                <!-- <div class="custom-control custom-checkbox"><input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" /><label class="custom-control-label" for="rememberPasswordCheck">Remember password</label></div> -->
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0"><input type="submit" name="submit" class="btn btn-primary" value="Login"/></div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <!-- <div class="small"><a href="register.html">Need an account? Sign up!</a></div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright © PROFit <?php echo date('Y'); ?></span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
