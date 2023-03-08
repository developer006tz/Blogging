<?php

namespace App;

use App\{Post, FeaturedPost, Topic, PostValidator, Utils, ImageUpload, PostCollection, PostCollectionValidator, Message};

class PostCollectionController {
    public static function index($request, $response, $service) {
        $pageData['collections'] = PostCollection::withPostsCount();
        $service->render('views/admin/collections/index.php', $pageData);
    }

    public static function assignPosts($request, $response, $service) {
        $pageData = Post::paginate(100);
        $pageData['collection'] = PostCollection::selectOne(['id' => $request->collection_id]);
        $pageData['collectionPosts'] = PostCollection::getPostsByCollectionId($request->collection_id, false);

        $service->render('views/admin/collections/collection_posts.php', $pageData);
    }

    public static function savePosts($request, $response, $service) {
        $postIds = isset($_POST['postIds']) ? $_POST['postIds'] : null;
        $collection_id = $_POST['collection_id'];

        PostCollection::removePosts($collection_id);

        foreach ($postIds as $key => $pId) {
            $newRecord = [
                'collection_id' => $collection_id,
                'post_id' => $pId,
                'sort_order' => $_POST['sort_order'][$key],
            ];
            PostCollection::addPost($newRecord);
        }
        Message::success("Post collection updated");
        $response->redirect(Utils::$base_url . '/admin/collections');
    }

    public static function edit($request, $response, $service) {
        $pageData = PostCollection::selectOne(['id' => $request->id]);
        $service->render('views/admin/collections/edit.php', $pageData);
    }

    public static function update($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = PostCollectionValidator::update($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/collections/edit.php', $_POST);
            exit();
        }

        $data['slug'] = Utils::slugify($data['title']);
        PostCollection::update($data['id'], $data);
        Message::success("Post collection updated");
        $response->redirect(Utils::$base_url . '/admin/collections');
    }

    public static function create($request, $response, $service) {
        $service->render('views/admin/collections/create.php');
    }

    public static function save($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = PostCollectionValidator::create($data);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $service->render('views/admin/collections/create.php', $_POST);
            exit();
        }

        $existingCollections = PostCollection::selectAll();
        $data['position'] = count($existingCollections) + 1;
        $data['slug'] = Utils::slugify($data['title']);
        PostCollection::create($data);
        Message::success("Post collection created");
        $response->redirect(Utils::$base_url . '/admin/collections');
    }

    public static function asyncSavePositions($request, $response, $service) {
        $positions = json_decode($_POST['positions']);

        foreach ($positions as $item) {
            PostCollection::update($item->id, ['position' => $item->position]);
        }
    }

    public static function confirmDelete($request, $response, $service) {
        $pageData = PostCollection::selectOne(['slug' => $request->slug]);
        $service->render('views/admin/collections/confirm_delete.php', $pageData);
    }

    public static function deleteCollection($request, $response, $service) {
        PostCollection::delete(['id' => $request->id]);
        PostCollection::removePosts($request->id);
        Message::success("Post collection deleted!");
        $response->redirect(Utils::$base_url . '/admin/collections');
    }
}
