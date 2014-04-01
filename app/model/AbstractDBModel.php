<?php

namespace app\model;

abstract class AbstractDBModel implements DBModel {

    /**
     *
     * @var \stdClass
     */
    private $data;
    
    /**
     *
     * @var \FluentPDO
     */
    private $fpdo;
    
    public function __construct() {
        $this->fpdo = new \FluentPDO(\app\db\DataBase::getInstance());
    }
    
    /**
    * @return \SelectQuery
    */
    public function select() {
        return $this->fpdo->from($this->getTable())->asObject(get_class($this));
    }
    
    /**
     * 
     * @return \DeleteQuery
     */
    public function deleteFrom() {
        return $this->fpdo->deleteFrom($this->getTable())->asObject(get_class($this));
    }
    /**
     * 
     * @return \stdClass jedini property je COUNT
     */
    public function countQuery() {
        return $this->fpdo->from($this->getTable())->select(null)->select('COUNT(*) AS COUNT');
    }
    
    /**
     * Vrši update ili insert s raspoloživim podacima u modelu
     */
    public function save() {
        $columns = $this->getColumns();
        
        if (null === $this->getPrimaryKey()) {
            // insert -> kljuc ne postoji (ne moze ga korisnik rucno zadati)
            $values = array();
            
            foreach($columns as $singleColumn) {
                $values[$singleColumn] = $this->$singleColumn;
            }
            
            $this->fpdo->insertInto($this->getTable(), $values)->execute();
            
            //oznaci da je podatak "ziv"
            $this->data->{$this->getPrimaryKeyColumn()} = \app\db\DataBase::getInstance()->lastInsertId();
        } else {
            // vrsimo update (jer vec znam primarni ključ)
            $values = array();
            
            foreach($columns as $singleColumn) {
                $values[$singleColumn] = $this->$singleColumn;
            }
            
            $this->fpdo->update($this->getTable(), $values, array(
               $this->getPrimaryKeyColumn() => $this->getPrimaryKey() 
            ))->execute();
        }  
    }
    
    
    /**
     * Učitava iz baze zapis s zadanim id-em
     * 
     * @param mixed $primaryKey
     * @throws NotFoundException
     */
    public function load($primaryKey) {
        $this->data = $this->fpdo->from($this->getTable())
                            ->where(array(
                                $this->getPrimaryKeyColumn() => $primaryKey
                            ))->fetch();
        if (false === $this->data) {
            throw new NotFoundException('Nothing found with ID ' . $primaryKey);
        }
    }
    
    /**
     * Briše zapis iz baze
     * 
     * @return void
     */
    public function delete() {
        if (null === $this->getPrimaryKey()) {
            return;
        }
        //brisi iz baze
        $this->fpdo->delete($this->getTable(), array(
            $this->getPrimaryKeyColumn() => $this->getPrimaryKey()
        ))->execute();
        
        // oznaci da si obrisao
        $this->data->{$this->getPrimaryKeyColumn()} = null;
    }

    /**
     * Provjerava jesu li objekti jednaki
     * 
     * @param app\model\Model $model
     * @return boolean
     */
    public function equals(Model $model) {
        if (get_class($this) !== get_class($model)) {
            return false;
        }
        
        if ($this->getPrimaryKey() !== $model->getPrimaryKey()) {
            return false;
        }
        
        foreach ($this->data as $key => $value) {
            if ($value !== $model->$key) {
                return false;
            }
        }
        
        return true;
    }
    
    public function serialize() {
        return serialize($this->data);
    }
    
    public function unserialize($serialized) {
        $this->data = unserialize($serialized);
        $this->fpdo = new \FluentPDO(\app\db\DataBase::getInstance());
    }
    
    /**
     * 
     * @return mixed    vrijednost primarnog ključa
     */
    public function getPrimaryKey() {
        return $this->{$this->getPrimaryKeyColumn()};
    }
    
    /**
     * 
     * @return string naziv atributa koji je primarni ključ
     */
    public abstract function getPrimaryKeyColumn();
    
    /**
     * 
     * @return  string naziv relacije
     */
    public abstract function getTable();
    
    /**
     * Vraca nazive svih neključnih atributa relacije
     * 
     * @return array
     */
    public abstract function getColumns();
    
    public function __get($name) {
        if (isset($this->data->$name)) {
            return $this->data->$name;
        } else {
            return null;
        }
    }
    
    public function __set($name, $value) {
        if (null === $this->data) {
            $this->data = new \stdClass();
        }
        
        if ($this->getPrimaryKeyColumn() === $name || in_array($name, $this->getColumns())) {
            return $this->data->$name =$value;
        } else {
            return null;
        }
    }
    
}