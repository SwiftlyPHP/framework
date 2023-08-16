<?php

namespace App\Controller;

use Swiftly\Database\Adapter\MysqlAdapter;
use App\Model\PostProvider;
use Swiftly\Database\Connection;

use function count;

/**
 * Class used to represent an archive of posts
 * 
 * @example Using URL placeholders to pass parameters
 */
class Example2
{
    /**
     * View a list of posts, paginating as required
     */
    public function view(): void
    {
        // @example TODO
        if (!empty($_GET['page']) && $_GET['page'] > 0) {
            $page = (int)$_GET['page'];
        } else {
            $page = 1;
        }

        // Setup database credentials
        $connection = new Connection();
        $connection->name = "username";
        $connection->password = "password";
        $connection->host = "localhost";

        // Connect to DB
        $database = new MysqlAdapter($connection);

        // Query for posts
        $provider = new PostProvider($database);

        $posts = $provider->getPosts(['page' => $page]);
        $count = count($posts);
        $title = "Search results";

        // Load template
        require APP_VIEW . '/example-archive.html.php';
        exit(0);
    }
}
