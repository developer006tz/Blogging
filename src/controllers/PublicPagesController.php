<?php

namespace App;

use App\{FeaturedPost, Post, PostCollection, Message, Utils};

class PublicPagesController {
    public static function index($request, $response, $service) {
        $service->pageTitle = 'Blog | Awesome articles about life, love and happiness!';
        $pageData['featuredPost'] = FeaturedPost::getFeaturedPost();
        $pageData['collections'] = PostCollection::getFeaturedCollections();
        $pageData['topics'] = Topic::selectAll();

        $service->render('views/index.php', $pageData);
    }

    public static function showSinglePost($request, $response, $service) {
        $pageData['post'] = Post::getSinglePostBySlug($request->slug);

        if (empty($pageData['post'])) {
            Message::warning('Post not found. Either post has been deleted or unpublished');
            $response->redirect(Utils::$base_url . '/');
            self::index($request, $response, $service);
            exit();
        }
        $post_id = $pageData['post']['id'];
        // Update view count
        Post::incrementViews($post_id);

        $pageData['relatedPosts'] = Post::getRelatedPostsByPostId($post_id);
        $service->render('views/single_post.php', $pageData);
    }

    public static function showAllPosts($request, $response, $service) {
        $pageData['topics'] = Topic::selectAll();

        $currentPage = isset($_GET['page']) ? $_GET['page'] : null;
        if (!empty($currentPage)) {
            $paginatedPosts = Post::selectAllPosts($currentPage);
            $formattedPosts = Utils::formattedPostFields($paginatedPosts);
            return json_encode($formattedPosts);
        }

        $searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
        if (!empty($searchTerm)) {
            $pageData['posts'] = Post::search($searchTerm, false);
            $pageData['showingSearchResults'] = true;
            $service->render('views/all_posts.php', $pageData);
            exit();
        }

        $pageData['posts'] = Post::selectAllPosts();
        $service->render('views/all_posts.php', $pageData);
    }

    public static function showCollectionPosts($request, $response, $service) {
        $collection = PostCollection::selectOne(['slug' => $request->slug]);
        $currentPage = isset($_GET['page']) ? $_GET['page'] : null;
        if (!empty($currentPage)) {
            $paginatedPosts = PostCollection::getPostsByCollectionId($collection['id'], true, $currentPage);
            $formattedPosts = Utils::formattedPostFields($paginatedPosts);
            return json_encode($formattedPosts);
        }

        $pageData['collection'] = $collection;
        $pageData['posts'] = PostCollection::getPostsByCollectionId($collection['id'], true);
        $pageData['topics'] = Topic::selectAll();
        $service->render('views/collection_posts.php', $pageData);
    }

    public static function showTopicPosts($request, $response, $service) {
        $topicSlug = $request->topic_slug;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : null;
        if (!empty($currentPage)) {
            $topicPosts = Post::getPostsByTopicSlug($topicSlug, $currentPage);
            $formattedTopicPosts = Utils::formattedPostFields($topicPosts);
            return json_encode($formattedTopicPosts);
        }

        $pageData['posts'] = Post::getPostsByTopicSlug($topicSlug);
        $pageData['topic'] = Topic::selectOne(['slug' => $topicSlug]);
        $pageData['topics'] = Topic::selectAll();
        $service->render('views/topic_posts.php', $pageData);
    }

    public static function showUserPosts($request, $response, $service) {
        $userId = $_GET['id'];
        $currentPage = isset($_GET['page']) ? $_GET['page'] : null;
        if (!empty($currentPage)) {
            $userPosts = Post::getPostsByUserId($userId, $currentPage);
            $formattedUserPosts = Utils::formattedPostFields($userPosts);
            return json_encode($formattedUserPosts);
        }

        $pageData['posts'] = Post::getPostsByUserId($userId);
        $pageData['user'] = User::selectOne(['id' => $userId]);
        $pageData['topics'] = Topic::selectAll();
        $service->render('views/user_posts.php', $pageData);
    }
}
