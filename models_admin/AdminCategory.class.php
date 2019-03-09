<?php

/**
 *  AdminCategory.class [Model Admin]
 *  Responsável por gerenciar as categorias no Admin
 * @copyright (c) year, Magnum Treinamento ltda
 */
class AdminCategory {

    private $Data;
    private $catId;
    private $Error;
    private $Result;
    
    const Entity= 'ws_categories';
    
    public function ExeCreate(array $data) {
        $this->Data = $data;
        
        if (in_array('', $this->Data)): //in_array procura um determinado valor dentro do array;            
            $this->Result = false;
            $this-> Error = ['erro ao cadastrar: preencha os campos corretamente',MG_ALERT];
        else:
            $this->setData();
            $this->checkName();
            $this->create();
        endif;
    }
    
    public function ExeUpdate($catId ,array $data) {
        $this->catId = (int) $catId;
        $this->Data = $data;
        if (in_array('', $this->Data)): //in_array procura um determinado valor dentro do array;
            $this->Result = false;
            $this-> Error = ["<b>Erro ao atualizar a categoria ({$this->Data['category_title']}):</b> Por favor, preencha os campos corretamente.",MG_ALERT];
        else:
            $this->setData();
            $this->checkName();
            $this->update();
        endif;
    }
    function getError() {
        return $this->Error;
    }

    function getResult() {
        return $this->Result;
    }
    /*PRIVADOS*/
    //Validação dos campos
    private function setData() {
        $this->Data = array_map('strip_tags',$this->Data);
        $this->Data = array_map('trim',$this->Data);
        $this->Data['category_name'] = check::Name($this->Data['category_title']);
        $this->Data['category_date'] = check::Date($this->Data['category_date']);
        $this->Data['category_parent'] = ($this->Data['category_parent'] === 'null' ? null : $this->Data['category_parent']);
    }
    private function checkName() {
        $where = (!empty($this->catId) ? "category_id !={$this->catId} AND" : '');
        $read = new Leitura;
        $read->ExeRead(self::Entity, "WHERE {$where} category_title = :t", "t={$this->Data['category_title']}");
        if ($read->getResult()) :
            $this->Data['category_name'] = $this->Data['category_name'].'-'. $read->getRowCount();
        endif;
    }
    
    private function create() {
        $create = new Createe();
        $create->ExeCreate(self::Entity, $this->Data);
        if ($create->getResult()):
            $this->Result = $create->getResult();
            $this->Error = ["A categoria <b>({$this->Data['category_title']})</b> foi cadastrada com sucesso",MG_ACCEPT];
        endif;
    }
    
    private function update() {
        $update = new Update;
        $update->ExeUpdate(self::Entity, $this->Data, "WHERE category_id= :id", "id={$this->catId}");
        
        if ($update->getResult()):
            $this->Result = true;
            $this->Error = ["A categoria <b>({$this->Data['category_title']})</b> foi atualizada com sucesso",MG_ACCEPT];
        endif;
        
    }
    
    
}
