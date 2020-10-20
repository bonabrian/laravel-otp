<?php

namespace Bonabrian\Otp;

use Illuminate\Contracts\Cache\Repository;

class OtpService
{
    /**
     * Cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository $cache
     */
    protected Repository $cache;

    /**
     * OTP expiry limit.
     *
     * @var int
     */
    protected $expiry = 600;

    /**
     * OTP digits | Default 4.
     *
     * @var int
     */
    protected $digits = 4;

    /**
     * @var int
     */
    protected $time;

    /**
     * OtpService constructor.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->time = time();
        $this->cache = $cache;
    }

    /**
     * Set expiry time for otp.
     *
     * @param int $expiry
     * @return self
     */
    public function setExpiry(int $expiry): self
    {
        $seconds = $expiry * 60;

        if ($seconds > 0) {
            $this->expiry = $seconds;
        }

        return $this;
    }

    /**
     * Set digits for otp.
     *
     * @param int $digits
     * @return self
     */
    public function setDigits(int $digits): self
    {
        if ($digits > 0) {
            $this->digits = $digits;
        }

        return $this;
    }
}
