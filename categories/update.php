<?php
if (!class_exists('sessao')):
    header('location:../../painel.php');
    die;
endif;
?> 

<div class="container form_create">
    <article>
        <h3>Atualizar Categoria:</h3>
        <?php
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $catId = filter_input(INPUT_GET, 'catId', FILTER_VALIDATE_INT);
        if (!empty($data['SendPostForm'])):
            unset($data['SendPostForm']);
            require 'models_admin/AdminCategory.class.php';
            $cadastra = new AdminCategory;
            $cadastra->ExeUpdate($catId, $data);
            MGErro($cadastra->getError()[0], $cadastra->getError()[1]);
        else:
            $read = new Leitura;
            $read->ExeRead('ws_categories', 'WHERE category_id=:id', "id={$catId}");
            if (!$read->getResult()):
                header('location:painel.php?exe=categories/index&empty=true');
            else:
                $data = $read->getResult()[0];
            endif;
        endif;
        $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
        if ($checkCreate):
            $tipo = (empty($data['category_parent']) ? 'sessão' : 'Categoria');
            MGErro("A <b>{$tipo}: ({$data['category_title']})</b> foi cadastrada com sucesso.", MG_ACCEPT);
        endif;
        ?>
        <form class="form-group" name="PostForm" id="PostForm" action="" method="post" enctype="multipart/form-data">
            <!-- Label/Rotulo value: títulos txt -->
            <label class="label">
                <span class="field">Titulo:</span>
                <input class="form-control" resize="none" type="text" name="category_title" value="<?php if (isset($data)) echo $data['category_title']; ?>" />
            </label><br><br>

            <!-- Label/Rotulo value: Conteúdo txt -->
            <label class="label">
                <span class="field">Conteúdo:</span><br>
                <textarea class="form-control" name="category_content" rows="5"><?php if (isset($data)) echo $data['category_content']; ?></textarea>
            </label><br>
            <!-- Label/Rotulo value: Data.date e Seção.listaDeCampos.banco -->
            <div class="label_line">

                <label class="label_small"><br>
                    <span class="field">Data:</span><br>
                    <input type="text" class="form-data form-control" name="category_date" value="<?php date('d/m/Y H:i:s'); ?>" />
                </label><br>

                <label class="label_small left"><br>
                    <span class="field">Sessão:</span><br>

                    <select name="category_parent" ><br>
                        <option value='null'> Selecione a Sessão: </option>
<?php
$readses = new Leitura;
$readses->ExeRead("ws_categories", "WHERE category_parent IS NULL ORDER BY category_title ASC");
if (!$readses->getResult()):
    echo '<option disabled="disable" value="null"> Cadastre antes uma sessão: </option>';
else:
    foreach ($readses->getResult() as $ses):
        echo "<option value=\"{$ses['category_id']}\" ";
        if ($data['category_parent'] == $ses['category_id']):
            echo ' selected = \"selected\" ';
        endif;
        echo "> {$ses['category_title']} </option>";
    endforeach;
endif;
?>
                    </select>

                </label><br><br>
            </div>

            <div class="gbform"></div>
            <input type="submit" class="btn btn-sm btn-warning" value="Atualizar Categoria" name="SendPostForm" />
            </div>    


