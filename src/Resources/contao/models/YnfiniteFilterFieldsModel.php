<?php

namespace Ynfinite;

use Contao\Model;

class YnfiniteFilterFieldsModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_ynfinite_filter_fields';

    public static function findAll(array $arrOptions=array()) {
        return static::findBy(null, null, $arrOptions);
    }

    public static function findByPid($pid, array $arrOptions = array()) {
    	$t = static::$strTable;

        $arrOptions = array_merge(
            array(
                'return' => 'Array',
                'column' => array("$t.pid=?"),
                'value'  => array($pid)
            ),
            $arrOptions
        );

        return static::find($arrOptions);
    }

}