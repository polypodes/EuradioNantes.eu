
# Update news post types
UPDATE news__post n
SET n.type = 'actualite'
WHERE n.id NOT IN (
    SELECT p.post_id FROM podcast__podcast p WHERE p.post_id IS NOT NULL GROUP BY p.post_id
);

UPDATE news__post n
SET n.type = 'podcast'
WHERE n.id IN (
    SELECT p.post_id FROM podcast__podcast p WHERE p.post_id IS NOT NULL GROUP BY p.post_id
);

# Move albums from static_content to music_album
# category = 9, 25, 26
INSERT INTO music__album
(`title`, `artist`, `resume`, `content`, `created_at`, `updated_at`, `featuredFrom`, `FeaturedTo`, `slug`, `image`, `published`)

(
    SELECT SUBSTRING_INDEX(SUBSTR(s.introduction, INSTR(s.introduction, '"') + 1), '"', 1), s.name, s.introduction, s.body, s.date_add, s.date_add, s.date_add, DATE_ADD(s.date_add, INTERVAL 1 WEEK), s.slug, s.id_image, 1
    FROM `static_content` s
    WHERE s.`id_category` IN (9, 25, 26)
    ORDER BY s.date_add ASC
);

# Move playlists from static_content to music_playlists
# category = 16

INSERT INTO music__playlist
(`title`, `resume`, `content`, `created_at`, `updated_at`, `featuredFrom`, `FeaturedTo`, `slug`, `published`)

(
    SELECT s.name, s.introduction, s.body, s.date_add, s.date_add, DATE_ADD(s.date_add, INTERVAL -15 DAY), s.date_add, s.slug, 1
    FROM `static_content` s
    WHERE `id_category` IN (16)
    ORDER BY s.date_add ASC
);

# Move labels from static_content to music_label
# category = 10

INSERT INTO music__label
(`title`, `resume`, `content`, `featuredFrom`, `FeaturedTo`, `slug`, `image`, `published`)

(
    SELECT s.name, s.introduction, s.body, s.date_add, DATE_ADD(s.date_add, INTERVAL 1 MONTH), s.slug, s.id_image, 1
    FROM `static_content` s
    WHERE `id_category` IN (10)
    ORDER BY s.date_add ASC
);

# Removing imported categories
DELETE FROM `static_content`
WHERE `id_category` IN (9, 25, 26, 16, 10)
