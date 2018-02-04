<?php


namespace App\User\Components;

/**
 * Class FilterQuery
 * @package App\User\Components
 * @author Josef Banya
 */
class FilterQuery
{
    /** @var  double */
    public $lat;

    /** @var  double */
    public $long;

    /** @var  int */
    public $distance;

    /** @var  int */
    public $slopeCount;

    /** @var  array */
    public $towType = [];

    /** @var  array */
    public $slopeType = [];

    /** @var  int */
    public $camber;

    /** @var  int */
    public $lenght;

    /** @var array  */
    public $openingDays = [];

    /** @var  int */
    public $price;

    /** @var  int */
    public $night;

    /**
     * @param $data
     */
    public function hydrate($data)
    {
        foreach ($data as $k => $v) {

	        switch ($k) {
		        case 'openingDays':
			        foreach ($v as $day) {
				        $this->openingDays[] = $day;
			        }
			        break;
		        case 'towType':
		        	foreach ($v as $type) {
		        		$this->towType[] = $type;
			        }
			        break;
		        case 'slopeType':
		        	foreach ($v as $type) {
		        		$this->slopeType[] = $type;
			        }
			        break;
		        default:
			        $this->$k = $v;
	        }
        }
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return self Provides Fluent Interface
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->long;
    }

    /**
     * @param float $lng
     * @return self Provides Fluent Interface
     */
    public function setLng($lng)
    {
        $this->long = $lng;
        return $this;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     * @return self Provides Fluent Interface
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return int
     */
    public function getSlopeCount()
    {
        return $this->slopeCount;
    }

    /**
     * @param int $slopeCount
     * @return self Provides Fluent Interface
     */
    public function setSlopeCount($slopeCount)
    {
        $this->slopeCount = $slopeCount;
        return $this;
    }

	/**
	 * @return array
	 */
    public function getTowType()
    {
        return $this->towType;
    }

    /**
     * @param int $towType
     * @return self Provides Fluent Interface
     */
    public function setTowType($towType)
    {
        $this->towType = $towType;
        return $this;
    }

	/**
	 * @return array
	 */
    public function getSlopeType()
    {
        return $this->slopeType;
    }

    /**
     * @param int $slopeType
     * @return self Provides Fluent Interface
     */
    public function setSlopeType($slopeType)
    {
        $this->slopeType = $slopeType;
        return $this;
    }

    /**
     * @return int
     */
    public function getCamber()
    {
        return $this->camber;
    }

    /**
     * @param int $camber
     * @return self Provides Fluent Interface
     */
    public function setCamber($camber)
    {
        $this->camber = $camber;
        return $this;
    }

    /**
     * @return array
     */
    public function getOpeningDays()
    {
        return $this->openingDays;
    }

    /**
     * @param array $openingDays
     * @return self Provides Fluent Interface
     */
    public function setOpeningDays($openingDays)
    {
        $this->openingDays = $openingDays;
        return $this;
    }

    /**
     * @return int
     */
    public function getLenght()
    {
        return $this->lenght;
    }

    /**
     * @param int $lenght
     * @return self Provides Fluent Interface
     */
    public function setLenght($lenght)
    {
        $this->lenght = $lenght;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return self Provides Fluent Interface
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

	/**
	 * @return int
	 */
	public function getNight() {
		return $this->night;
	}

	/**
	 * @param int $night
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setNight( $night ) {
		$this->night = $night;

		return $this;
	}






}