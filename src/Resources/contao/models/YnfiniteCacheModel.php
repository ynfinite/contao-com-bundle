<?php

namespace Ynfinite;

use Contao\Model;

class YnfiniteCacheModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_ynfinite_cache';

    public static function findAll(array $arrOptions=array()) {
        return static::findBy(null, null, $arrOptions);
    }

    public static function findByUrl($url, array $arrOptions = array()) {
    	$t = static::$strTable;

        $arrOptions = array_merge(
            array(
                'return' => 'Model',
                'column' => array("$t.url=?"),
                'value'  => array($url)
            ),
            $arrOptions
        );

        return static::find($arrOptions);
    }

}