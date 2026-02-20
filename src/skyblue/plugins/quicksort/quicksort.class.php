<?php

/**
 * quicksort. This sorting algorithm uses a callback funtion (via a omparator,
 * so it can be implied to arrays, objects etc.
 * 
 * This file is part of the Brim project. The brim- project is located at the
 * following location: {@link http: //www.brim-project.org/ http://www.brim-project.org/}
 * 
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php 
 * The GNU Public License
 */
class quicksort
{

    var $fieldToCompare = NULL;

    function verify()
    {
        return true;
    }

    /**
     * Default empty constructor
     */
    function __construct() 
    {
        ;
    }
    
    /**
     * The actual function
     * @param array input object array containing objects to be sorted
     * @param object comparator the comparator that compares two objects in 
     * the input array
     */
    function _sort( &$input, $fieldToCompare )
    {
        $this->fieldToCompare = $fieldToCompare;
        $this->internalSort( $input, 0, count($input) -1 );
    }
    
    /**
     * The partial sorting function. Private/internal use only 
     * @param array input object array containing objects to be sorted
     * @param object comparator the comparator that compares two objects in 
     * the input array
     * @param int low the starting offset
     * @param int high the ending offset
     * @private
     */
    function internalSort( &$input, $low, $high )
    {
        if( $low < $high )
        {
            $tmpLow = $low;
            $tmpHigh = $high + 1;
            $current = $input[$low];
            
            $done = false;
            while( !$done )
            {
                
                while (++$tmpLow <= $high && 
                    $this->compare( $input[$tmpLow], $current) < 0 );
                    
                while ( $this->compare( $input[--$tmpHigh], $current) > 0 );
                
                if ( $tmpLow < $tmpHigh )
                {
                    $this->swap( $input, $tmpLow, $tmpHigh );
                } else {
                    $done = true;
                }
            }
            $this->swap( $input, $low, $tmpHigh );
            $this->internalSort( $input, $low, $tmpHigh-1 );
            $this->internalSort( $input, $tmpHigh+1, $high );
        }
    }
    
    /**
     * Swap two elements in an array
     * @param array input the input array
     * @param int i the first element to be swapped
     * @param int j the second element to be swapped
     * @private
     */
    function swap( &$input, $i, $j )
    {
        $temp = $input[$i];
        $input[$i]=$input[$j];
        $input[$j]=$temp;
    }
    
        /**
     * Compares two objects
     *
     * @param first the first object to compare
     * @param object second the second object to compare
     * @return <0 when first < second || first != null && second == null
     * @return 0  when first == second
     * @return >0 when first > second || first == null && second != null
     */
    function compare ($first, $second)
    {
        if (!isset ($first))
        {
            return 1;
        }
        if (!isset ($second))
        {
            return -1;
        }
        $fieldToCompare = $this->fieldToCompare;
        $fst = strtolower( @$first->$fieldToCompare );
        if (strlen ($fst) == 0)
        {
            $fst = ' ';
        }
        $snd = strtolower (@$second->$fieldToCompare);
        if (strlen ($snd) == 0)
        {
            $snd = ' ';
        }

        if (is_numeric($fst) && is_numeric($snd)) {
            if ($fst < $snd) return -1;
            if ($snd < $fst) return 1;
            if ($fst == $snd) return 0;
        }
        
        if ( phpversion() < 5 )
        {
            return $this->mySubstr_compare( $fst, $snd, 0 );
        } else {
            return substr_compare( $fst, $snd, 0 );
        }
    }
    
    function mySubstr_compare( $main_str, $str, $offset, $length = NULL, $case_insensitivity = false )
    {

        if ($offset >= strlen($main_str)) 
        {
           trigger_error('The start position cannot exceed initial string length.', E_USER_WARNING);
           return;
        }
        
        if (is_int($length)) 
        {
           $main_substr = substr($main_str, $offset, $length);
        } else {
           $main_substr = substr($main_str, $offset);
        }
        
        if ($case_insensitivity === true) 
        {
           return strcasecmp($main_substr, $str);
        }
        
        return strcmp($main_substr, $str);
   }
}

?>