<?php

function indicacoes_create() {
    $id = $_POST["id"];
    $numero = $_POST["numero"];
    $ano = $_POST["ano"];
    $tipo = $_POST["tipo"];
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    
    
    $data = str_replace("/", "-", $_POST["data"]);
    $dataUS = date('Y-m-d', strtotime($data));
    $data = date_format(date_create($dataUS), 'c');

    $ementa = $_POST["ementa"];
    $pdf = $_POST["pdf"];

    $arquivo = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/wp-content/plugins/indicacoes/data.json');
    $rows = json_decode($arquivo);

    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $dados = array(
            'id' => $id,
            'numero' => $numero,
            'ano' => $ano,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'autor' => $autor,
            'data' => $data,
            'ementa' => $ementa,
            'pdf' => $pdf
        );  
        $rows->$id = (object) $dados;
        $novojson = json_encode($rows);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/wp-content/plugins/indicacoes/data.json', $novojson);
        
        $message.="Salvo com Sucesso";
    }
    ?>
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/poshytip/1.2/jquery.poshytip.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css"
    rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <div class="wrap">
        <h2>Adicionar Nova Publicação</h2>
        <?php if (isset($message)): ?><div class="updated">
            <p><?php echo $message; ?></p>
        </div><?php endif; ?>

        <div id="post-body-content" class="edit-form-section edit-comment-section">
            <div id="namediv" class="stuffbox" style="padding:5px">
                <div class="inside">
                    <fieldset>
                        <legend class="screen-reader-text">Editar de Publicação</legend>
                        <table class='form-table'>
                            <tr>
                                <th>ID</th>
                                <td><input type="text" name="id" value="<?php echo $id; ?>" /></td>
                            </tr>
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
                                            <?php ($tipo == "indicacao") ? print "selected" : ""?>>Indicação</option>
                                        <option value="requerimento"
                                            <?php ($tipo == "requerimento") ? print "selected" : ""?>>Requerimento
                                        </option>
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
                                <td><input id="data-criacao" type="text" name="data" onkeypress="mascaraData( this, event )" maxlength="10" class="obg data" /></td>
                            </tr>
                            <tr>
                                <th>Ementa</th>
                                <td>
                                    <textarea name="ementa" id="ementa" rows="5"
                                        cols="30"><?php echo $ementa; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>Url PDF</th>
                                <td><input type="text" name="pdf" value="<?php echo $pdf; ?>" /></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </div>
            <input type='submit' name="insert" value='Salvar' class="button button-primary">
        </div>
    </div>
</form>
<script>
$(document).ready(function() {

    $(document).on('keyup', ".txt_maiusculo", function() {
        $(this).val($(this).val().toUpperCase());
    });

    $(function() {
        $(".data").datepicker({
            dateFormat: 'dd/mm/yy',
            maxDate:0
        });
    });

    $('#data-criacao').blur(function(){
        var strdate = $(this).val();
        if(strdate != ''){
            var date1 = new Date(formatUs(strdate));
            var date2 = new Date();
            if(date1 > date2){
                $(this).val('');
            }
        }
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

function formatUs(data) {
  var dia  = data.split("/")[0];
  var mes  = data.split("/")[1];
  var ano  = data.split("/")[2];

  return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
}
</script>
<?php
}