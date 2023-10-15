<?php

namespace App\Concerns;

trait ComparesInstances
{

    public function is($compare, $property = 'value')
    {
        if(is_null($property)){
            return $compare === $this;
        }

        if($compare instanceof self) {
            return $compare->{$property} === $this->{$property};
        }

        if(property_exists($compare, $property) && property_exists($this, $property)) {
            return $compare->{$property} === $this->{$property};
        }

        return false;
    }


}