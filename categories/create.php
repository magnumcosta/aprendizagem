<?php
if (!class_exists('sessao')):
    header('location:../../painel.php');
    die;
endif;
?> 

<div class="form_create">  
    <h2>Criar Categoria:</h2>    
    <?php
    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $catid = filter_input(INPUT_GET,'catId',FILTER_VALIDATE_INT);
    if (isset($data['SendPostForm'])):
        unset($data['SendPostForm']);
        require 'models_admin/AdminCategory.class.php';
        $cadastra = new AdminCategory;
        $cadastra->ExeCreate($data);
        if (!$cadastra->getResult()):
            MGErro($cadastra->getError()[0], $cadastra->getError()[1]);
        Else:
            header('location:painel.php?exe=categories/update&create=true&catId=' . $cadastra->getResult());
        endif;
    endif;
    ?>
    <form class="form-group mb-3" name="PostForm" id="PostForm" action="" method="post" enctype="multipart/form-data">

        <!-- Label/Rotulo value: títulos txt -->
        <div>            
                <span class="field">Titulo:</span>
                <input class="form-control" resize="none" type="text" name="category_title" value="<?php if (isset($data)) echo $data['category_title']; ?>" />            
        </div>
        <!-- Label/Rotulo value: Conteúdo txt -->
        <div>            
            <span class="field">Conteúdo:</span>
            <textarea class="form-control" name="category_content" rows="3"><?php if (isset($data)) echo $data['category_content']; ?></textarea>          
        </div>
        <!-- Label/Rotulo value: Data.date e Seção.listaDeCampos.banco -->
        <div class="label_line">

            <label class="label_small">
                <span class="field">Data:</span>
                <input type="text" class="form-data form-control" name="category_date" value="<?php date('d/m/Y H:i:s'); ?>" />
            </label>

            <label class="label_small left">
                <span class="field">Sessão:</span>

                <select name="category_parent" >
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

            </label>
        </div>

        <div class="gbform">
        <input type="submit" class="btn btn-sm btn-primary" value="Criar Categoria" name="SendPostForm" />
</div>