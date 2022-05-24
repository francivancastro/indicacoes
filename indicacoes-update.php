<?php
add_thickbox();
function indicacoes_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "publicacao";
    $id = $_GET["id"];
    $numero = $_POST["numero"];
    $ano = $_POST["ano"];
    $tipo = $_POST["tipo"];
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];

    $data = str_replace("/", "-", $_POST["data"]);
    $dataUS = date('Y-m-d', strtotime($data));
    $data = date_format(date_create($dataUS), 'c');
    //var_dump($data);die();
    $ementa = $_POST["ementa"];
    $pdf = $_POST["pdf"];
//update

    $arquivo = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/wp-content/plugins/indicacoes/data.json');
    $rows = json_decode($arquivo);
    
    if (isset($_POST['update'])) {
        $rows->$id->id = $id;
        $rows->$id->numero = $numero;
        $rows->$id->ano = $ano;
        $rows->$id->tipo = $tipo;
        $rows->$id->autor = $autor;
        $rows->$id->data = $data;
        $rows->$id->ementa = $ementa;
        $rows->$id->pdf = $pdf;    
        $novojson = json_encode($rows);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/wp-content/plugins/indicacoes/data.json', $novojson);
    }
//delete
    else if (isset($_POST['delete'])) {
        unset($rows->$id);
        $novojson = json_encode($rows);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/wp-content/plugins/indicacoes/data.json', $novojson);
    } else {
        $s = $rows->$id;
        $numero = $s->numero;
        $ano = $s->ano;
        $tipo = $s->tipo;
        $titulo = $s->titulo;
        $autor = $s->autor;
        $data = $s->data;
        $ementa = $s->ementa;
        $pdf = $s->pdf;
    }
    ?>
<!---<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/indicacoes/style-admin.css" rel="stylesheet" />-->
<style>
select[readonly] {
    background: #eee;
    pointer-events: none;
    touch-action: none;
}

.containe-flex {
    display: flex;
}

.editable-container {
    background-color: #e5ffff;
    padding: 2px;
    border-radius: 10px;
}


.flip {
    display: inline-block;
    font-size: 30px;

    -webkit-transform: matrix(-1, 0, 0, 1, 0, 0);
    -moz-transform: matrix(-1, 0, 0, 1, 0, 0);
    -o-transform: matrix(-1, 0, 0, 1, 0, 0);
    transform: matrix(-1, 0, 0, 1, 0, 0);
}

.t-r {
    text-align: right;
}

.text-main td:nth-child(-n+4) {
    font-weight: bold;
}
</style>
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/poshytip/1.2/jquery.poshytip.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css"
    rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>


