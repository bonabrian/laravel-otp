<?php

namespace Bonabrian\Otp;

use DateInterval;
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

    /**
     * Generate new OTP
     *
     * @param string $key
     */
    public function generate($key): string
    {
        $secret = sha1(uniqid());
        $ttl = DateInterval::createFromDateString("{$this->time} seconds");
        $this->cache->put($this->keyFor($key), $secret, $ttl);

        return $this->calculate($secret);
    }

    /**
     * Set key for
     *
     * @param string $key
     */
    protected function keyFor($key): string
    {
        return sha1(sprintf('%s-%s', 'laravel-otp', $key));
    }

    /**
     * Calculate to generate OTP
     */
    protected function calculate($secret, $factor = null): string
    {
        $hash = hash_hmac('sha1', $this->timeFactor($factor), $secret, true);
        $offset = ord($hash[strlen($hash) - 1]) & 0xf;
        $hash = str_split($hash);

        foreach ($hash as $key => $val) {
            $hash[$key] = ord($val);
        }

        $binary = (($hash[$offset] & 0x7f) << 24) | (($hash[$offset + 1] & 0xff) << 16) | (($hash[$offset + 2] & 0xff) << 8) | ($hash[$offset + 3] & 0xff);

        $otp = $binary % pow(10, $this->digits);

        return str_pad((string) $otp, $this->digits, '0', STR_PAD_LEFT);
    }

    protected function timeFactor($divisionFactor): string
    {
        $factor = $divisionFactor ? floor($divisionFactor) : floor($this->time / $this->expiry);

        $text = [];

        for ($i = 7; $i >= 0; $i--) {
            $text[] = ($factor & 0xff);
            $factor >>= 8;
        }

        $text = array_reverse($text);

        foreach ($text as $key => $val) {
            $text[$key] = chr((int) $val);
        }

        return implode('', $text);
    }
}
