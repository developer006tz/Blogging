<?php

namespace App;

use App\{User, Role, UserValidator, Utils, ImageUpload, Message};

class UserController {
    public static function manageUsers($request, $response, $service) {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $usersData = User::paginate(5, $page);
        $service->render('views/admin/users/index.php', $usersData);
    }

    public static function create($request, $response, $service) {
        $roles = Role::selectAll();
        $service->render('views/admin/users/create.php', ['roles' => $roles]);
    }

    public static function update($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : NULL;
        $errors = UserValidator::update($data, $avatarFile);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $_POST['roles'] = Role::selectAll();
            $service->render('views/admin/users/edit.php', $_POST);
            exit();
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            $path_info = pathinfo($_FILES['avatar']['name']);
            $filename = strtolower($data['username']) . '_' . time() . '.' . $path_info['extension'];
            $uploadSuccess = ImageUpload::avatar($avatarFile, $filename);
            $data['image'] = $uploadSuccess ? $filename : NULL;

            // delete previous image
            $prevUser = User::selectOne(['id' => $data['id']]);
            \unlink(ImageUpload::$avatarDir . $prevUser['image']);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $data['token'] = bin2hex(random_bytes(50)); // a unique token
        User::update($data['id'], $data);

        Message::success("User updated!");
        $response->redirect(Utils::$base_url . '/admin/users');
    }

    public static function save($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $avatarFile = !empty($_FILES['avatar']) ? $_FILES['avatar'] : NULL;
        $errors = UserValidator::create($data, $avatarFile);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $_POST['roles'] = Role::selectAll();
            $service->render('views/admin/users/create.php', $_POST);
            exit();
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            $path_info = pathinfo($_FILES['avatar']['name']);
            $filename = strtolower($data['username']) . '_' . time() . '.' . $path_info['extension'];
            $uploadSuccess = ImageUpload::avatar($avatarFile, $filename);
            $data['image'] = $uploadSuccess ? $filename : NULL;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $data['token'] = bin2hex(random_bytes(50)); // a unique token
        $data['role_id'] = empty($data['role_id']) ? NULL : $data['role_id'];
        $user_id = User::create($data);

        Message::success("New user created!");
        $response->redirect(Utils::$base_url . '/admin/users');
    }

    public static function edit($request, $response, $service) {
        $userData = User::selectOne(['id' => $request->id]);
        $userData['password'] = '';
        $userData['roles'] = Role::selectAll();
        $service->render('views/admin/users/edit.php', $userData);
    }

    public static function delete($request, $response, $service) {
        $id = $request->id;
        $user = User::selectOne(['id' => $id]);
        User::delete(['id' => $id]);

        // delete image
        \unlink(ImageUpload::$avatarDir . $user['image']);

        Message::success("User deleted!");
        $response->redirect(Utils::$base_url . '/admin/users');
    }

    public static function asyncAdminSearch($request, $response, $service) {
        $users = User::searchUsers($_POST['searchTerm']);
        return json_encode($users);
    }

    public static function asyncFilter($request, $response, $service) {
        $_SESSION['usersFilter'] = $_GET['filter'];
        $usersData = User::paginate(5, 1);
        return json_encode($usersData);
    }
}
