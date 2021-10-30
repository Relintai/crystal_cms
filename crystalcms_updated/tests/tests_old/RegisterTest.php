<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;

class RegisterTest extends TestCase
{
	use DatabaseTransactions;

    public function testIfRegisterLinkWorks()
    {
    	$this->enableRegistration();

        $this->visit('register')
        	->see('Register');
    }

    public function testRegisterLinkWhenRegistrationDisabled()
    {
    	$this->disableRegistration();

    	$response = $this->call('GET', 'register');

    	$this->assertEquals(404, $response->status());
    }

    public function testIfRegisteringWorks()
    {
    	$this->enableRegistration();

        $this->visit('register')
        	->type('tester', 'username')
        	->type('tester@tester.com', 'email')
        	->type('testerpw', 'password')
        	->type('testerpw', 'password2')
        	->check('eula')
        	->press('Register!')
        	->seePageIs('/');
    }

    public function testUsernameErrors()
    {
    	$this->enableRegistration();

        $this->visit('register')
        	->type('te', 'username')
        	->type('tester@tester.com', 'email')
        	->type('testerpw', 'password')
        	->type('testerpw', 'password2')
        	->check('eula')
        	->press('Register!')
        	->see('username');
    }

    public function testEmailErrors()
    {
    	$this->enableRegistration();

        $this->visit('register')
        	->type('tester', 'username')
        	->type('testertester.com', 'email')
        	->type('testerpw', 'password')
        	->type('testerpw', 'password2')
        	->check('eula')
        	->press('Register!')
        	->see('email');

        $this->visit('register')
        	->type('tester', 'username')
        	->type('tester@testercom', 'email')
        	->type('testerpw', 'password')
        	->type('testerpw', 'password2')
        	->check('eula')
        	->press('Register!')
        	->see('email');
    }

    public function testPasswordMatchErrors()
    {
    	$this->enableRegistration();

        $this->visit('register')
        	->type('teasda', 'username')
        	->type('tester@tester.com', 'email')
        	->type('testerpw', 'password')
        	->type('testerpwa', 'password2')
        	->check('eula')
        	->press('Register!')
        	->see('password');
    }

    public function testEulaErrors()
    {
    	$this->enableRegistration();

        $this->visit('register')
        	->type('teasda', 'username')
        	->type('tester@tester.com', 'email')
        	->type('testerpw', 'password')
        	->type('testerpw', 'password2')
        	->press('Register!')
        	->see('eula');
    }
    
    protected function enableRegistration()
    {
    	$s = GlobalSettings::where('key', 'registration_enabled')->first();
    	$s->value = 1;
    	$s->save();
    }

    protected function disableRegistration()
    {
    	$s = GlobalSettings::where('key', 'registration_enabled')->first();
    	$s->value = 0;
    	$s->save();
    }
}
