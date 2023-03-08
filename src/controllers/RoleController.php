<?php

namespace App;

use App\{Role, RoleValidator, Utils, Message};

class RoleController {
    public static function manageRoles($request, $response, $service) {
        $roles = Role::selectWithUserCount();
        $service->render('views/admin/roles/index.php', ['roles' => $roles]);
    }

    public static function create($request, $response, $service) {
        $service->render('views/admin/roles/create.php');
    }

    public static function save($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = RoleValidator::create($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/roles/create.php', $_POST);
            exit();
        }

        $data['slug'] = Utils::slugify($data['name']);
        Role::create($data);
        Message::success("Role created!");
        $response->redirect(Utils::$base_url . '/admin/roles');
    }

    public static function edit($request, $response, $service) {
        $role = Role::selectOne(['id' => $request->id]);
        $service->render('views/admin/roles/edit.php', $role);
    }

    public static function update($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = RoleValidator::update($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/roles/edit.php', $_POST);
            exit();
        }

        $data['slug'] = Utils::slugify($data['name']);
        Role::update($data['id'], $data);
        Message::success("Role updated!");
        $response->redirect(Utils::$base_url . '/admin/roles');
    }

    public static function showAssignPermissions($request, $response, $service) {
        $assignedPermissionIds = Role::getPermissionIds($request->role_id);
        $role = Role::selectOne(['id' => $request->role_id]);
        $allPermissions = Permission::selectAll();

        $data = [
            'role' => $role,
            'assignedPermissionIds' => $assignedPermissionIds,
            'allPermissions' => $allPermissions,
        ];
        $service->render('views/admin/roles/assign_permissions.php', $data);
    }

    public static function assignPermission($request, $response, $service) {
        $role_id = $request->role_id;
        $selectedPermissionIds = array_keys($_POST);

        Role::assignPermissions($role_id, $selectedPermissionIds);
        Message::success("Successfully assigned permissions to role!");
        $response->redirect(Utils::$base_url . '/admin/roles');
    }

    public static function delete($request, $response, $service) {
        Role::delete(['id' => $request->id]);
        Message::success("Role deleted!");
        $response->redirect(Utils::$base_url . '/admin/roles');
    }
}