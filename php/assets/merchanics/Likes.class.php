<?php

namespace ass\mech;

class Image
{
    private $_image;
    private $_filters;

    public function __construct( $base64, $filters, $name)
    {
        $this->base64ToImg($base64);
        $this->setFileter($filters);
        if (!empty($this->_filters)){
            foreach ($this->_filters as $filter){
                $this->merge($filter);
            }
        }
        $this->save($name);
    }
}
?>