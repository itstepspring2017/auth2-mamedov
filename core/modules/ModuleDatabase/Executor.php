<?php

namespace ModuleDatabase;
use \PDO;
use \PDOStatement;

class Executor{
    private $t;
    private $dbh;
    private $components = [
        "where"=>[],
        "having"=>[],
        "order"=>[],
        "limit"=>NULL,
        "offset"=>NULL,
        "fields"=>NULL,
        "join"=>[],
        "groupby"=>NULL
    ];

    public function __construct(PDO $dbh,string $t){
        $this->t = $t;
        $this->dbh = $dbh;
    }

    private function _execute(string $q,array $params):PDOStatement{
        $stmt = $this->dbh->prepare($q);
        $stmt->execute($params);
        return $stmt;
    }

    public function insert(array $data):int {
        $names = array_keys($data);
        $q = "INSERT INTO `{$this->t}` (`".implode("`,`",$names)."`) VALUES (:".implode(",:",$names).")";
        $this->_execute($q,$data);
        return $this->dbh->lastInsertId();
    }

    public function getAll(string $where="1",array $data=[]):array {
        return $this->_execute("SELECT * FROM `{$this->t}` WHERE {$where}",$data)->fetchAll();
    }

    public function getOne(string $where="1",array $data=[]){
        return $this->_execute("SELECT * FROM `{$this->t}` WHERE {$where}",$data)->fetch();
    }

    public function get(int $id){
        return $this->getOne("id=?",[$id]);
    }

    public function update(int $id,array $data=[]):void{
        $_data = array_map(function ($elem){
            return "`{$elem}`=:{$elem}";
        },array_keys($data));
        $this->_execute("UPDATE `{$this->t}` SET ".implode(", ",$_data)." WHERE id={$id}",$data);
    }

    public function deleteWhere(string $where,array $data=[]):void {
        $this->_execute("DELETE FROM`{$this->t}` WHERE {$where}",$data);
    }

    public function delete(int $id):void{
        $this->deleteWhere("`id`=?",[$id]);
    }

    public function countOf(string $where="1",array $data=[]):int {
        return (int)$this->_execute("SELECT COUNT(*) FROM `{$this->t}` WHERE {$where}",$data)->fetchColumn();
    }

    private static function _field($field){
        return "`".str_replace(".","`.`",$field)."`";
    }

    private function _join($table,$field_far,$field="id",$cur_table=NULL,$type="INNER"){
        $cur_table = $cur_table === NULL ? $this->t : $cur_table;

        $field = "`{$cur_table}`.`{$field}`";
        $field_far = "`{$table}`.`{$field_far}`";
        $on = "({$field}={$field_far})";

        $this->components["join"][] = [$type,$table,$on];
        return $this;
    }

    public function join($table,$far_fild,$fild="id",$cur_table=NULL){
        return $this->_join($table,$far_fild,$fild,$cur_table);
    }

    public function joinLeft($table,$far_fild,$fild="id",$cur_table=NULL){
         return $this->_join($table,$far_fild,$fild,$cur_table,"LEFT");
    }

    public function joinRight($table,$far_fild,$fild="id",$cur_table=NULL){
         return $this->_join($table,$far_fild,$fild,$cur_table,"RIGHT");
    }

    private function _where($type,$field,$sign,$value=NULL,bool $native=FALSE){
        if($value === NULL){
            $value = $sign;
            $sign = "=";
        }
        if(!is_int($value) && $value[0]!=":" && $value!="?" && !$native){
            $value = $this->dbh->quote($value);
        }
        return [$type,self::_field($field),$sign,$value];

    }

    public function where($field,$sign,$value=NULL,bool $native=FALSE){
        $this->components["where"][] = $this->_where("",$field,$sign,$value,$native);
       return $this;
    }

    public function orWhere($field,$sign,$value=NULL,bool $native=FALSE){
        $this->components["where"][] = $this->_where("OR",$field,$sign,$value,$native);
        return $this;
    }

    public function andWhere($field,$sign,$value=NULL,bool $native=FALSE){
        $this->components["where"][] = $this->_where("AND",$field,$sign,$value,$native);
        return $this;
    }

    private function _whereGroup(callable $where,$type=NULL){
        if($type !== NULL){
            $this->components["where"][] = [$type];
        }
        $this->components["where"][] = ["("];
        $where($this);
        $this->components["where"][] = [")"];
        return $this;
    }

    public function whereGroup(callable $where){
        return $this->_whereGroup($where);
    }

    public function andWhereGroup(callable $where){
        return $this->_whereGroup($where,"AND");
    }

    public function orWhereGroup(callable $where){
        return $this->_whereGroup($where,"OR");
    }

    public function having($field,$sign,$value=NULL){
        $this->components["having"][] = $this->_where("",$field,$sign,$value);
        return $this;
    }

    public function orHaving($field,$sign,$value=NULL){
        $this->components["having"][] = $this->_where("OR",$field,$sign,$value);
        return $this;
    }

    public function andHaving($field,$sign,$value=NULL){
        $this->components["having"][] = $this->_where("AND",$field,$sign,$value);
        return $this;
    }

    public function asc($field="id"){
        $this->components["order"][] = ["ASC",self::_field($field)];
        return $this;
    }

    public function desc($field="id"){
        $this->components["order"][] = ["DESC",self::_field($field)];
        return $this;
    }

    public function limit(int $limit){
        $this->components["limit"] = $limit;
        return $this;
    }

    public function offset(int $offset){
        $this->components["offset"] = $offset;
        return $this;
    }

    public function fields(array $fields){
        $this->components["fields"] = array_map(function ($f){
           return self::_field($f);
        },$fields);
        return $this;
    }

    public function groupBy(string $value){
        $this->components["groupby"] = $value;
        return $this;
    }

    private function _select(){
        $fields = $this->components["fields"]===NULL ? "*" : implode(", ",$this->components["fields"]);
        $q = "SELECT {$fields} FROM `{$this->t}`";
        if(!empty($this->components["join"])){
            foreach ($this->components["join"] as $join){
                $q.= " {$join[0]} JOIN `{$join[1]}` ON {$join[2]}";
            }
        }
        if(!empty($this->components["where"])){
            $q.= " WHERE ";
            foreach ($this->components["where"] as $where){
                $q.= " {$where[0]} ";
                if(count($where) > 1){
                    $q.= "({$where[1]} {$where[2]} {$where[3]})";
                }
            }
        }

        if(!empty($this->components["groupby"])){
            $q.= " GROUP BY `{$this->components["groupby"]}`";
        }

        if(!empty($this->components["having"])){
            $q.= " HAVING ";
            foreach ($this->components["having"] as $having){
                $q.= " {$having[0]} ";
                if(count($having) > 1){
                    $q.= "({$having[1]} {$having[2]} {$having[3]})";
                }
            }
        }
        if(!empty($this->components["order"])){
            $q.= " ORDER BY ".implode(",",array_map(function ($elem){
                return "{$elem[1]} {$elem[0]}";
                },$this->components["order"]));
        }
        if(!empty($this->components{"limit"})){
            $q.= " LIMIT {$this->components["limit"]}";
            if(!empty($this->components["offset"])){
                $q.= " OFFSET {$this->components["offset"]}";
            }
        }
        return $q;
    }
//представление
    public function zapros(){
        return $this->_select();
    }

    public function all(array $params=[]){
        return $this->_execute($this->_select(),$params)->fetchAll();
    }

    public function first(array $params=[]){
        return $this->_execute($this->_select(),$params)->fetch();
    }
}