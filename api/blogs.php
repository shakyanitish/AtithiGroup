<?php
require_once('../includes/initialize.php');

// Fetch all blogs from database (ignoring status for debug)
$blogs = Blog::find_by_sql("SELECT * FROM tbl_blog ORDER BY blog_date DESC");

$jsonBlogs = [];

foreach ($blogs as $blog) {
    // Format the date
    $date = date('M d, Y', strtotime($blog->blog_date));
    
    // Estimate read time (approx 200 words per minute)
    $wordCount = str_word_count(strip_tags($blog->content));
    $readTimeNum = ceil($wordCount / 200);
    $readTime = ($readTimeNum < 1 ? 1 : $readTimeNum) . " Min Read";
    
    // Truncate brief for excerpt
    $excerpt = !empty($blog->brief) ? strip_tags($blog->brief) : '';
    if (strlen($excerpt) > 120) {
        $excerpt = substr($excerpt, 0, 120) . '...';
    }

    // Image path - adjustments for frontend
    if (!empty($blog->image)) {
        $imagePath = IMAGE_PATH . 'blog/' . $blog->image;
    } else {
        // Fallback to a placeholder or a banner if image is missing in DB
        $imagePath = BASE_URL . 'template/web/assets/image/banner-1.jpeg';
    }

    // Map database HTML to the JSON content structure
    $content = [
        [
            "type" => "paragraph",
            "text" => $blog->content,
            "style" => ""
        ]
    ];

    $jsonBlogs[] = [
        "id" => (int)$blog->id,
        "slug" => $blog->slug,
        "category" => "News", 
        "date" => $date,
        "readTime" => $readTime,
        "title" => $blog->title,
        "excerpt" => $excerpt,
        "image" => $imagePath,
        "author" => $blog->author ? $blog->author : "Atithi Group",
        "authorImage" => BASE_URL . "template/web/assets/image/favicon.png",
        "content" => $content
    ];
}

// Set header for JSON response
header('Content-Type: application/json');

// Return JSON
$jsonData = json_encode($jsonBlogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo $jsonData;

// Sync to the physical JSON file for the detail page
// Path relative to root: template/web/assets/data/blogs.json
$jsonFilePath = '../template/web/assets/data/blogs.json';
// Ensure directory exists (it should, but just in case)
file_put_contents($jsonFilePath, $jsonData);
?>
