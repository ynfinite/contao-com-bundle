<?php

namespace Ynfinite;

use Contao\Model;

class YnfiniteFilterFormModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_ynfinite_filter_form';

    public static function findAll(array $arrOptions=array()) {
        return static::findBy(null, null, $arrOptions);
    }

}