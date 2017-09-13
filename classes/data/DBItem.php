<?php

/**
 * represents a record in the database.
 *
 * @author erik
 */
class DBItem extends \mcs\FMRecordTemplate
{
	public $id;
    public $name;

    public $store;
    public $toSet;

    public $dbItemFields = [];
    public $friendMap = [];

    public function setDBItemFields($dbItemFields){
    	$this->dbItemFields = $dbItemFields;
    }

    public function setStore($store){
    	$this->store = $store; 
    }

    public function preSet($toSet){
    	$this->toSet = $toSet;
    }

	protected function readFields()
	{
		//this loads the record data into the object
		$this->name = $this->getField('Name');
		$this->id = $this->getField('id');
		foreach($this->dbItemFields as $field){
			//echo "getting field " . $field . " to " . $this->getField($field) . "<br/>";
			$this->store[$field] = $this->getField($field); 
			$this->friendMap[friendly($field)] = $field;
		}
		
	}
	
	public function toArray(){

	    return array_merge($this->store, ["id"=>$this->id]);
	}

	public function setName(){

		$this->setField("Name", "Test");
		$this->commit();
	}
	
	public function commitToDatabase(){
		$changed = 0;
		foreach($this->dbItemFields as $field){
			if($this->toSet[friendly($field)] != $this->store[$field]){
				$changed = $changed + 1;
			$this->setField($field ,$this->toSet[friendly($field)]);
		}
			
		}

		//commit can be slow so only commit if there has been changes to the record.

		//echo $changed . " objects changed ";
		if($changed > 0){
			//echo  "committing";
	   if($this->commit()){
	   	//echo "commit successful";
	   }else{
	   	//echo "commit failed";
	   }
	}
	   
	}

}
