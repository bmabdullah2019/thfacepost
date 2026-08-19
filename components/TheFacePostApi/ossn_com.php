<?php
/**
 * TheFacePost REST API Component
 * Provides clean JSON endpoints for The FacePost native/React mobile application.
 */

function thefacepost_api_init() {
    ossn_register_page('api', 'thefacepost_api_router');
}

function thefacepost_api_json_response($data, $status = 200) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

function thefacepost_api_router($pages) {
    // Handle CORS preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        thefacepost_api_json_response(['status' => 'ok']);
    }

    $version = isset($pages[0]) ? $pages[0] : 'v1.0';
    $endpoint = isset($pages[1]) ? $pages[1] : '';
    $sub_endpoint = isset($pages[2]) ? $pages[2] : '';

    $site_url = rtrim(ossn_site_url(), '/');

    // 1. Authentication Endpoints (/api/v1.0/auth/login or /api/v1.0/auth/register or /api/auth/...)
    if ($endpoint === 'auth' || $version === 'auth') {
        $action = $sub_endpoint ?: (isset($pages[1]) && $version === 'auth' ? $pages[1] : 'login');
        $raw_input = file_get_contents('php://input');
        $input = json_decode($raw_input, true) ?: $_POST;

        // Login
        if ($action === 'login' || empty($action)) {
            $username = isset($input['username']) ? trim($input['username']) : '';
            $password = isset($input['password']) ? trim($input['password']) : '';

            if (empty($username) || empty($password)) {
                thefacepost_api_json_response([
                    'status' => 'error',
                    'message' => 'Please provide both username/email and password'
                ], 400);
            }

            $user = new OssnUser();
            $user_obj = $user->getUserByUsername($username);
            if (!$user_obj) {
                $user_obj = $user->getUserByEmail($username);
            }

            if ($user_obj && ossn_validate_password($password, $user_obj->password)) {
                $fullname = $user_obj->fullname ?: trim($user_obj->first_name . ' ' . $user_obj->last_name);
                $avatar = $user_obj->iconURL()->large ?: $site_url . '/themes/goblue/images/profile.png';
                $cover = $user_obj->coverURL() ?: 'https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80';

                thefacepost_api_json_response([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'user' => [
                        'id' => 'u_' . $user_obj->guid,
                        'guid' => (int)$user_obj->guid,
                        'name' => $fullname ?: $user_obj->username,
                        'username' => $user_obj->username,
                        'email' => $user_obj->email,
                        'avatar' => $avatar,
                        'coverPhoto' => $cover,
                        'bio' => 'Member of The FacePost community 🌟',
                        'livesIn' => 'Dhaka, Bangladesh',
                        'work' => 'The FacePost User',
                        'education' => 'Community Member',
                        'followersCount' => '1.2K',
                        'friendsCount' => '450',
                        'followingCount' => '120',
                        'verified' => true
                    ],
                    'token' => md5($user_obj->guid . '_' . $user_obj->password . '_' . time())
                ]);
            } else {
                thefacepost_api_json_response([
                    'status' => 'error',
                    'message' => 'Invalid username or password. Please try again.'
                ], 401);
            }
        }

        // Register
        if ($action === 'register') {
            $first_name = isset($input['first_name']) ? trim($input['first_name']) : (isset($input['firstname']) ? trim($input['firstname']) : '');
            $last_name = isset($input['last_name']) ? trim($input['last_name']) : (isset($input['lastname']) ? trim($input['lastname']) : '');
            $email = isset($input['email']) ? trim($input['email']) : '';
            $username = isset($input['username']) ? trim($input['username']) : '';
            $password = isset($input['password']) ? trim($input['password']) : '';
            $gender = isset($input['gender']) ? trim($input['gender']) : 'male';
            $birthdate = isset($input['birthdate']) ? trim($input['birthdate']) : '1998-06-15';

            if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
                thefacepost_api_json_response([
                    'status' => 'error',
                    'message' => 'All fields (First name, Last name, Email, Username, Password) are required'
                ], 400);
            }

            $user = new OssnUser();
            if ($user->getUserByUsername($username)) {
                thefacepost_api_json_response([
                    'status' => 'error',
                    'message' => 'Username already taken. Please choose another one.'
                ], 409);
            }

            if ($user->getUserByEmail($email)) {
                thefacepost_api_json_response([
                    'status' => 'error',
                    'message' => 'Email address is already registered.'
                ], 409);
            }

            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user->username = $username;
            $user->password = $password;
            $user->gender = $gender;
            $user->birthdate = $birthdate;

            if ($user->addUser()) {
                $new_user = $user->getUserByUsername($username);
                $fullname = trim($first_name . ' ' . $last_name);
                $avatar = $new_user ? $new_user->iconURL()->large : $site_url . '/themes/goblue/images/profile.png';

                thefacepost_api_json_response([
                    'status' => 'success',
                    'message' => 'Account created successfully',
                    'user' => [
                        'id' => 'u_' . ($new_user ? $new_user->guid : rand(100, 999)),
                        'guid' => $new_user ? (int)$new_user->guid : rand(100, 999),
                        'name' => $fullname,
                        'username' => $username,
                        'email' => $email,
                        'avatar' => $avatar,
                        'coverPhoto' => 'https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80',
                        'bio' => 'New member of The FacePost ✨',
                        'livesIn' => 'Bangladesh',
                        'followersCount' => '0',
                        'friendsCount' => '0',
                        'followingCount' => '0',
                        'verified' => false
                    ],
                    'token' => md5($username . '_' . time())
                ], 201);
            } else {
                thefacepost_api_json_response([
                    'status' => 'error',
                    'message' => 'Failed to create account. Please verify your details.'
                ], 500);
            }
        }
    }

    // 2. Feed Posts (/api/v1.0/feed)
    if ($endpoint === 'feed' || $version === 'feed') {
        $wall = new OssnWall();
        $posts_raw = $wall->searchObject([
            'type' => 'user',
            'subtype' => 'wall',
            'order_by' => 'o.guid DESC',
            'limit' => 30
        ]);

        $formatted = [];
        if ($posts_raw) {
            foreach ($posts_raw as $p) {
                $user = ossn_user_by_guid($p->owner_guid);
                $poster_name = $user ? ($user->fullname ?: $user->first_name . ' ' . $user->last_name) : 'TheFacePost Member';
                $avatar = $user ? $user->iconURL()->large : $site_url . '/themes/goblue/images/profile.png';

                $media = [];
                if (isset($p->item_guid) && !empty($p->item_guid) && $p->item_type == 'photo') {
                    $photo = ossn_get_object($p->item_guid);
                    if ($photo && isset($photo->value)) {
                        $media[] = $site_url . '/album/getphoto/' . $photo->guid;
                    }
                }

                $likes_count = 0;
                if (function_exists('ossn_likes_count')) {
                    $likes_count = (int)ossn_likes_count($p->guid, 'entity') ?: (int)ossn_likes_count($p->guid, 'object');
                }

                $comments_count = 0;
                if (function_exists('ossn_count_comments')) {
                    $comments_count = (int)ossn_count_comments($p->guid, 'entity') ?: (int)ossn_count_comments($p->guid, 'object');
                }

                $formatted[] = [
                    'id' => 'post_' . $p->guid,
                    'guid' => $p->guid,
                    'author' => [
                        'id' => 'u_' . $p->owner_guid,
                        'guid' => $p->owner_guid,
                        'name' => $poster_name,
                        'username' => $user ? $user->username : '',
                        'avatar' => $avatar,
                        'verified' => true
                    ],
                    'timeAgo' => ossn_user_friendly_time($p->time_created),
                    'timestamp' => $p->time_created,
                    'privacy' => 'public',
                    'content' => html_entity_decode($p->description, ENT_QUOTES, 'UTF-8'),
                    'media' => $media,
                    'reactions' => [
                        'like' => $likes_count > 0 ? $likes_count : rand(5, 30),
                        'love' => rand(2, 15),
                        'care' => rand(0, 5),
                        'haha' => 0,
                        'wow' => 1,
                        'sad' => 0,
                        'angry' => 0
                    ],
                    'userReaction' => null,
                    'commentsCount' => $comments_count,
                    'sharesCount' => rand(1, 10),
                    'comments' => []
                ];
            }
        }

        thefacepost_api_json_response([
            'status' => 'success',
            'count' => count($formatted),
            'posts' => $formatted
        ]);
    }

    // 3. Stories (/api/v1.0/stories)
    if ($endpoint === 'stories' || $version === 'stories') {
        $stories_data = [];
        $users_db = new OssnDatabase();
        $users_db->statement("SELECT guid, username, first_name, last_name FROM ossn_users WHERE is_banned=0 AND is_activated=1 ORDER BY guid DESC LIMIT 10");
        $users_db->execute();
        $all_users = $users_db->fetch(true);

        if ($all_users) {
            foreach ($all_users as $u) {
                $user_obj = ossn_user_by_guid($u->guid);
                if ($user_obj) {
                    $stories_data[] = [
                        'id' => 'story_' . $u->guid,
                        'user' => [
                            'id' => 'u_' . $u->guid,
                            'name' => $user_obj->fullname ?: ($u->first_name . ' ' . $u->last_name),
                            'avatar' => $user_obj->iconURL()->large,
                            'isOnline' => (bool)rand(0, 1)
                        ],
                        'mediaUrl' => $user_obj->iconURL()->large,
                        'timeAgo' => rand(1, 12) . 'h',
                        'caption' => 'Latest update from ' . ($user_obj->first_name ?: $u->username) . ' ✨',
                        'unread' => true
                    ];
                }
            }
        }

        thefacepost_api_json_response([
            'status' => 'success',
            'stories' => $stories_data
        ]);
    }

    // 4. Reels / Videos (/api/v1.0/reels)
    if ($endpoint === 'reels' || $version === 'reels') {
        thefacepost_api_json_response([
            'status' => 'success',
            'reels' => [
                [
                    'id' => 'reel_1',
                    'creator' => [
                        'id' => 'u_1',
                        'name' => 'TheFacePost Official',
                        'avatar' => $site_url . '/themes/goblue/images/profile.png',
                        'isFollowing' => false
                    ],
                    'description' => 'Welcome to The FacePost mobile community! 🚀 Share moments, watch reels, and chat with friends.',
                    'music' => 'Original Audio • The FacePost Beats',
                    'videoUrl' => 'https://assets.mixkit.co/videos/preview/mixkit-tree-branches-in-the-breeze-1188-large.mp4',
                    'posterUrl' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
                    'likes' => '1.5K',
                    'isLiked' => false,
                    'comments' => '84',
                    'shares' => '32'
                ]
            ]
        ]);
    }

    // 5. Notifications (/api/v1.0/notifications)
    if ($endpoint === 'notifications' || $version === 'notifications') {
        thefacepost_api_json_response([
            'status' => 'success',
            'notifications' => [
                [
                    'id' => 'notif_live_1',
                    'type' => 'reaction',
                    'user' => [
                        'name' => 'The FacePost Admin',
                        'avatar' => $site_url . '/themes/goblue/images/profile.png'
                    ],
                    'text' => 'welcomed you to The FacePost mobile app.',
                    'timeAgo' => 'Just now',
                    'unread' => true
                ]
            ]
        ]);
    }

    // Default info
    thefacepost_api_json_response([
        'status' => 'online',
        'api' => 'TheFacePost REST API v1.0',
        'endpoints' => [
            '/api/v1.0/auth/login',
            '/api/v1.0/auth/register',
            '/api/v1.0/feed',
            '/api/v1.0/stories',
            '/api/v1.0/reels',
            '/api/v1.0/notifications'
        ]
    ]);
}

ossn_register_callback('ossn', 'init', 'thefacepost_api_init');
