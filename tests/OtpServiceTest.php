<?php

namespace Bonabrian\Otp\Tests;

class OtpServiceTest extends TestCase
{
    /** @test */
    public function shouldHasDigitsExpiry()
    {
        $otp = new MockOtp();

        $this->assertTrue($otp->getExpiry() > 0);
        $this->assertTrue($otp->getDigits() > 0);
    }

    /** @test */
    public function digitsShouldCanBeChanged()
    {
        $otp1 = new MockOtp();
        $otp1->setDigits(4);

        $otp2 = new MockOtp();
        $otp2->setDigits(6);

        $this->assertEquals(4, $otp1->getDigits());
        $this->assertEquals(6, $otp2->getDigits());
    }

    /** @test */
    public function expiryShouldCanBeChanged()
    {
        $otp1 = new MockOtp();
        $otp1->setExpiry(5);

        $otp2 = new MockOtp();
        $otp2->setExpiry(10);

        $this->assertEquals(300, $otp1->getExpiry());
        $this->assertEquals(600, $otp2->getExpiry());
    }
}
