<?php namespace Cornford\Pokenotifier\Contracts;

use Cornford\Pokenotifier\Models\Position;

interface PositioningInterface {

    /**
     * Public constructor.
     *
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude);

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude();

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return void
     */
    public function setLongitude($longitude);

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude();

    /**
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return float
     */
    public function setLatitude($latitude);

    /**
     * Calculate distance in meters between the current and another position.
     *
     * @param Position $position
     *
     * @return float
     */
    public function calculateDistance(Position $position);

}
