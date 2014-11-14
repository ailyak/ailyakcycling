<?php

namespace Ailyak\Ride;

/**
 * Description of Item
 *
 * @author vis
 */
class Item {

    private $name;

    function __construct($name, array $options = array()) {
        $this->name = (string) $name;
    }

    function getName() {
        return $this->name;
    }

    public function getHash() {
        return md5($this->name);
    }

}
