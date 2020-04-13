<?php require_once './layout.header.php';?>
<h1 class="mt-4">Организации</h1>

<div class="card mb-4">
    
    <div class="card-body ">
        <div class="table-responsive">
            <button class="btn btn-sm btn-primary mb-4"  data-toggle="modal" data-target="#orgmodal"><i class="fa fa-plus" aria-hidden="true"></i> Создать</button>
            <button id="delorgs" style="display:none" class="btn btn-sm btn-danger mb-4" ><i class="fa fa-trash"></i> Удалить</button>
            <table id="orgs" class="table-sm table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width=10></th>
                        <th>Наименование</th>
                        <th>Тег</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    require "../connection.php";
                    $stmt = $conn->query("SELECT o.id as oid , o.name, o.param  FROM organization as o");
                    $row = $stmt->fetchAll();
                    $stmt->closeCursor();
                    
                    foreach($row as $r) {
                        $str = "";
                        echo "<tr data-id='".$r['oid']."'>";
                        echo "<td><input data-id='".$r['oid']."' class='dels' name='dels' type='checkbox'></td>";
                        echo "<td class='name editorg' data-id='".$r['oid']."'>".$r['name']."</td>";
                        echo "<td class='param editorg' data-id='".$r['oid']."'>".$r['param']."</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                        
<!-- Modal -->
<div class="modal fade" id="orgmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить организацию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Наименование <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control v" placeholder="" aria-describedby="helpId">
                    <small id="namesm" class="text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="tag">Тег <span class="text-danger">*</span></label>
                    <input type="text" class="form-control v" name="tag" id="tag" aria-describedby="helpId" placeholder="">
                    <small id="tagsm" class="form-text text-muted"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="saveorg" class="btn btn-primary">Сохранить</button>
           </div>
        </div>
    </div>
</div>

<div class="modal fade" data-id="0"  id="eorgmodal" tabindex="-1" role="dialog" aria-labelledby="emodelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать организацию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ename">Наименование</label>
                    <input type="text" name="elname" id="ename" class="form-control v" placeholder="" aria-describedby="helpId">
                    <small id="enamesm" class="text-muted"></small>
                </div>
                <div class="form-group">
                    <label for="eparam">Тег</label>
                    <input type="text" class="form-control v" name="eparam" id="eparam" aria-describedby="helpId" >
                    <small id="epatramsm" class="form-text text-muted"></small>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="esaveorg" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<?php require_once './layout.footer.php';?>
                    