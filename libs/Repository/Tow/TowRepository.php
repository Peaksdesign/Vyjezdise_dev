<?php


namespace App\Repository\Tow;


use App\Repository\Base\BaseRepository;
use Nette\Database\Table\IRow;

/**
 * Class TowRepository
 * @package App\Repository\Tow
 * @author Josef Banya
 */
class TowRepository extends BaseRepository
{
    /** @var string  */
    protected $tableName = 'tow_type';

    /**
     * @param $id
     * @return bool
     */
    public function getTowTypeById($id)
    {
       $type = $this->getDatabase()->table('tow_type')
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