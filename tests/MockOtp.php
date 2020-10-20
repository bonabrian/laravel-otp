<?php

namespace Bonabrian\Otp\Tests;

use Bonabrian\Otp\OtpService;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;

class MockOtp extends OtpService
{
    /**
     * MockOtp constructor
     */
    public function __construct() {
        $directory = './tests/php-unit-cache';
        $cache = new Repository(new FileStore(new Filesystem(), $directory));

        parent::__construct($cache);
    }

    /**
     * Get expiry
     *
     * @return int
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * Get digits
     *
     * @return int
     */
    public function getDigits()
    {
        return $this->digits;
    }

    /**
     * Set time
     *
     * @param int $time
     * @return void
     */
    public function setTime(int $time)
    {
        $this->time = $time;
    }
}
