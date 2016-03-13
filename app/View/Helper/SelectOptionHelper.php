<?php

/**
 * select options helper
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category
 * @package
 *
 */
class SelectOptionHelper extends AppHelper {


    public function yearArray($id, $defalut = null, $empty = null, $error = true) {

        $years = array();
        $startYear = 2015;
        $endYear = date('Y');

        for ($i = $startYear; $i <= $endYear + 5; $i++) {
            $years[$i] = $i;
        }

        $returnArray = array();
        $returnArray['class'] = 'select-date';
        $returnArray['id'] = $id;
        if (! $error)
            $returnArray['error'] = false;
        $returnArray['options'] = $years;

        if ($defalut !== null) {
            $returnArray['default'] = $defalut;
        } else {
            $returnArray['default'] = date('Y');
        }

        if ($empty != '') {
            $returnArray['empty'] = $empty;
        }

        return $returnArray;

    }

    public function monthArray($id, $defalut = null, $empty = null) {

        $month = array();
        for ($i = 1; $i <= 12; $i ++) {
            $month[sprintf('%02d', $i)] = sprintf('%02d', $i);
        }

        $returnArray = array();
        $returnArray['class'] = 'select-date';
        $returnArray['id'] = $id;
        $returnArray['options'] = $month;

        if ($defalut !== null) {
            $returnArray['default'] = $defalut;
        } else {
            $returnArray['default'] = date('m');
        }

        if ($empty != '') {
            $returnArray['empty'] = $empty;
        }

        return $returnArray;

    }

    public function dayArray($id, $defalut = null, $empty = null) {

        $days = array();
        for ($i = 1; $i <= 31; $i ++) {
            $days[sprintf('%02d', $i)] = sprintf('%02d', $i);
        }

        $returnArray = array();
        $returnArray['class'] = 'select-date';
        $returnArray['id'] = $id;
        $returnArray['options'] = $days;

        if ($defalut !== null) {

            if ($defalut == 'last') {
                $returnArray['default'] = date('t');
            } else {
                $returnArray['default'] = $defalut;
            }
        } else {
            $returnArray['default'] = date('d');
        }

        if ($empty != '') {
            $returnArray['empty'] = $empty;
        }

        return $returnArray;

    }
}
