<div class="content cat_list">

    <section>
        <h1>Categorias:</h1>
        <?PHP
        $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
        if ($empty):
            MGErro("Você tentou, sem sucesso, remover ou editar um registro inexistente.", MG_ERROR);
        endif;
        ?>
        <?php 

        ?>
            <section>
                <header>
                    <h1>Vídeo aulas:  <span>( 21 posts ) ( 3 Categorias )</span></h1>
                    <p class="tagline">Venha aprender a criar sites profissionais com cursos gratuitos e video aulas de AJAX, PHP, WORDPRESS, TABLELESS, JQUERY e muito mais!</p>

                    <ul class="info post_actions">
                        <li><strong>Data:</strong> <?= date('d/m/Y H:i'); ?>Hs</li>
                        <li><a class="act_view" target="_blank" href="painel.php?exe=posts/post&id=ID_DO_POST" title="Ver no site">Ver no site</a></li>
                        <li><a class="act_edit" href="painel.php?exe=posts/post&id=ID_DO_POST" title="Editar">Editar</a></li>
                        <li><a class="act_delete" href="painel.php?exe=posts/post&id=ID_DO_POST" title="Excluir">Deletar</a></li>
                    </ul>
                </header>

                <h2>Sub categorias de vídeo aulas:</h2>

                <?php 
                    $a = 1;
                ?>
                    <article<?php if ($a % 3 == 0) echo ' class="right"'; ?>>
                        <h1><a target="_blank" href="../categoria/uri-da-categoria" title="Ver Categoria">Programação com PHP:</a>  ( 7 posts )</h1>

                        <ul class="info post_actions">
                            <li><strong>Data:</strong> <?= date('d/m/Y H:i'); ?>Hs</li>
                            <li><a class="act_view" target="_blank" href="painel.php?exe=posts/post&id=ID_DO_POST" title="Ver no site">Ver no site</a></li>
                            <li><a class="act_edit" href="painel.php?exe=posts/post&id=ID_DO_POST" title="Editar">Editar</a></li>
                            <li><a class="act_delete" href="painel.php?exe=posts/post&id=ID_DO_POST" title="Excluir">Deletar</a></li>
                        </ul>
                    </article>
                <?php  ?>

            </section>
        <?php  ?>

        <div class="clear"></div>
    </section>

    <div class="clear"></div>
</div> <!-- content home -->