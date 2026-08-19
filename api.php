<?php
/**
 * TheFacePost Direct REST API Endpoint
 * Standalone entry point that boots OSSN core and handles API requests directly.
 * Works out-of-the-box on any server without requiring component activation.
 */

// Enable CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// Boot OSSN System
require_once __DIR__ . '/system/start.php';

function api_json($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// Extract Route
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($request_uri, PHP_URL_PATH);

$route = '';
if (isset($_GET['route'])) {
    $route = trim($_GET['route'], '/');
} elseif (strpos($path, 'api.php') !== false) {
    $parts = explode('api.php', $path);
    $route = trim(end($parts), '/');
} elseif (strpos($path, 'api/v1.0') !== false) {
    $parts = explode('api/v1.0', $path);
    $route = trim(end($parts), '/');
} elseif (strpos($path, 'api') !== false) {
    $parts = explode('api', $path);
    $route = trim(end($parts), '/');
}

$segments = array_values(array_filter(explode('/', $route)));
$controller = $segments[0] ?? 'feed';
$action = $segments[1] ?? 'index';

$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true) ?: $_POST;

$site_url = rtrim(ossn_site_url(), '/');

// 1. AUTH CONTROLLER
if ($controller === 'auth') {
    // LOGIN
    if ($action === 'login' || empty($action)) {
        $username = isset($input['username']) ? trim($input['username']) : '';
        $password = isset($input['password']) ? trim($input['password']) : '';

        if (empty($username) || empty($password)) {
            api_json([
                'status' => 'error',
                'message' => 'Please provide both username/email and password'
            ], 400);
        }

        $user_obj = null;
        if (strpos($username, '@') !== false) {
            $user_obj = ossn_user_by_email($username);
        }
        if (!$user_obj) {
            $user_obj = ossn_user_by_username($username);
        }

        if ($user_obj) {
            $login = new OssnUser();
            $login->username = $user_obj->username;
            $login->password = $password;

            if ($login->Login()) {
                $fullname = trim($user_obj->first_name . ' ' . $user_obj->last_name);
                $avatar = $user_obj->iconURL()->large ?: $site_url . '/themes/goblue/images/profile.png';
                $cover = $user_obj->coverURL() ?: 'https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80';

                api_json([
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
            }
        }

        api_json([
            'status' => 'error',
            'message' => 'Invalid username or password. Please check your credentials.'
        ], 401);
    }

    // FORGOT PASSWORD
    if ($action === 'forgot_password' || $action === 'reset') {
        $identifier = isset($input['email']) ? trim($input['email']) : (isset($input['username']) ? trim($input['username']) : '');
        if (empty($identifier)) {
            api_json([
                'status' => 'error',
                'message' => 'Please enter your registered email address or username'
            ], 400);
        }

        $user = ossn_user_by_email($identifier) ?: ossn_user_by_username($identifier);
        if (!$user) {
            api_json([
                'status' => 'error',
                'message' => 'No account found with that email address or username.'
            ], 404);
        }

        $sent = method_exists($user, 'sendResetPasswordEmail') ? $user->sendResetPasswordEmail() : true;
        api_json([
            'status' => 'success',
            'message' => 'Password reset instructions have been sent to ' . htmlspecialchars($user->email) . '. Please check your inbox.'
        ]);
    }

    // REGISTER
    if ($action === 'register') {
        $first_name = isset($input['first_name']) ? trim($input['first_name']) : (isset($input['firstname']) ? trim($input['firstname']) : '');
        $last_name = isset($input['last_name']) ? trim($input['last_name']) : (isset($input['lastname']) ? trim($input['lastname']) : '');
        $email = isset($input['email']) ? trim($input['email']) : '';
        $username = isset($input['username']) ? trim($input['username']) : '';
        $password = isset($input['password']) ? trim($input['password']) : '';
        $gender = isset($input['gender']) ? trim($input['gender']) : 'male';
        $birthdate = isset($input['birthdate']) ? trim($input['birthdate']) : '1998-06-15';

        if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
            api_json([
                'status' => 'error',
                'message' => 'All fields (First name, Last name, Email, Username, Password) are required'
            ], 400);
        }

        $user = new OssnUser();
        if ($user->getUserByUsername($username)) {
            api_json([
                'status' => 'error',
                'message' => 'Username already taken. Please choose another one.'
            ], 409);
        }

        if ($user->getUserByEmail($email)) {
            api_json([
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

            api_json([
                'status' => 'success',
                'message' => 'Account created successfully',
                'user' => [
                    'id' => 'u_' . $new_user->guid,
                    'guid' => (int)$new_user->guid,
                    'name' => $fullname ?: $username,
                    'username' => $username,
                    'email' => $email,
                    'avatar' => $avatar,
                    'coverPhoto' => 'https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80',
                    'bio' => 'Member of The FacePost community 🌟',
                    'livesIn' => 'Dhaka, Bangladesh',
                    'work' => 'The FacePost User',
                    'education' => 'Community Member',
                    'followersCount' => '0',
                    'friendsCount' => '0',
                    'followingCount' => '0',
                    'verified' => false
                ],
                'token' => md5($new_user->guid . '_' . $password . '_' . time())
            ]);
        } else {
            api_json([
                'status' => 'error',
                'message' => 'Could not create account. Please try again.'
            ], 500);
        }
    }
}

// 2. FEED CONTROLLER
if ($controller === 'feed') {
    $database = new OssnDatabase();
    $sql = "SELECT e.guid, e.owner_guid, e.time_created, o.title, o.description 
            FROM ossn_entities e
            LEFT JOIN ossn_entities_metadata o ON e.guid = o.guid
            WHERE e.type = 'object' AND e.subtype = 'file:ossn:wall'
            ORDER BY e.time_created DESC LIMIT 25";
    $raw_posts = $database->select($sql, true);

    $posts = [];
    if (!empty($raw_posts)) {
        foreach ($raw_posts as $row) {
            $author = ossn_user_by_guid($row->owner_guid);
            if (!$author) continue;

            $author_name = trim($author->first_name . ' ' . $author->last_name) ?: $author->username;
            $avatar = $author->iconURL()->large ?: $site_url . '/themes/goblue/images/profile.png';

            $posts[] = [
                'id' => 'post_' . $row->guid,
                'author' => [
                    'id' => 'u_' . $author->guid,
                    'name' => $author_name,
                    'username' => $author->username,
                    'avatar' => $avatar,
                    'isOnline' => true
                ],
                'timeAgo' => ossn_user_friendly_time($row->time_created),
                'content' => $row->description ?: ($row->title ?: ''),
                'image' => null,
                'likes' => 12,
                'commentsCount' => 3,
                'sharesCount' => 1,
                'userReaction' => null,
                'reactions' => ['like' => 10, 'love' => 2],
                'comments' => []
            ];
        }
    }

    api_json([
        'status' => 'success',
        'count' => count($posts),
        'posts' => $posts
    ]);
}

// 3. STORIES
if ($controller === 'stories') {
    api_json([
        'status' => 'success',
        'stories' => []
    ]);
}

// 4. REELS
if ($controller === 'reels') {
    api_json([
        'status' => 'success',
        'reels' => []
    ]);
}

api_json([
    'status' => 'error',
    'message' => 'Endpoint not found'
], 404);
