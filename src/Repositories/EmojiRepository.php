<?php

namespace FancyFlux\Repositories;

use FancyFlux\EmojiData;
use Illuminate\Support\Str;

/**
 * Repository for emoji lookup by slug.
 *
 * Provides a clean API for accessing emoji data using kebab-case slugs
 * generated from emoji names. Used by the FANCY facade and components.
 *
 * Why: Centralizes emoji lookup logic and provides slug-based access
 * that's more developer-friendly than searching by character or name.
 *
 * @example FANCY::emoji()->list() // ['grinning-face', 'waving-hand', ...]
 * @example FANCY::emoji()->get('fire') // '🔥'
 * @example FANCY::emoji()->find('fire') // ['char' => '🔥', 'name' => 'fire', 'slug' => 'fire', 'category' => 'symbols']
 */
class EmojiRepository
{
    /**
     * Cached emoji data indexed by slug.
     *
     * @var array<string, array{char: string, name: string, slug: string, category: string}>|null
     */
    protected ?array $emojiBySlug = null;

    /**
     * Cached list of all slugs.
     *
     * @var array<string>|null
     */
    protected ?array $slugList = null;

    /**
     * Get all available emoji slugs.
     *
     * @return array<string>
     */
    public function list(): array
    {
        $this->ensureLoaded();

        return $this->slugList;
    }

    /**
     * Get emoji character by slug.
     *
     * @param string $slug Kebab-case emoji slug (e.g., 'grinning-face')
     * @return string|null Emoji character or null if not found
     */
    public function get(string $slug): ?string
    {
        $this->ensureLoaded();

        return $this->emojiBySlug[$slug]['char'] ?? null;
    }

    /**
     * Find emoji data by slug.
     *
     * @param string $slug Kebab-case emoji slug
     * @return array{char: string, name: string, slug: string, category: string}|null
     */
    public function find(string $slug): ?array
    {
        $this->ensureLoaded();

        return $this->emojiBySlug[$slug] ?? null;
    }

    /**
     * Check if a slug exists.
     *
     * @param string $slug Kebab-case emoji slug
     * @return bool
     */
    public function has(string $slug): bool
    {
        $this->ensureLoaded();

        return isset($this->emojiBySlug[$slug]);
    }

    /**
     * Search emojis by name or slug.
     *
     * @param string $query Search query
     * @param int $limit Maximum results to return
     * @return array<array{char: string, name: string, slug: string, category: string}>
     */
    public function search(string $query, int $limit = 20): array
    {
        $this->ensureLoaded();

        $query = strtolower($query);
        $results = [];

        foreach ($this->emojiBySlug as $emoji) {
            // Match against slug or name
            if (str_contains($emoji['slug'], $query) || str_contains(strtolower($emoji['name']), $query)) {
                $results[] = $emoji;
                if (count($results) >= $limit) {
                    break;
                }
            }
        }

        return $results;
    }

    /**
     * Get all emojis in a specific category.
     *
     * @param string $category Category key (e.g., 'smileys', 'people')
     * @return array<array{char: string, name: string, slug: string, category: string}>
     */
    public function category(string $category): array
    {
        $this->ensureLoaded();

        return array_values(array_filter(
            $this->emojiBySlug,
            fn($emoji) => $emoji['category'] === $category
        ));
    }

    /**
     * Get all category keys.
     *
     * @return array<string>
     */
    public function categories(): array
    {
        return array_keys(EmojiData::categories());
    }

    /**
     * Convert emoji name to kebab-case slug.
     *
     * @param string $name Emoji name (e.g., 'grinning face')
     * @return string Kebab-case slug (e.g., 'grinning-face')
     */
    public static function nameToSlug(string $name): string
    {
        return Str::slug($name);
    }

    /**
     * Ensure emoji data is loaded and indexed.
     */
    protected function ensureLoaded(): void
    {
        if ($this->emojiBySlug !== null) {
            return;
        }

        $this->emojiBySlug = [];
        $this->slugList = [];

        foreach (EmojiData::all() as $emoji) {
            $slug = self::nameToSlug($emoji['name']);
            $this->emojiBySlug[$slug] = [
                'char' => $emoji['char'],
                'name' => $emoji['name'],
                'slug' => $slug,
                'category' => $emoji['category'],
            ];
            $this->slugList[] = $slug;
        }
    }
}
