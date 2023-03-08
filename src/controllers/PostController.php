<?php

namespace App;

use App\{Post, FeaturedPost, Topic, RelatedPost, PostValidator, Utils, ImageUpload, YoutubeEmbed, Message};

class PostController {
    public static function managePosts($request, $response, $service) {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $postData = Post::paginate(5, $page);
        $featuredPost = FeaturedPost::selectAll(); // Select featured post using a relational sql statement
        $featuredPostId = isset($featuredPost[0]) ? $featuredPost[0]['post_id'] : 0;
        $postData['featuredPost'] = Post::selectOne(['id' => $featuredPostId]);

        $service->render('views/admin/posts/index.php', $postData);
    }

    public static function create($request, $response, $service) {
        $topics = Topic::selectAll();
        $service->render('views/admin/posts/create.php', ['topics' => $topics]);
    }

    public static function edit($request, $response, $service) {
        $postData = Post::selectOne(['id' => $request->id]);
        $postData['topics'] = Topic::selectAll();
        $service->render('views/admin/posts/edit.php', $postData);
    }

    public static function asyncSave($request, $response, $service) {
        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $existingPost = Post::selectOne(['id' => $id]);

        if (empty($existingPost)) {
            $error = empty($_POST['title']) ? "Please consider providing a title to enable autosave" : null;
            $existingPostByTitle = Post::selectOne(['title' => $_POST['title']]);
            $error = isset($existingPostByTitle['title']) ? "Title already exists" : $error;

            $_POST['body'] = htmlentities($_POST['body'], ENT_QUOTES, "UTF-8");
            if (!empty($error)) {
                return json_encode(['error' => true, 'message' => $error]);
            } else {
                $_POST['slug'] = Utils::slugify($_POST['title']);
                $_POST['user_id'] = $_SESSION['id'];
                $new_post_id = Post::create($_POST);
                return json_encode(['post_id' => $new_post_id]);
            }
        } else {
            $updated_post_id = Post::update($id, $_POST);
            return json_encode(['success' => true]);
        }
    }

    public static function save($request, $response, $service) {
        // --> Check if user has permissions
        $data = Utils::trimFormData($_POST);
        $errors = PostValidator::create($_POST, $_FILES['image']);
        $data['body'] = YoutubeEmbed::embedYoutubeVideos($data['body']);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $_POST['topics'] = Topic::selectAll();
            $service->render('views/admin/posts/create.php', $_POST);
            exit();
        }

        // Delete images in folder that were not finally submitted with the post
        if (isset($_SESSION['post_images'])) {
            foreach ($_SESSION['post_images'] as $imageName) {
                $postHasCurrentImage = strpos($data['body'], $imageName) !== false;
                if (!$postHasCurrentImage) {
                    \unlink(ImageUpload::$inPostImagesDir . $imageName);
                }
            }
        }
        unset($_SESSION['post_images']);

        $data['body'] = htmlentities($data['body']);
        $data['slug'] = Utils::slugify($data['title']);
        $data['published'] = isset($_POST['published']) ? 1 : 0;
        $data['user_id'] = $_SESSION['id'];

        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $path_info = pathinfo($_FILES['image']['name']);
            $filename = $data['slug'] . '_' . time() . '.' . $path_info['extension'];

