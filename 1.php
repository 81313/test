<?php
header('Content-Type: text/html; charset=utf-8');

$rss_url = 'https://autoweb.nfu.edu.tw/feed/';
$announcements = [];

try {
    // 載入 RSS Feed
    $rss = simplexml_load_file($rss_url);

    if ($rss === false) {
        throw new Exception("無法載入 RSS Feed。請檢查 URL 或網路連線。");
    }

    $count = 0;
    // 遍歷 RSS Feed 中的項目
    foreach ($rss->channel->item as $item) {
        if ($count >= 3) { // 只抓取前三項
            break;
        }

        $title = htmlspecialchars($item->title);
        $link = htmlspecialchars($item->link);
        $description = strip_tags($item->description); // 移除 HTML 標籤
        $pubDate = date('Y年m月d日', strtotime($item->pubDate));

        $announcements[] = [
            'title' => $title,
            'link' => $link,
            'description' => mb_substr($description, 0, 100, 'utf-8') . '...', // 截斷描述
            'pubDate' => $pubDate
        ];
        $count++;
    }
} catch (Exception $e) {
    $error_message = "獲取公告失敗：" . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>虎尾科技大學重要公告 (RSS Feed)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .announcement-item { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 15px; background-color: #f9f9f9; }
        .announcement-item h3 { margin-top: 0; color: #3498db; }
        .announcement-item p { font-size: 0.9em; color: #555; }
        .announcement-item a { text-decoration: none; color: #e74c3c; font-weight: bold; }
        .announcement-item a:hover { text-decoration: underline; }
        .error-message { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>虎尾科技大學自動化工程系 - 重要公告 (RSS Feed 來源)</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php elseif (empty($announcements)): ?>
            <p>目前沒有可用的公告。</p>
        <?php else: ?>
            <?php foreach ($announcements as $announcement): ?>
                <div class="announcement-item">
                    <h3><a href="<?php echo $announcement['link']; ?>" target="_blank"><?php echo $announcement['title']; ?></a></h3>
                    <p>發布日期：<?php echo $announcement['pubDate']; ?></p>
                    <p><?php echo $announcement['description']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <p>資料來源：<a href="https://autoweb.nfu.edu.tw/" target="_blank">虎尾科技大學自動化工程系</a></p>
    </div>
</body>
</html>