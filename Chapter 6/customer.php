<?php

require 'mysql.php';
require 'dbconnection.php';

class Customer
{
    private $_mysql;
    private $_mongodb;
    private $_collection;
    private $_table;
    
    private $_id;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_dateOfBirth;
    private $_createdAt;
    
    public function __construct($id = null)
    {
        $this->_mysql   = getMySQLConnection();
        
        $this->_mongodb = DBConnection::instantiate();
        $this->_collection = $this->_mongodb->getCollection('customer_metadata');
        $this->_table = 'customers';
        
        if(isset($id)) {
            $this->_id = $id;
            $this->_load();
        }
    }
    
    private function _load()
    {
        $query = sprintf("SELECT * FROM %s WHERE id = %d", $this->_table, $this->_id);
        
        $result = $this->_mysql->query($query);
        
        if($result === False) {
            throw new Exception('Error loading data: '.$this->_mysql->error);
        }
        elseif($result->num_rows === 0) {
            throw new Exception('No customer found with id '.$this->id);
            $this->__destruct();
        }
        else{
            $obj = $result->fetch_object();
            
            $this->_email       = $obj->email_address;
            $this->_firstName   = $obj->first_name;
            $this->_lastName    = $obj->last_name;
            $this->_dateOfBirth = $obj->date_of_birth;
            $this->_createdAt   = $obj->created_at;
            
            $result->free();
        }
        
        return;
    }
    
    
    public function __get($name){
        switch($name) {
            
            case 'id':
                return $this->_id;
            
            case 'email';
                return $this->_email;
            
            case 'firstName':
                return $this->_firstName;
            
            case 'lastName':
                return $this->_lastName;
                
            case 'dateOfBirth':
                return $this->_dateOfBirth;
                
            case 'createdAt':
                return $this->_createdAt;
            default:
                throw new Exception('Trying to access undefined property '.$name);
        }
    }
    
    public function __set($name, $value){
        switch($name) {
            case 'email':

                if(filter_var($value, FILTER_VALIDATE_EMAIL) === False){
                    throw new Exception('Trying to set invalid email');
                    return;
                }

                $this->_email = $value;
                break;
            
            case 'firstName':
            case 'lastName' :
                
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                
                if ($name === 'firstName'){
                    $this->_firstName = $value;
                }
                else {
                    $this->_lastName = $value;
                }
                break;
            case 'dateOfBirth':
                $timestamp = strtotime($value);
                
                if(is_numeric($timestamp) === False){
                    throw new Exception('Trying to set invalid date of birth. Expected format Y-m-d');
                    return;
                }
                
                elseif($timestamp > time()){
                    throw new Exception('Trying to set future date as birth date.');
                    return;
                }
                elseif (checkdate(date('m', $timestamp), 
                                  date('d', $timestamp), 
                                  date('Y', $timestamp)
                                 ) === False
                       ) {
                    throw new Exception('Trying to set invalid date of birth.');
                    return;
                }
                $this->_dateOfBirth = date('Y-m-d H:i:s', $timestamp);
                break;
            default:
                throw new Exception('Trying to set undefined/restricted property '.$name);
        }
    }
    
    public function save(){
        if(isset($this->_id)) {
            $query = sprintf("UPDATE %s SET first_name='%s', last_name='%s', email_address='%s',".
                             " date_of_birth='%s' WHERE id = %d", $this->_table,
                                                                  $this->_firstName,
                                                                  $this->_lastName,
                                                                  $this->_email,
                                                                  $this->_dateOfBirth,
                                                                  $this->_id);
        }
        else {
            $query = sprintf("INSERT INTO %s (first_name, last_name, email_address,".
                             " date_of_birth) VALUES('%s', '%s', '%s', '%s')", $this->_table,
                                                                               $this->_firstName,
                                                                               $this->_lastName,
                                                                               $this->_email,
                                                                               $this->_dateOfBirth);
        }

        $status = $this->_mysql->query($query);
        
        if ($status === False) {
            throw new Exception('Failed to save customer to MySQL database '.$this->_mysql->error);
        }
        
        elseif(!isset($this->_id)) {
            $this->_id = $this->_mysql->insert_id;
        }
        
        return $status;
    }
    
    public function delete() {
        
        if(!isset($this->_id)){
            return;
        }
        
        $query = sprintf("DELETE FROM %s WHERE id = %d", $this->_table, $this->_id);
        
        $status = $this->_mysql->query($query);
        
        if ($status === False) {
            throw new Exception('Failed to delete customer from MySQL database '.$this->_mysql->error);
        }
        else{
            unset($this->_id);
        }
        
        return $status;
    }
    
    public function getMetaData()
    {
        if(!isset($this->_id)) {
            return;
        }
        
        $metadata = $this->_collection->findOne(array('customer_id' => $this->_id));
        
        if ($metadata === NULL) {
            return array();
        }
        
        unset($metadata['_id']);
        unset($metadata['customer_id']);
        
        return $metadata;
        
    }
    
    public function setMetaData($metadata)
    {
        if(!isset($this->_id)) {
            throw new Exception('Cannot store metadata before saving the object in MySQL');
        }
        
        $metadata['customer_id'] = $this->_id;
        
        foreach($metadata as $key => $value) {
            if ($key === '_id') {
                unset($metadata[$key]);
            }
            elseif ((strpos($key, '$') !== FALSE) || (strpos($key, '.') !== FALSE)) {
                unset($metadata[$key]);
            }
        }
        
        $currentMetaData = $this->getMetaData();
        $metadata = array_merge($currentMetaData, $metadata);
        
        $this->_collection->update(array('customer_id' => $this->_id), $metadata, 
                                   array('upsert' => True));
                                   
    }
    
    public function __destruct(){
        $this->_mysql->close();
        $this->_mongodb->connection->close();
    }
}