            $uploadStatus = ImageUpload::featuredImage($_FILES['image'], $filename);
            $data['image'] = $filename;
        }

        !empty($_POST['id']) ? Post::update($_POST['id'], $data) : Post::create($data);
        Message::success("New post created!");
        $response->redirect(Utils::$base_url . '/admin/posts');
    }

    public static function update($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $errors = PostValidator::update($_POST, $_FILES['image']);
        $data['body'] = YoutubeEmbed::embedYoutubeVideos($data['body']);

        if (!empty($errors)) {
            $_POST['errors'] = $errors;
            $_POST['topics'] = Topic::selectAll();
            $service->render('views/admin/posts/edit.php', $_POST);
            exit();
        }

        // Delete images in folder that were not finally submitted with the post
        if (isset($_SESSION['post_images'])) {
            foreach ($_SESSION['post_images'] as $imageName) {
                $postHasCurrentImage = strpos($data['body'], $imageName) !== false;
                if (!$postHasCurrentImage) {
                    \unlink(ImageUpload::$inPostImagesDir . $imageName);
                }
            }
        }
        unset($_SESSION['post_images']);

        $data['body'] = htmlentities($data['body']);
        $data['slug'] = Utils::slugify($data['title']);
        $data['published'] = isset($_POST['published']) ? 1 : 0;

        $previousPost = Post::selectOne(['id' => $data['id']]);
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            // Update image
            $path_info = pathinfo($_FILES['image']['name']);
            $filename = $previousPost['slug'] . '_' . time() . '.' . $path_info['extension'];
            $uploadStatus = ImageUpload::featuredImage($_FILES['image'], $filename);
            $data['image'] = $filename;

            // delete previous image
            \unlink(ImageUpload::$featuredImagesDir . $previousPost['image']);
        }

        Post::update($_POST['id'], $data);
        Message::success("Post updated!");
        $response->redirect(Utils::$base_url . '/admin/posts');
    }

    public static function uploadPostBodyImage($request, $response, $service) {
        $path_info = pathinfo($_FILES['image']['name']);
        $filename = $path_info['filename'] . '_' . time() . '.' . $path_info['extension'];
        $imageUrl = Utils::$base_url . '/assets/images/in_post_images/' . $filename;
        $uploadStatus = ImageUpload::inPostImage($_FILES['image'], $filename);

        if (isset($_SESSION['post_images'])) {
            array_push($_SESSION['post_images'], $filename);
        } else {
            $_SESSION['post_images'] = [];
            array_push($_SESSION['post_images'], $filename);
        }

        return json_encode(['url' => $imageUrl]);
    }

    public static function relatedPosts($request, $response, $service) {
        $pageData = Post::paginate(100);
        $pageData['post'] = Post::selectOne(['id' => $request->post_id]);
        $pageData['relatedPosts'] = Post::getRelatedPostsByPostId($request->post_id);

        $service->render('views/admin/posts/related_posts.php', $pageData);
    }

    public static function saveRelatedPosts($request, $response, $service) {
        $relatedPostIds = isset($_POST['relatedPostIds']) ? $_POST['relatedPostIds'] : null;
        $post_id = $_POST['post_id'];

        RelatedPost::delete(['post_id' => $post_id], 'AND');
        foreach ($relatedPostIds as $key => $pId) {
            $newRecord = [
                'post_id' => $post_id,
                'related_post_id' => $pId,
                'sort_order' => $_POST['sort_order'][$key],
            ];
            RelatedPost::create($newRecord);
        }
        Message::success("Related posts saved.");
        $response->redirect(Utils::$base_url . '/admin/posts');
    }

    public static function togglePublish($request, $response, $service) {
        Post::togglePublished($request->id);
        Message::success("Publish status changed");
        $response->redirect(Utils::$base_url . '/admin/posts');
    }

    public static function asyncAdminSearch($request, $response, $service) {
        $posts = Post::search($_POST['searchTerm']);
        return json_encode($posts);
    }

    public static function asyncFilter($request, $response, $service) {
        $_SESSION['postsFilter'] = $_GET['filter'];
        $postsData = Post::paginate(5, 1);
        return json_encode($postsData);
    }

    public static function updateFeaturedPost($request, $response, $service) {
        $data = Utils::trimFormData($_POST);
        $post = Post::selectOne(['title' => $data['title']]);

        if (empty($post)) {
            $_SESSION['message'] = "Post not found";
            $_SESSION['messageType'] = "warning";
            self::managePosts($request, $response, $service);
            exit();
        }

        Message::success("Featured post has been updated");
        FeaturedPost::delete([]);
        FeaturedPost::create(['post_id' => $post['id']]);
        $response->redirect(Utils::$base_url . '/admin/posts');
    }

    public static function showTrash($request, $response, $service) {
        $posts = Post::getAllTrashed();
        $service->render('views/admin/posts/trash.php', ['posts' => $posts]);
    }

    public static function trash($request, $response, $service) {
        $id = $request->id;
        $count = Post::update($id, ['deleted_at' => date('Y-m-d H:i:s')], false);
        Message::success("Post has been trashed");
        $response->redirect(Utils::$base_url . '/admin/posts');
    }

    public static function confirmDelete($request, $response, $service) {
        $post = Post::selectOne(['id' => $request->id]);
        $service->render('views/admin/posts/confirm_delete.php', $post);
    }

    public static function permanentlyDelete($request, $response, $service) {
        $id = $request->id;
        $post = Post::selectOne(['id' => $id]);
        Post::delete(['id' => $id]);

        if ($post['image']) {
            \unlink(ImageUpload::$inPostImagesDir . $post['image']);
        }

        Message::success("Post has been permanently deleted");
        $response->redirect(Utils::$base_url . '/admin/posts/trash');
    }

    public static function restore($request, $response, $service) {
        $id = $request->id;
        Post::update($id, ['deleted_at' => NULL]);
        Message::success("Post has been restored");
        $response->redirect(Utils::$base_url . '/admin/posts/trash');
    }
}
