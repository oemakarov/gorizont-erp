<?php


namespace totum\models;

use totum\common\errorException;
use totum\common\Lang\RU;
use totum\common\Model;

class CalcsTablesVersions extends Model
{
    protected $cachedDefaultVersions = [];

    public function getDefaultVersion($tableName, $withDefaultOrd = false)
    {
        if (!key_exists($tableName, $this->cachedDefaultVersions)) {
            $this->cachedDefaultVersions[$tableName] = $this->executePrepared(
                true,
                ['table_name' => $tableName, 'is_default' => "true"],
                'version, default_ord, default_auto_recalc'
            )->fetch();

            if (!$this->cachedDefaultVersions[$tableName]) {
                throw new errorException($this->translate('There is no default version for table %s.'), $tableName);
            }
        }
        if ($withDefaultOrd) {
            return $this->cachedDefaultVersions[$tableName];
        }
        return $this->cachedDefaultVersions[$tableName]['version'];
    }
}
