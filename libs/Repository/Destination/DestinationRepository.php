<?php


namespace App\Repository\Destination;


use App\Repository\Album\AlbumRepository;
use App\Repository\Base\BaseRepository;
use App\Repository\Rating\RatingRepository;
use App\Repository\Slope\SlopeRepository;
use App\User\Components\FilterQuery;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Database\Context;
use Nette\Utils\ArrayHash;

/**
 * Class DestinationRepository
 * @package App\Repository\Destination
 * @author Josef Banya
 */
class DestinationRepository extends BaseRepository
{

    /** constants */
    const STATUSES = [
        1 => 'allow',
        2 => 'deny'
    ];

    /** @var string  */
    protected $tableName = 'destinations';

    /** @var  AlbumRepository */
    private $albumRepository;

    /** @var  SlopeRepository */
    private $slopeRepository;

    /** @var  RatingRepository */
    private $ratingRepository;


	/**
	 * DestinationRepository constructor.
	 *
	 * @param \Nette\Database\Context $db
	 * @param \App\Repository\Slope\SlopeRepository $slopeRepository
	 * @param \App\Repository\Album\AlbumRepository $albumRepository
	 * @param \App\Repository\Rating\RatingRepository $ratingRepository
	 */
    public function __construct(Context $db, SlopeRepository $slopeRepository, AlbumRepository $albumRepository, RatingRepository $ratingRepository)
    {
        parent::__construct($db);
        $this->setSlopeRepository($slopeRepository)
            ->setAlbumRepository($albumRepository)
	        ->setRatingRepository($ratingRepository);
    }

    /**
     * @param \App\User\Components\FilterQuery $filterQuery
     * @return array|\Nette\Database\IRow[]|\Nette\Database\ResultSet
     */
    public function search(FilterQuery $filterQuery)
    {

        // SELECTORS
        $query = "SELECT  destinations.* ";
        if (!empty($filterQuery->getLat()) && !empty($filterQuery->getLng())) {
            $query .= ",(
                6371 *
                acos( cos( radians( ".$filterQuery->getLat()." ) ) * cos( radians( `lat` ) ) * cos( radians( `lng` ) - radians( ".$filterQuery->getLng()." ) ) + sin(radians(".$filterQuery->getLat().")) * sin(radians(`lat`))
                )
              ) `distance`";
        }

        $query .= " FROM `destinations`  ";

        $query .= "  JOIN `slope` AS s ON  s.destination_id = destinations.id ";
        $query .= "  JOIN `destination_openhour` AS do ON do.destination_id = destinations.id";

        // WHERES
        $query .= " WHERE destinations.status = 'allow' ";
        if (!empty($filterQuery->getOpeningDays())) {
            foreach ($filterQuery->getOpeningDays() as $day) {
                $query .= "AND (SELECT count(id) FROM `destination_openhour`
                 WHERE destination_id = destinations.id AND day = '$day') = 1 ";
            }
        }

        if (!empty($filterQuery->getSlopeCount())) {
            if ($filterQuery->getSlopeCount() == 8) { // 8 and more slopes in filter settings
              $query .= "AND (SELECT count(id) FROM `slope`
                 WHERE destination_id = destinations.id) >= " .$filterQuery->getSlopeCount() . " ";
            } else {
                $query .= "AND (SELECT count(id) FROM `slope`
                 WHERE destination_id = destinations.id) >= " .$filterQuery->getSlopeCount() . " ";
            }
        }

        if (!empty($filterQuery->getNight())) {
            $query .= " AND destinations.night = " . $filterQuery->getNight() . " ";
        }
        if (!empty($filterQuery->getCamber())) {
            $query .= " AND s.camber > " . $filterQuery->getCamber() . " ";
        }
        if (!empty($filterQuery->getPrice())) {
            $query .= " AND destinations.price <= " . $filterQuery->getPrice() . " ";
        }
