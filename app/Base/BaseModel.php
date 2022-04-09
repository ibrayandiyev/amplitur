<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $_searchable   = null;

    public function scopeFilter($query, $params)
    {
        if(is_array($params)){
            foreach($params as $key => $data){
                if(in_array($key, $this->fillable) && trim($params[$key] !== '')){
                    $query->where($key, 'LIKE', trim($params[$key]) . '%');
                }
            }
        }
        return $query;
    }

    public function getIsSearchable($key = null){
        if(is_array($this->_searchable)){
            if(array_key_exists($key, $this->_searchable)){
                return true;
            }
        }
        return false;
    }

    public function getIndexSearchable($key = null){
        if(isset($this->_searchable[$key]) && is_array($this->_searchable[$key])){
            return $this->_searchable[$key];
        }
        return false;
    }
}
