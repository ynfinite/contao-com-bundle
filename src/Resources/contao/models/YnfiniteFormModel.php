<?php

namespace Ynfinite;

use Contao\Model;

class YnfiniteFormModel extends Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_ynfinite_form';

    public static function findAll(array $arrOptions=array()) {
        return static::findBy(null, null, $arrOptions);
    }

}