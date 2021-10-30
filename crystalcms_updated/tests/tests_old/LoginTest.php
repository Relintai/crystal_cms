<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;

class LoginTest extends TestCase
{
	use DatabaseTransactions;

    public function testIfLoginWorks()
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

        $this->visit('login')
        	->type('tester', 'username')
        	->type('testerpw', 'password')
        	->see('Logout');
    }

    public function testIfWrongPasswordDoesntWork()
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

        $this->visit('logout');

        $this->visit('login')
        	->type('tester', 'username')
        	->type('testerpwasfd', 'password')
        	->dontSee('Logout');
    }

	protected function enableRegistration()
    {
    	$s = GlobalSettings::where('key', 'registration_enabled')->first();
    	$s->value = 1;
    	$s->save();
    }
}
