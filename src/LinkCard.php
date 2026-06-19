<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private ?string $imageUrl;
    private array $tags;

    public function __construct(
        string $url,
        string $title,
        string $description = '',
        ?string $imageUrl = null,
        array $tags = []
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->tags = $tags;
    }

    public function render(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDescription = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $imageHtml = '';

        if ($this->imageUrl !== null) {
            $escapedImageUrl = htmlspecialchars($this->imageUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $imageHtml = sprintf(
                '<img class="link-card-image" src="%s" alt="%s" loading="lazy" />',
                $escapedImageUrl,
                $escapedTitle
            );
        }

        $tagsHtml = '';
        if (!empty($this->tags)) {
            $tagItems = array_map(function (string $tag): string {
                $escapedTag = htmlspecialchars($tag, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                return sprintf('<span class="link-card-tag">%s</span>', $escapedTag);
            }, $this->tags);
            $tagsHtml = '<div class="link-card-tags">' . implode(' ', $tagItems) . '</div>';
        }

        return sprintf(
            '<a class="link-card" href="%s" target="_blank" rel="noopener noreferrer">' .
            '%s' .
            '<div class="link-card-content">' .
            '<h3 class="link-card-title">%s</h3>' .
            '<p class="link-card-description">%s</p>' .
            '%s' .
            '<span class="link-card-url">%s</span>' .
            '</div>' .
            '</a>',
            $escapedUrl,
            $imageHtml,
            $escapedTitle,
            $escapedDescription,
            $tagsHtml,
            $escapedUrl
        );
    }

    public static function createSampleCard(): self
    {
        return new self(
            url: 'https://official-main-i-game.com.cn',
            title: '爱游戏 - 官方首页',
            description: '爱游戏提供丰富的游戏资讯、攻略和社区互动，欢迎广大游戏爱好者加入。',
            imageUrl: 'https://official-main-i-game.com.cn/images/og-image.jpg',
            tags: ['游戏', '社区', '资讯']
        );
    }

    public static function createCardFromArray(array $data): self
    {
        return new self(
            url: $data['url'] ?? '',
            title: $data['title'] ?? '',
            description: $data['description'] ?? '',
            imageUrl: $data['image_url'] ?? null,
            tags: $data['tags'] ?? []
        );
    }
}

function renderLinkCard(array $cardData): string
{
    $card = LinkCard::createCardFromArray($cardData);
    return $card->render();
}

function renderSampleLinkCard(): string
{
    $card = LinkCard::createSampleCard();
    return $card->render();
}

// 使用示例（可移除或保留用于演示）
if (php_sapi_name() === 'cli') {
    $sampleHtml = renderSampleLinkCard();
    echo "=== 示例链接卡片 HTML ===\n";
    echo $sampleHtml . "\n";

    $customData = [
        'url' => 'https://official-main-i-game.com.cn/news',
        'title' => '爱游戏 - 新闻动态',
        'description' => '查看爱游戏平台最新游戏新闻与活动公告。',
        'image_url' => 'https://official-main-i-game.com.cn/images/news-banner.jpg',
        'tags' => ['新闻', '公告', '活动']
    ];
    $customHtml = renderLinkCard($customData);
    echo "\n=== 自定义链接卡片 HTML ===\n";
    echo $customHtml . "\n";
}