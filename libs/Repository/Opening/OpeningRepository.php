<?php


namespace App\Repository\Opening;


use App\Repository\Base\BaseRepository;

/**
 * Class OpeningRepository
 * @package App\Repository\Opening
 * @author Josef Banya
 */
class OpeningRepository extends BaseRepository
{
    /** @var string  */
    protected $tableName = 'destination_openhour';

    public function createOpeningHoursDataByDestinationId($destinationId)
    {
        if (!$destinationId) return false;
        $openings = $this->getList()->where(['destination_id' => $destinationId]);
        $openingDefaults = [];
        if ($openings->count('id') > 7) {
            $data = array_map( function ($item) {
                return $item->day;
            },(array) $openings->fetchAll());
            $data = array_values($data);
            $prev = null;
            $current = null;
            for ($i = 0; $i < count($data); $i++) {
                $prev = $current;
                $current = $data[$i];

                if($prev !== null && $current === $prev){
                    $openHourString = '';
                    $days = $this->getList()->where(['day' => $current, 'destination_id' => $destinationId])->fetchAll();
                    foreach($days as $day){
                        if($day === reset($days)){
                            $openHourString .= $day->from->format('%H:%I') . ' - ' . $day->to->format('%H:%I'). ' - ';
                        } else {
                            $openHourString .= $day->from->format('%H:%I') . ' - ' . $day->to->format('%H:%I');
                        }
                    }
                    $openingDefaults[$current] = $openHourString;
                }
            }

            foreach ($openings as $opening){
                if (!key_exists($opening->day, $openingDefaults)){
                    $openingDefaults[$opening->day] = $day->from->format('%H:%I') . ' - ' . $day->to->format('%H:%I');
                }
            }
        }
        else {
            foreach ($openings as $day) {
                $openingDefaults[$day->day] = $day->from->format('%H:%I') . ' - ' . $day->to->format('%H:%I');
            }
        }
        return $openingDefaults;
    }
}