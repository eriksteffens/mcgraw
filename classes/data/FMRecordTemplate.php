<?php

namespace mcs;

abstract class FMRecordTemplate
{
	private $rec = null;
	private $prefix = '';
	private $commit = false;
	function __construct(\FileMaker_Record $rec=null, $prefix=null)
	{if($rec != null){
		if ($prefix !== null)
			$this->prefix = "{$prefix}::";
		$this->rec = $rec;
		$this->readFields();
		}
	}

	public function reInit(\FileMaker_Record $rec=null, $prefix=null)
	{if($rec != null){
		if ($prefix !== null)
			$this->prefix = "{$prefix}::";
		$this->rec = $rec;
		$this->readFields();
		}
	}
	
	static function newRecord(\FileMaker $FM, $layout=null)
	{
		if (!$layout)
			$layout = static::DEFAULT_LAYOUT;
		return new static($FM->createRecord($layout));
	}
	
	protected function getField($field)
	{
		return html_entity_decode($this->rec->getField("{$this->prefix}{$field}"));
	}
	
	protected function setField($field, $value)
	{
		$this->commit = true;
		return $this->rec->setField("{$this->prefix}{$field}", $value);
	}
	
	protected function setFieldFromTimestamp($field, $value)
	{
		$this->commit = true;
		return $this->rec->setFieldFromTimestamp("{$this->prefix}{$field}", $value);
	}
	
	public function commit()
	{
            $toReturn = true;
		if ($this->commit)
                {
                	//echo "items changed performing rec commit <br/>";
                    $toReturn =  $this->rec->commit();
                    $this->readFields();
                }
			
                
		return $toReturn;
	}
	
	protected function getRelatedSet($setName)
	{
		$set = $this->rec->getRelatedSet($setName);
		if (!is_array($set))
			return array();
		return $set;
	}
	
	protected function readFields()
	{
		
	}
	
	public function debugRec()
	{
		return $this->rec;
	}
	
	protected function transferDataToField($data, $dataIndex, $fieldName)
	{
		if (isset($data[$dataIndex])){
			$this->setField($fieldName, $data[$dataIndex]);
                }
	}
	
	public function deleteRec(){
	    $this->rec->delete();
	}
}
