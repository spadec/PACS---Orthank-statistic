
<?php require_once './layout.header.php' ?>                     <!-- <h1 class="mt-4">Панель администратора</h1> -->
<h1 class="mt-4">Пользователи dcm4chee</h1>

<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <button class="btn btn-sm btn-primary mb-4"  data-toggle="modal" data-target="#modal"><i class="fa fa-plus" aria-hidden="true"></i> Создать</button>
            <button id="del" style="display:none" class="btn btn-sm btn-danger mb-4" ><i class="fa fa-trash"></i> Удалить</button>
            <table id="users" class="table-sm table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width=10></th>
                        <th>Логин</th>
                        <th>Организации</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    require "../connection.php";
                    $stmt = $conn->query("SELECT u.id as uid , u.login, u.role  FROM users as u where role>0");
                    $row = $stmt->fetchAll();
                    $stmt->closeCursor();
                    
                    foreach($row as $r) {
                        $str = "";
                        echo "<tr  data-id='".$r['uid']."'>";
                        echo "<td><input data-id='".$r['uid']."' class='dels' name='dels' type='checkbox'></td>";
                        echo "<td class='login edit' data-id='".$r['uid']."'>".$r['login']."</td>";
                        $stm1 = $conn->query("SELECT o.id , o.name  FROM user_org as uo INNER JOIN organization as o ON o.id = uo.org_id WHERE uo.user_id = $r[uid]");
                        $org = $stm1->fetchAll();
                        echo "<td class='orgs edit' data-id='".$r['uid']."'>";
                        foreach($org as $o) {
                            $str= $str.$o['name'].", ";
                        }

                        echo $str;
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                        
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить пользователя</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="login">Логин <span class="text-danger">*</span></label>
                    <input type="text" name="login" id="login" class="form-control v" placeholder="" aria-describedby="helpId">
                    <small id="loginsm" class="text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="password">Пароль <span class="text-danger">*</span></label>
                    <input type="password" class="form-control v" name="password" id="password" aria-describedby="helpId" placeholder="">
                    <small id="passwordsm" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="org">Организация <span class="text-danger">*</span></label>
                    <select multiple class="form-control v-s" name="org" id="org">
                    <?php  
                        $stmt = $conn->query("SELECT o.id as oid , o.name as oname FROM organization as o");
                        $row = $stmt->fetchAll();
                        foreach($row as $r) {
                            echo "<option value=".$r['oid'].">".$r['oname']."</option>";
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="saveuser" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" data-id="0"  id="emodal" tabindex="-1" role="dialog" aria-labelledby="emodelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать пользователя</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                <label for="elogin">Логин</label>
                <input type="text" name="elogin" id="elogin" class="form-control v" placeholder="" aria-describedby="helpId">
                <small id="eloginsm" class="text-muted"></small>
                </div>
                <div class="form-group">
                <label for="epassword">Пароль</label>
                <input type="password" class="form-control" placeholder="Новый пароль" name="epassword" id="epassword" aria-describedby="helpId" placeholder="">
                <small id="epasswordsm" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                <label for="eorg">Организация</label>
                <select multiple class="form-control" name="eorg" id="eorg">
                    <?php  
                        $stmt = $conn->query("SELECT o.id as oid , o.name as oname FROM organization as o");
                        $row = $stmt->fetchAll();
                        foreach($row as $r) {
                            echo "<option value=".$r['oid'].">".$r['oname']."</option>";
                        }
                    ?>
                </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="esaveuser" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<?php require_once './layout.footer.php' ?>    