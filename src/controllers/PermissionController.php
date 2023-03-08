<?php

namespace App;

use App\{Permission, PermissionValidator, Utils, Message};

class PermissionController {
    public static function managePermissions($request, $response, $service) {
        $permissions = Permission::selectAll();
        $service->render('views/admin/permissions/index.php', ['permissions' => $permissions]);
    }

    public static function create($request, $response, $service) {
        $service->render('views/admin/permissions/create.php');
    }

    public static function save($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = PermissionValidator::create($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/permissions/create.php', $_POST);
            exit();
        }

        $data['slug'] = Utils::slugify($data['name']);
        Permission::create($data);
        Message::success("Permission created!");
        $response->redirect(Utils::$base_url . '/admin/permissions');
    }

    public static function edit($request, $response, $service) {
        $permission = Permission::selectOne(['id' => $request->id]);
        $service->render('views/admin/permissions/edit.php', $permission);
    }

    public static function update($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = PermissionValidator::update($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/permissions/edit.php', $_POST);
            exit();
        }

        $data['slug'] = Utils::slugify($data['name']);
        Permission::update($data['id'], $data);
        Message::success("Permission updated!");
        $response->redirect(Utils::$base_url . '/admin/permissions');
    }

    public static function delete($request, $response, $service) {
        Permission::delete(['id' => $request->id]);
        Message::success("Permission deleted!");
        $response->redirect(Utils::$base_url . '/admin/permissions');
    }
}