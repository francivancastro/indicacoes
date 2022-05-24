<?php

    //add_action('wp_loaded', 'redirecionar_url');


    // redirecionar_url($url){
    //     wp_redirect($url);
    //     exit();
    // }

    function update_view() {

        $params = $_POST;
        
        $arquivo = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/indicacoes/data.json');
        
        $rows = json_decode($arquivo);

        if (isset($_GET['id'])) {
            $id = $_GET["id"];
            if(!$rows->$id->hidden){
                $rows->$id->hidden = true;
            } else {
                unset($rows->$id->hidden);
            }
            $novojson = json_encode($rows);
        }
        
        if (isset($_GET['edit'])) {
            $id = $_GET["edit"];
            $pk = explode('-', $params["pk"]);
            $name = $params["name"];
            if($name == 'data'){
                $data = str_replace("/", "-", $datateste);
                $dataUS = date('Y-m-d', strtotime($data));
                $params["value"] =  date_format(date_create($dataUS), 'c');
            }
            if(isset($pk[2])){
                $rows->$id->andamento[$pk[1]]->sub[$pk[2]]->$name = $params["value"];
            } else {
                $rows->$id->andamento[$pk[1]]->$name = $params["value"];
            }
            
            $novojson = json_encode($rows);
        }

        if (isset($_GET['add'])) {
            $id = $_GET["add"];
            $andamento = $_GET['andamento'];

            if(isset($andamento)){
                $rows->$id->andamento[$andamento]->sub[] = $params['sub'][0];
            } else {
                $rows->$id->andamento[] = $params['sub'][0];    
            }            
            $novojson = json_encode($rows);
        }

        if (isset($_GET['remove'])) {
            $id = $_GET["remove"];
            $andamento = $_GET['andamento'];
            $sub = $_GET['sub'];

            //var_dump($rows->$id->andamento[$andamento]->sub, '<br>');

            if(isset($sub)){
                $contSub = count(($rows->$id->andamento[$andamento]->sub));
                if($contSub == 1){
                    unset($rows->$id->andamento[$andamento]->sub);
                } else {
                    unset($rows->$id->andamento[$andamento]->sub[$sub]);
                    $rows->$id->andamento[$andamento]->sub = array_values($rows->$id->andamento[$andamento]->sub);
                }
               
            } else {
                unset( $rows->$id->andamento[$andamento]);
                $rows->$id->andamento = array_values($rows->$id->andamento);
            }

            $novojson = json_encode($rows);
        }
        
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/indicacoes/data.json', $novojson);
    }
?>