<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <?php if ($_POST['delete']) { ?>
    <div class="updated">
        <p>Excluido com Sucesso!</p>
    </div>
    <a href="<?php echo admin_url('admin.php?page=indicacoes_list') ?>">&laquo; Voltar</a>

    <?php } else if ($_POST['update']) { ?>
    <div class="updated">
        <p>Alterado com Sucesso!</p>
    </div>
    <a href="<?php echo admin_url('admin.php?page=indicacoes_list') ?>">&laquo; Voltar</a>

    <?php } else { ?>
    <div class="containe-flex">
        <div class="wrap">
            <h2>Publicações</h2>
            <div id="post-body-content" class="edit-form-section edit-comment-section">
                <div id="namediv" class="stuffbox">
                    <div class="inside" style="padding:5px">
                        <fieldset>
                            <legend class="screen-reader-text">Editar de Publicação</legend>
                            <table class='form-table'>
                                <tr>
                                    <th>Número</th>
                                    <td><input type="text" name="numero" value="<?php echo $numero; ?>" /></td>
                                </tr>
                                <tr>
                                    <th>Ano</th>
                                    <td><input type="text" name="ano" value="<?php echo $ano; ?>" /></td>
                                </tr>
                                <tr>
                                    <th>Tipo</th>
                                    <td>
                                        <select name="role" id="role">
                                            <option value="indicacao"
                                                <?php ($tipo == "indicacao") ? print "selected" : ""?>>
                                                Indicação
                                            </option>
                                            <option value="requerimento"
                                                <?php ($tipo == "requerimento") ? print "selected" : ""?>>
                                                Requerimento</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Título</th>
                                    <td><input type="text" name="titulo" value="<?php echo $titulo; ?>" /></td>
                                </tr>
                                <tr>
                                    <th>Autor</th>
                                    <td><input type="text" name="autor" value="<?php echo $autor; ?>" /></td>
                                </tr>
                                <tr>
                                    <th>Data</th>
                                    <td><input type="text" class="data" name="data" maxlength="10"
                                            onkeypress="mascaraData( this, event )"
                                            value="<?php echo date('d/m/Y', strtotime($data)); ?>" /></td>
                                </tr>
                                <tr>
                                    <th>Ementa</th>
                                    <td>
                                        <textarea name="ementa" id="ementa" rows="5"
                                            cols="100"><?php echo $ementa; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Url PDF</th>
                                    <td><input type="text" name="pdf" value="<?php echo $pdf; ?>" /></td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div id="major-publishing-actions">
                        <div id="delete-action">
                            <input type='submit' name="delete" value='Excluir' class='button'
                                onclick="return confirm('Deseja Realmente Excluir?')">
                        </div>
                        <div id="publishing-action">
                            <input type='submit' name="update" value='Salvar'
                                class='button button-primary button-large'>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="postbox-container-1">
            <h2>Andamentos</h2>
            <div id="submitdiv" class="">
                <div class="inside">
                    <div class="submitbox" id="submitcomment">
                        <table class="wp-list-table widefat fixed striped table-view-list" id="tb_andamento">
                            <thead>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Titulo</th>
                                <th>Data</th>
                                <th style="display:flex;text-align:center;justify-content: space-evenly;">
                                    <a id="{}" href=" #TB_inline?height=500&width=600&inlineId=andamento"
                                        class="thickbox button">Adicionar</a>
                                </th>
                            </thead>
                            <tbody id="container-andamento">
                                <?php if(isset($rows->$id->andamento)) {
                                    foreach($rows->$id->andamento as $ka => $andamento){
                                    ?>
                                <tr id="tr-andamento-<?= $ka; ?>" class="text-main">
                                    <td class="origem" data-name="origem" data-type='text' data-pk="col-<?= $ka; ?>">
                                        <?= $andamento->origem; ?></td>
                                    <td class="destino" data-name="destino" data-type='text' data-pk="col-<?= $ka; ?>">
                                        <?= $andamento->destino; ?></td>
                                    <td class="titulo" data-name="titulo" data-type='text' data-pk="col-<?= $ka; ?>">
                                        <?= $andamento->titulo; ?></td>
                                    <td id="databr" class="data" data-name="date" data-type="date"
                                        data-pk="col-<?= $ka; ?>">
                                        <?php echo date('d/m/Y', strtotime($andamento->date)); ?></td>
                                    <td style="text-align:right;">
                                        <div class="row-actions visible">
                                            <span class="activate">
                                                <a href="#TB_inline?height=300&width=400&inlineId=andamento"
                                                    class="edit thickbox">
                                                    Adicionar
                                                </a> |
                                            </span>
                                            <span class="delete">
                                                <a href="#TB_inline?height=300&width=400&inlineId=remove"
                                                    class="delete thickbox">Excluir</a>
                                            </span>
                                        </div>
                                    </td>

                                </tr>
                                <?php 
                                if(isset($andamento->sub)){
                                    foreach($andamento->sub as $ks => $sub){
                                ?>
                                <tr class="tr-andamento-<?= $ka; ?>" id="tr-andamento-<?= $ka; ?>-<?= $ks; ?>">
                                    <td class="origem" data-name="origem" data-type='text'
                                        data-pk="col-<?= $ka; ?>-<?= $ks; ?>">

                                        <?= $sub->origem; ?>
                                    </td>
                                    <td class="destino" data-name="destino" data-type='text'
                                        data-pk="col-<?= $ka; ?>-<?= $ks; ?>">
                                        <?= $sub->destino; ?></td>
                                    <td class="titulo" data-name="titulo" data-type='text'
                                        data-pk="col-<?= $ka; ?>-<?= $ks; ?>">
                                        <?= $sub->titulo; ?></td>
                                    <td class="data" data-name="date" data-type="date"
                                        data-pk="col-<?= $ka; ?>-<?= $ks; ?>">
                                        <?php echo date('d/m/Y', strtotime($sub->date)); ?></td>
                                    <td style="text-align:right;">
                                        <div class="row-actions visible">
                                            <span class="delete">
                                                <a href="#TB_inline?height=300&width=400&inlineId=remove"
                                                    class="delete thickbox">Excluir</a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <?php }}}} ?>
                            </tbody>
                        </table>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div><!-- /submitdiv -->
    </div>
    <?php } ?>
    </div>
</form>

<div id="andamento" style="display:none;">
    <form action="" id="andamentoForm">
        <table class='form-table'>
            <tr class="form-field form-required">
                <th>Origem</th>
                <td><input type="text" name="origem" value="" id="origem" class='obg txt_maiusculo' /></td>
            </tr>
            <tr class="form-field form-required">
                <th>Destino</th>
                <td><input type="text" name="destino" value="" id="destino" class='obg txt_maiusculo' /></td>
            </tr>
            <tr class="form-field form-required">
                <th>Titulo</th>
                <td><input type="text" name="titulo" value="" id="titulo" class='obg txt_maiusculo' /></td>
            </tr>
            <tr class="form-field form-required">
                <th>Data</th>
                <td><input type="text" onkeypress="mascaraData( this, event )" maxlength="10" name="data" value=""
                        id="data" class='obg data' /></td>
            <tr>
        </table>
        <a href="#" class="button" id="addAndamento">Adicionar</a>
        <div id="container-sub">

        </div>
    </form>
</div>

