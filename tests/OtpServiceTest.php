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

    /** @test */
    public function canGenerateOtp()
    {
        $otp = new MockOtp();

        $this->assertNotEmpty($otp->generate('foo'));
    }

    /** @test */
    public function shouldGenerateDifferentOtpEachTime()
    {
        $otp = new MockOtp();
        $this->assertNotEquals($otp->generate('foo'), $otp->generate('foo'));
        $this->assertNotEquals($otp->generate('bar'), $otp->generate('bar'));
    }

    /** @test */
    public function shouldGeneratesTheSameNumberOfDigits()
    {
        $otp1 = new MockOtp();
        $otp1->setDigits(4);

        $otp2 = new MockOtp();
        $otp2->setDigits(6);

        $this->assertEquals(4, strlen($otp1->generate('foo')));
        $this->assertEquals(6, strlen($otp2->generate('foo')));
    }

    /** @test */
    public function validatesTheOtp()
    {
        $otp = new MockOtp();
        $code = $otp->generate('foo');

        $this->assertFalse($otp->validate($code, 'bar'));
        $this->assertTrue($otp->validate($code, 'foo'));
    }

    /** @test */
    public function itShouldForgetTheOtp()
    {
        $otp = new MockOtp();
        $code = $otp->generate('foo');

        $this->assertFalse($otp->validate($code, 'bar'));
        $this->assertTrue($otp->validate($code, 'foo'));

        $otp->forget('foo');
        $this->assertFalse($otp->validate($code, 'foo'));
    }

    /** @test */
    public function otpWillBeInvalidAfterTheExpiry()
    {
        $otp = new MockOtp();
        $code = $otp->generate('foo');

        $otp->setTime(time() + ($otp->getExpiry() * 100));

        $this->assertFalse($otp->validate($code, 'foo'));
    }
}
