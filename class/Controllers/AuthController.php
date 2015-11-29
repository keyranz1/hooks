<?php

    namespace Controllers;

    use Framework\DB;
    use Framework\Redirect;
    use Framework\Route;

    class AuthController{

        private static $level;

        public static function authenticate($level, $redirect){
            $_SESSION['redirect'] = $redirect;
            if(isset($_SESSION['xoauth.token']) &&  isset($_SESSION['xoauth.level'])){
                if($_SESSION['xoauth.level'] >= $level){
                    if(self::currentURL() !== $_SESSION['redirect'] && $_SESSION['redirect'] !== null){
                        Redirect::to($_SESSION['redirect']);
                    }
                    return true;
                } else {
                    Route::view('pages.login', array('message' => 'You do not have right access for this link.'));
                    die();
                }
            } else {
                Redirect::to('login');
                return false;
            }
        }

        public static function grantToken(){
            $_SESSION['xoauth.token'] = uniqid();
            $_SESSION['xoauth.level'] = self::$level;
        }

        public static function voidToken($redirect = null){
            unset($_SESSION['xoauth.token']);
            unset($_SESSION['xoauth.level']);
            $_SESSION['redirect'] = $redirect;
            Route::view('pages.login', array('message' => 'You have been logged out.'));
        }

        public static function login(){

            $message = "Please login using your username/email and password";

            if(isset($_POST['username']) && isset($_POST['password'])){
                if(self::verify()){
                    self::grantToken();
                    header('Location:'.$_SESSION['redirect']);
                } else {
                    $message = "Failed to Login. Check your username and password";
                }
            }
            Route::view('pages.login', array('message' => $message));

        }

        public static function verify(){

            if(isset($_POST["username"]) && isset($_POST["password"])){

                $row = self::getRow($_POST["username"]);

                if($row){
                    //Verify Password
                    if (password_verify($_POST["password"], $row->password)) {
                        self::$level = $row->access;
                        return true;
                    }
                }


            }

            return false;

        }

        public static function register(){

            if(!REGISTER){
                die("Registration is not allowed. Please <a href='login'>login</a> from here.");
            }
            
            RequireAuth(REGISTER_LEVEL);
            

            $message = "Please fill the following fields to register yourself as a user:";

            if(isset($_POST['username'])){
                if($_POST['password'] === $_POST['rpassword']){
                    if(!DB::exists(USER_TABLE,array('username' , '=' , $_POST['username']))){
                        if(!DB::exists(USER_TABLE,array('email' , '=' , $_POST['email']))){

                            $pass =  password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 12));

                            $post = array(
                                'username' => $_POST['username'],
                                'name' => $_POST['name'],
                                'email' => $_POST['email'],
                                'password' => $pass,
                                'access' => 1
                            );

                            $insert = DB::insert(USER_TABLE,$post);

                            if($insert){
                                $message = "You have been registered. Please login:";
                                Route::view('pages.login', array('message' => $message));
                                return true;
                            } else {
                                $message = "Failed to register you as the user";
                            }

                        } else {
                            $message = "The email is already registered. <a href='forgot'>Reset</a> your password here.";
                        }
                    } else {
                        $message = "The username is not available";
                    }
                } else {
                    $message = "Passwords do not match";
                }
            }


            Route::view('pages.register', array('message' => $message));

        }

        public static function getRow($username){
            $rows = DB::get(USER_TABLE,array('username','=',$username));
            if(count($rows) === 1){
                return $rows[0];
            } else {
                //Maybe Email...
                $rows = DB::get(USER_TABLE,array('email','=',$username));
                if(count($rows) === 1) {
                    return $rows[0];
                }

            }
            return false;
        }

        public static function sendResetLink($email_to){

            $email_from = "no-reply@".$_SERVER['HTTP_HOST'];
            $subject = "Password Reset Link";

            $oauth = rand(1000,9999).'-'.uniqid().'-'.rand(1000000,9999999).'-'.uniqid();
            $reset_link = BASE_URL."reset/oauth/" . $oauth;

            DB::update(USER_TABLE,array('oauth' => $oauth), array('email' ,'=', $email_to));

            $message = "Here is your password reset link: ". $reset_link;

            $headers   = array();
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/plain; charset=iso-8859-1";
            $headers[] = "From: " . $email_from;
            $headers[] = "Subject: " . $subject;
            $headers[] = "X-Mailer: PHP/".phpversion();


            mail($email_to, $subject, $message, implode("\r\n", $headers));
            return true;
        }

        public static function forgot(){

            $message = "Enter your username or email to get a reset link: ";

            if(isset($_POST["username"])){
                $row = self::getRow($_POST['username']);
                if($row){
                    if(self::sendResetLink($row->email)){
                        $message = "Email Reset sent. Please check your inbox.";
                        Route::view('pages.login', array('message' => $message));
                        return false;
                    }
                } else {
                    $message = "No such username/email. Please try again";
                }
            }

            Route::view('pages.forgot', array('message' => $message));

        }

        public static function reset($oauth){
            if(self::verifyAuth($oauth)){
                Route::view('pages.reset', array('message' => 'Please enter new password:', 'oauth' => $oauth));
            } else {
                Route::view('pages.login', array('message' => 'Invalid Link. Please login:'));
            }
        }

        public static function newpass(){
            if(isset($_POST['oauth']) && isset($_POST['password']) && isset($_POST['rpassword'])){
                if(self::verifyAuth($_POST['oauth'])){
                    if($_POST['password'] === $_POST['rpassword']){

                        $pass =  password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 12));
                        $post = array(
                            'password' => $pass,
                            'oauth' => NULL
                        );
                        if(DB::update(USER_TABLE, $post ,array('oauth','=',$_POST['oauth']))){
                            Route::view('pages.login', array('message' => 'Password Reset. Please login:'));
                            return true;
                        }else {
                            Route::view('pages.reset', array('message' => 'Failed to reset password.', 'oauth' => $_POST['oauth']));
                            return false;
                        }
                    }else {
                        Route::view('pages.reset', array('message' => 'Passwords do not match. Try again:', 'oauth' => $_POST['oauth']));
                        return false;
                    }
                } else {
                    Route::view('pages.reset', array('message' => 'Invalid Authentication Code.', 'oauth' => $_POST['oauth']));
                    return false;
                }
            }

            Route::view('pages.login', array('message' => 'Invalid Link. Please login:'));
            return false;

        }

        public static function verifyAuth($oauth){
            $rows = DB::get(USER_TABLE,array('oauth','=',$oauth));
            if(count($rows)){
                $row = $rows[0];

                $now = new \DateTime('now');
                $upd = new \DateTime($row->updated_at);

                $diff = $now->diff($upd);

                if($diff->days <= 1){
                    return true;
                } else {
                    return false;
                }
            }

        }

        public static function currentURL(){
            return $_SERVER['REQUEST_SCHEME']. "://" .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

    }