<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait HasFieldsSaleDates
{

    /**
     * Get timestamp array from sale_dates
     * 
     * @return string
     */
    public function getSalesDateTimestamp(){
        $_sale_dates = [];
        if($this->fields != ""){
            $_fields = $this->fields;
            if(is_array($_fields['sale_dates'])){
                foreach($_fields['sale_dates'] as $saleDates){
                    $date = Carbon::createFromFormat('Y-m-d', $saleDates);
                    $_sale_dates[] = $date->timestamp;
                }
            }
        }
        return $_sale_dates;
    }

}