<?php

/**
 * Form Class to generate forms on the fly with less markup
 * @author Andre Honsberg -> www.AndreHonsberg.com
 */
class Mod_Html extends System {

    public function __construct() {

    }

    public function lists(&$data, $processing_data) {
        
        $list = '<' . $data[0] . '>';
        foreach ($processing_data as $key => $value) {
            if (!is_array($value)) {

                if (!array($key)) {
                    $v = '<a href="' . $value . '">' . $key . '</a>';
                } else {
                    $v = $value;
                }
                $list .= '<li>' . $v . '</li>';
            } else {

                $n = array($key);
                $list .= $this->lists($n, $value);
            }
        }
        $list .= '</' . $data[1] . '>';
        return $list;
    }

}