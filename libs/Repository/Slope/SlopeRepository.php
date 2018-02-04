<?php


namespace App\Repository\Slope;


use App\Repository\Base\BaseRepository;

/**
 * Class SlopeRepository
 * @package App\Repository\Slope
 * @author Josef Banya
 */
class SlopeRepository extends BaseRepository
{
    /** @var string  */
    protected $tableName = 'slope';

    /**
     * @param $id
     * @return bool
     */
    public function getSlopeTypeById($id)
    {
        $type = $this->getDatabase()->table('slope_type')
            ->select('*')
            ->where('id = ?', $id)
            ->fetch();

        if ($type instanceof IRow) {
            return $type->name;
        } else {
            return false;
        }
    }
}