//        if (!empty($filterQuery->getLenght())) {
//            $query .= "AND (SELECT sum(lenght) FROM `slope`
//                 WHERE destination_id = destinations.id) >= " . $filterQuery->getLenght();
//        }

        if (!empty($filterQuery->getLenght())) {
            $query .= " AND s.lenght >= " . $filterQuery->getLenght() . " ";
        }

        if (!empty($filterQuery->getSlopeType())) {
            foreach ($filterQuery->getSlopeType() as $type) {
	            $query .= " AND s.slope_type_id = " . $type . " ";
            }
        }

        if (!empty($filterQuery->getTowType())) {
        	foreach ($filterQuery->getTowType() as $type) {
		        $query .= " AND s.tow_type_id = " . $type . " ";
	        }
        }

        $query .= " GROUP BY destinations.id ";

        // HAVERS
        if (!empty($filterQuery->getLat()) && !empty($filterQuery->getLng()) && !empty($filterQuery->getDistance())) {
            $query .= " HAVING `distance` <= " . $filterQuery->getDistance(). " ";
        }

        // ORDERS
        if (!empty($filterQuery->getLat()) && !empty($filterQuery->getLng())) {
            $query .= " ORDER BY `distance` ";
        }

//        $query .= "  LIMIT 200 ";

        return  $this->prepareDataForResults( $this->getDatabase()->query($query)->fetchAll() );
    }

    /**
     * @param $result
     * @return array
     */
    private function prepareDataForResults($result)
    {
        $destinations = [];
        /** @var \Nette\Database\Row $dest */
        foreach ($result as $row){

            $slopes = $this->getSlopeRepository()->getList()->where(['destination_id'  => $row->id]);
            $slopeLength = 0;
            $slopeTypes = $towTypes = []
            ;
	        $ratingList = $this->getRatingRepository()
	                           ->getList()
	                           ->where(['destination_id' => $row->id]);

	        $ratingCount = $ratingList->count('id');

	        $ratings = $this->getRatingRepository()
	                        ->getList()
	                        ->select('SUM(final) / COUNT(id) AS totalCount')
	                        ->where(['destination_id' => $row->id])->fetch();

            foreach ($slopes as $slope) {
                $slopeLength = $slopeLength + $slope->lenght;
                $slopeTypes[] = $slope->slope_type->name;
                $towTypes[] = $slope->tow_type->name;
            }

            if (!empty($row->album_id)) {

            	$image = $this->getAlbumRepository()
                              ->getDatabase()
                              ->table('album_images')
                              ->where(['album_id' => $row->album_id, 'is_primary' => 1])
                              ->fetch();

                $image = 'upload/' . $image->image->image_file;
            } else {
                $image = 'frontend-assets/img/temp/temp.jpg';
            }

            $slopeTypes = array_unique($slopeTypes);
            $towTypes = array_unique($towTypes);

            $destinations[] = ArrayHash::from( [
                'id' => $row->id,
                'name' => $row->title,
                'seo_title' => $row->seo_title,
                'address' => $row->address,
                'imgPath' => $image,
                'distance' => (!empty($row->distance)) ? number_format( $row->distance , 1) : '/',
                'district' => $row->area,
                'contact' => $row->tel,
                'slopeLength' => $slopeLength,
                'difficulty' => implode(', ', $slopeTypes),
                'skiLift'   => implode(', ', $towTypes),
                'rating'    => $ratings->totalCount,
                'ratingNum' => $ratingCount,
                'skipass'   => $row->price

            ]);
        }
        return $destinations;
    }

    /**
     * @return \App\Repository\Album\AlbumRepository
     */
    public function getAlbumRepository()
    {
        return $this->albumRepository;
    }

    /**
     * @param \App\Repository\Album\AlbumRepository $albumRepository
     * @return self Provides Fluent Interface
     */
    public function setAlbumRepository($albumRepository)
    {
        $this->albumRepository = $albumRepository;
        return $this;
    }

    /**
     * @return \App\Repository\Slope\SlopeRepository
     */
    public function getSlopeRepository()
    {
        return $this->slopeRepository;
    }

    /**
     * @param \App\Repository\Slope\SlopeRepository $slopeRepository
     * @return self Provides Fluent Interface
     */
    public function setSlopeRepository($slopeRepository)
    {
        $this->slopeRepository = $slopeRepository;
        return $this;
    }

	/**
	 * @return \App\Repository\Rating\RatingRepository
	 */
	public function getRatingRepository() {
		return $this->ratingRepository;
	}

	/**
	 * @param \App\Repository\Rating\RatingRepository $ratingRepository
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setRatingRepository( $ratingRepository ) {
		$this->ratingRepository = $ratingRepository;

		return $this;
	}

	/**
	 * @return \Nette\Caching\Cache
	 */
	public function getCache() {
		return $this->cache;
	}

	/**
	 * @param \Nette\Caching\Cache $cache
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setCache( $cache ) {
		$this->cache = $cache;

		return $this;
	}



}