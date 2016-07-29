<?php namespace Cornford\Pokenotifier\Models;

use Cornford\Pokenotifier\Contracts\PositioningInterface;

class Position implements PositioningInterface {

	/**
	 * Latitude.
	 *
	 * @var float
	 */
	protected $latitude = [];

	/**
	 * Longitude.
	 *
	 * @var float
	 */
	protected $longitude = [];

	/**
	 * Public constructor.
	 *
	 * @param float $latitude
	 * @param float $longitude
	 */
	public function __construct($latitude, $longitude)
	{
		$this->latitude = $latitude;
		$this->longitude = $longitude;
	}

	/**
	 * Get longitude.
	 *
	 * @return float
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	/**
	 * Set longitude.
	 *
	 * @param float $longitude
	 *
	 * @return void
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}

	/**
	 * Get latitude.
	 *
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * Set latitude.
	 *
	 * @param float $latitude
	 *
	 * @return float
	 */
	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
	}

	/**
	 * Calculate distance in meters between the current and another position.
	 *
	 * @param Position $position
	 *
	 * @return float
	 */
	public function calculateDistance(Position $position)
	{
		$theta = $this->getLongitude() - $position->getLongitude();
		$dist = rad2deg(acos(sin(deg2rad($this->getLatitude())) * sin(deg2rad($position->getLatitude())) + cos(deg2rad($this->getLatitude())) * cos(deg2rad($position->getLatitude())) * cos(deg2rad($theta))));
		$miles = $dist * 60 * 1.1515;

		return round($miles * 1.609344 * 1000);
	}

}
