<?php

namespace App;

use App\{Topic, TopicValidator, Utils, Message};

class TopicController {
    public static function manageTopics($request, $response, $service) {
        $topics = Topic::selectWithPostsCount();
        $service->render('views/admin/topics/index.php', ['topics' => $topics]);
    }

    public static function create($request, $response, $service) {
        $service->render('views/admin/topics/create.php');
    }

    public static function edit($request, $response, $service) {
        $topic = Topic::selectOne(['id' => $request->id]);
        $service->render('views/admin/topics/edit.php', $topic);
    }

    public static function save($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = TopicValidator::create($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/topics/create.php', $_POST);
            exit();
        }

        $data['slug'] = Utils::slugify($data['name']);
        Topic::create($data);
        Message::success("New topic created!");
        $response->redirect(Utils::$base_url . '/admin/topics');
    }

    public static function update($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = TopicValidator::create($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/topics/edit.php', $_POST);
            exit();
        }

        Topic::update($data['id'], $data);
        Message::success("Topic updated!");
        $response->redirect(Utils::$base_url . '/admin/topics');
    }

    public static function delete($request, $response, $service) {
        Topic::delete(['id' => $request->id]);
        Message::success("Topic deleted!");
        $response->redirect(Utils::$base_url . '/admin/topics');
    }

}