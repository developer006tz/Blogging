<?php

namespace App;

session_start();

use App\{User, Post, Utils, UserValidator, Mail, PasswordReset, ImageUpload, Message};

class AuthController {
    public static function showLogin($request, $response, $service) {
        $service->render('views/login.php');
    }

    public static function showRegister($request, $response, $service) {
        $service->render('views/register.php');
    }

    private static function logInByUserId($user_id) {
        $user = User::selectOne(['id' => $user_id]);
        if (isset($user)) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['image'] = $user['image'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['permissions'] = User::getUserPermissions($user['id']);
        }
        return $user;
    }

    public static function login($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = UserValidator::login($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/login.php', $_POST);
            exit();
        }

        $loginDetails = [
            'email' => $data['username'],
            'username' => $data['username'],
        ];
        $user = User::selectOne($loginDetails, 'OR');

        if (!empty($user) && password_verify($data['password'], $user['password'])) {
            self::logInByUserId($user['id']);
            Message::success("Login Successful!");

            if (isset($_POST['remember-me'])) {
                $token = bin2hex(random_bytes(50)); // a unique token
                $user_id = User::update($user['id'], ['token' => $token]);
                setCookie('token', $token, time() + (30 * 24 * 60 * 60));
            }
            $response->redirect(Utils::$base_url . '/');
        } else {
            $_POST['errors'] = ["Wrong username or password"];
            $service->render('views/login.php', $_POST);
        }
    }

    public static function register($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $imageFile = isset($_FILES['image']) ? $_FILES['image'] : NULL;
        $errors = UserValidator::create($data, $_FILES['avatar']);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/register.php', $_POST);
            exit();
        }

        if (isset($imageFile)) {
            $path_info = pathinfo($imageFile['name']);
            $filename = strtolower($data['username']) . '_' . time() . '.' . $path_info['extension'];
            $uploadSuccess = ImageUpload::avatar($_FILES['avatar'], $filename);
            $data['image'] = $uploadSuccess ? $filename : NULL;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $data['token'] = bin2hex(random_bytes(50)); // a unique token
        $user_id = User::create($data);
        self::logInByUserId($user_id);
        Message::success("Thanks for signing up! You are now logged in.");
        $response->redirect(Utils::$base_url . '/');
    }

    public static function rememberMe() {
        if (!empty($_COOKIE['token'])) {
            $user = User::selectOne(['token' => $_COOKIE['token']]);
            self::logInByUserId($user['id']);
        }
    }

    public static function logout($request, $response, $service) {
        session_start();
        unset($_SESSION['id']);
        unset($_SESSION['username']);
        setCookie('token', '', -3600);
        session_destroy();
        $response->redirect(Utils::$base_url . '/');
    }

    public static function forgotPassword($request, $response, $service) {
        $service->render('views/forgot_password.php');
    }

    public static function sendResetLink($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = UserValidator::email($data['email']);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/forgot_password.php', $_POST);
            exit();
        }

        $user = User::selectOne(['email' => $data['email']]);
        if (!empty($user)) {
            $passwordResetData = [
                'token' => bin2hex(random_bytes(50)), // a unique token
                'user_id' => $user['id'],
                'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day', time())), // token expires 1 day from now
            ];
            PasswordReset::create($passwordResetData);

            // Send password reset email to user
            $message = file_get_contents(BASE_URL . '/email-template/reset-link?token=' . urlencode($passwordResetData['token']));
            Utils::dd($message);
            Mail::send($user['email'], $message);
        }

        $response->redirect(Utils::$base_url . '/reset-email-sent');
    }

    public static function showResetEmailSent($request, $response, $service) {
        $service->render('views/confirm_reset_email.php');
    }

    public static function showResetPasswordForm($request, $response, $service) {
        $token = $request->token;
        $resetData = PasswordReset::selectOne(['token' => $token]);

        if (!empty($resetData) && date('Y-m-d H:i:s') < $resetData['expires_at']) {
            $service->render('views/reset_password.php', ['token' => $token]);
        } else {
            $_SESSION['message'] = "Unable to reset password because user does not exist or link has expired.";
            $_SESSION['messageType'] = "warning";
            $response->redirect(Utils::$base_url . '/');
        }
    }

    public static function saveNewPassword($request, $response, $service) {
        $errors = UserValidator::passwords($_POST['password'], $_POST['passwordConf']);
        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/reset_password.php', $_POST);
            exit();
        }

        $tokenInfo = PasswordReset::selectOne(['token' => $_POST['token']]);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        User::update($tokenInfo['user_id'], ['password' => $password]);
        PasswordReset::delete($tokenInfo['id']);

        $service->render('views/login.php');
    }

}
