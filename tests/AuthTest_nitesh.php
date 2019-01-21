<?php
//use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
class AuthTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
        protected $baseUrl = 'http://londonce.lan/';
        
	public function testLandingPage()
	{
		$this->visit('http://londonce.lan/login')
                    ->see('log')
                        ->see('password')
                            ->see('Login');
	}
        
        /**
            * Test: View Login
            *
            * Description:
            * This will test that the user will be able to view the login page.
            *
            * @return void
        */
        public function testViewLogin()
        {
            $this->visit('http://londonce.lan/login')
                 ->see('Login');
        }
        /**
        * Test: Email & Password Field Blank Validation
        *
        * Description:
        * This will test that the user has not entered a valid email and password.
        *
        * @return void
        */
       
        public function testLoginValidation()
        {           
            $this->visit('http://londonce.lan/login')
                 ->submitForm('Login', ['log' => '', 'password' => ''])
                    ->see('Please enter a valid username.')
                    ->see('Please enter a valid password.');
            
        }
        /**
        * Test: Email proper but Password Field Blank Validation
        *
        * Description:
        * This will test that the user has entered a valid email but password is blank.
        *
        * @return void
        */
       
        public function testLoginValidationLog()
        {           
            $this->visit('http://londonce.lan/login')
                 ->submitForm('Login', ['log' => 'aaaaa', 'password' => ''])                    
                    ->see('Please enter a valid password.');
            
        }
        /**
        * Test: Email not proper but Password Field Validation
        *
        * Description:
        * This will test that the user has not entered a valid email but password is not blank.
        *
        * @return void
        */
       
        public function testLoginValidationPass()
        {           
            $this->visit('http://londonce.lan/login')
                 ->submitForm('Login', ['log' => '', 'password' => 'sfaff'])
                    ->see('Please enter a valid username.');                       
                 
            
        }
        /**
        * Test: Email Validation
        *
        * Description:
        * This will test that the user has entered a valid email into the email field.
        *
        * @return void
        */
       public function testEmailValidation()
       {
           $this->visit('http://londonce.lan/login')
                ->submitForm('Login', ['log' => 'dfdfdfd', 'password' => '11'])
                ->see('Your Username or Password has not been recognised. Please try again.');
       }
       //Welcome Admin
       /**
        * Test: Failed Auth
        *
        * Description:
        * This will test that a user who is registered and activated in the database has failed to log in
        * and is taken back to the login page and sees that error message.
        *
        * @return void
        */
       // public function testFailAuth()
       // {
           // Sentinel::registerAndActivate([
               // 'log'    => 'admin',
               // 'password' => '123456',
           // ]);
           // $this->visit('http://londonce.lan/login')
                // ->submitForm('Login', ['email' => 'admin', 'password' => '123456'])
                // ->see('Your Username or Password has not been recognised. Please try again.');
       // }
        
       
        
}