<div id="remove" style="display:none;">
    <h1>Deseja realmente excluir?</h1>
    <p>O registro será apagado permanente.</p>
    <div>
        <a href="#" id="removeItem" class="button">SIM</a>
        <a href="admin.php?page=indicacoes_update&id=<?= $id; ?>" class="button">NÃO</a>
    </div>
</div>
<script>
$(document).ready(function() {


    function edit_col(container, coluna, url, titulo, tipo) {
        $.fn.editable.defaults.mode = tipo;

        var obj = {
            selector: coluna,
            url: url,
            title: titulo,
            dataType: 'json',
            validate: function(value) {
                console.log(value);
                if ($.trim(value) == '') {
                    return 'Campo ' + titulo + ' não pode ser vazio!';
                }
            }
        }

        if (coluna == 'td.data') {
            obj.format = 'yyyy-mm-dd';
            obj.viewformat = 'dd/mm/yyyy';
            obj.datepicker = {
                weekStart: 1
            }
        }


        $(container).editable(obj);

        $.fn.editableform.buttons =
            '<button type="submit" class="button button-primary editable-submit">Salvar&nbsp;</button><button type="button" class="button editable-cancel">Cancelar&nbsp;</button>';

    }

    edit_col('#container-andamento', 'td.origem', 'admin.php?page=update_view&edit=<?= $id; ?>', 'Origem',
        'popup');
    edit_col('#container-andamento', 'td.destino', 'admin.php?page=update_view&edit=<?= $id; ?>',
        'Destino', 'popup');
    edit_col('#container-andamento', 'td.titulo', 'admin.php?page=update_view&edit=<?= $id; ?>', 'Titulo',
        'popup');
    edit_col('#container-andamento', 'td.data', 'admin.php?page=update_view&edit=<?= $id; ?>', 'Data',
        'popup');


    function limpaAndamentos() {
        $('#origem').val('');
        $('#destino').val('');
        $('#titulo').val('');
        $('#data').val('');
    }

    $('.thickbox').click(function() {
        $("#addAndamento").removeAttr('data-target');
        var idTr = $(this).parents('tr').attr('id');
        $("#addAndamento").attr("data-target", idTr);
        limpaAndamentos();
    });

    var lista = new Array();
    $(document).on('click', '#addAndamento', function() {

        var sub = $(this).attr('data-target');
        var sub_id = '';
        if (sub) {
            sub_id = '&andamento=' + sub.replace('tr-andamento-', '');
        }

        var flag = false;
        $('.obg').each(function() {
            var valor = $(this).val();
            if (!valor) {
                $(this).parents('.form-field').addClass('form-invalid');
                flag = true;
            } else {
                $(this).parents('.form-field').removeClass('form-invalid');
            }
        });
        if (flag === true) {
            return false;
        }

        var origem = $('#origem');
        var destino = $('#destino');
        var titulo = $('#titulo');
        var data = $('#data');

        var isoDateString = new Date(data.val()).toISOString();
        var obj = {
            'origem': origem.val(),
            'destino': destino.val(),
            'titulo': titulo.val(),
            'date': isoDateString
        };

        // console.log(obj);
        // return false;
        lista.push(obj);

        $.ajax({
            type: "POST",
            url: 'admin.php?page=update_view&add=<?= $id; ?>' + sub_id,
            data: {
                sub: lista
            },
            success: function(data) {
                var base = "admin.php?page=indicacoes_update&id=<?= $id; ?>";
                window.location.href = base;
            }
        });
    });

    function ajaxAction(action) {
        data = $("#frm_" + action).serializeArray();
        $.ajax({
            type: "POST",
            url: "admin.php?page=update_view",
            data: data,
            dataType: "json",
            success: function(response) {
                $("#container-conta").bootgrid('reload');
            }
        });
    }

    $(document).on('keyup', ".txt_maiusculo", function() {
        $(this).val($(this).val().toUpperCase());
    });

    $(function() {
        $(".data").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });

    $(".delete").click(function() {
        var idTr = $(this).parents('tr').attr('id');
        var itens = idTr.split('-');

        $("#removeItem").attr("data-target", idTr);
        var sub_id = '';
        if (itens[3]) {
            sub_id = '&sub=' + itens[3];
        }
        $("#removeItem").attr("href", 'admin.php?page=update_view&remove=<?= $id; ?>&andamento=' +
            itens[2] + sub_id);
    });

    $('#removeItem').on("click", function(e) {
        e.preventDefault();
        var sub = $(this).attr('data-target');
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {},
            success: function(data) {
                var base = "admin.php?page=indicacoes_update&id=<?= $id; ?>";
                window.location.href = base;
            }
        });
    });
});

function mascaraData(campo, e) {
    var kC = (document.all) ? event.keyCode : e.keyCode;
    var data = campo.value;

    if (kC != 8 && kC != 46) {
        if (data.length == 2) {
            campo.value = data += '/';
        } else if (data.length == 5) {
            campo.value = data += '/';
        } else
            campo.value = data;
    }
}
</script>

<?php
}