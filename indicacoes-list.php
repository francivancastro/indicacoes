<?php

function indicacoes_list() {

    if (isset($_GET['sync'])) {
        $teste =  shell_exec("cd  ". $_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/indicacoes/ && ./teste-linux");
        echo $teste;
    }

    $arquivo = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/wp-content/plugins/indicacoes/data.json');
    $rows = json_decode($arquivo);
?>
<link type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#publicacoes').DataTable({
        bLengthChange: false,
        bInfo: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        }
    });

    $('.btn-hidden').click(function() {
        var id = $(this).attr('id');
        $("span", this).toggleClass("dashicons-visibility dashicons-hidden");

        var el = $("div", this);
        el.text(el.text() == 'Exibir' ? 'Ocultar' : 'Exibir');
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: 'admin.php?page=update_view&id=' + id,
            async: true,
            success: function(response) {
                console.log(response);
            }
        });
    });
});
</script>
<div class="wrap">
    <h2>Publicações</h2>
    <div class="tablenav top">
        <div class="alignleft actions">
            <!--
            <a href="<?php echo admin_url('admin.php?page=indicacoes_create'); ?>" class="button">Adicionar</a>
            -->
            <a href="<?php echo admin_url('admin.php?page=indicacoes_list&sync=active'); ?>"
                class="button">Sincronizar</a>
        </div>
        <br class="clear">
    </div>
    <?php
        global $wpdb;
    ?>
    <table id="publicacoes" class="wp-list-table widefat fixed striped posts" style="width:100%">
        <thead>
            <tr>
                <th class="manage-column">Título</th>
                <th class="manage-column">Autor</th>
                <th class="manage-column">Data</th>
                <th class="manage-column">Ementa</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $key => $row) { ?>
            <tr>
                <td class="manage-column"><?php echo $row->titulo; ?></td>
                <td class="manage-column"><?php echo $row->autor; ?></td>
                <td class="manage-column"><?php echo date('d/m/Y', strtotime($row->data)); ?></td>
                <td class="manage-column"><?php echo mb_strimwidth($row->ementa, 0, 50, "..."); ?></td>
                <td style="display:flex;text-align:center;justify-content: space-evenly;">
                    <a href="<?php echo $row->pdf; ?>" target="_blank"><span
                            class="dashicons dashicons-media-text"></span>
                        <div>PDF</div>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=indicacoes_update&id=' . $row->id); ?>"><span
                            class="dashicons dashicons-edit-page"></span>
                        <div>Editar</div>
                    </a>
                    <a href="#" id="<?php echo $row->id; ?>" class="btn-hidden">
                        <span
                            class="dashicons dashicons-<?php (!$row->hidden) ? print "visibility" : print "hidden" ?>"></span>
                        <div><?php (!$row->hidden) ? print "Ocultar" : print "Exibir" ?></div>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
}