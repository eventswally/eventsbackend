-- Events Wally - Fix Localhost URLs
-- Run this SQL script to update all localhost URLs to live domain
-- This updates existing uploaded images to use the correct domain

-- Fix URLs in event_planners table (logo column)
UPDATE event_planners 
SET logo = REPLACE(logo, 'http://localhost/eventswaly', 'https://events.chatvoo.com')
WHERE logo LIKE 'http://localhost/eventswaly%';

-- Fix URLs in event_planners table (video_url column)
UPDATE event_planners 
SET video_url = REPLACE(video_url, 'http://localhost/eventswaly', 'https://events.chatvoo.com')
WHERE video_url LIKE 'http://localhost/eventswaly%';

-- Fix URLs in planner_images table
UPDATE planner_images 
SET image_url = REPLACE(image_url, 'http://localhost/eventswaly', 'https://events.chatvoo.com')
WHERE image_url LIKE 'http://localhost/eventswaly%';

-- Verify changes
SELECT 'Logos Updated:' as status, COUNT(*) as count FROM event_planners WHERE logo LIKE 'https://events.chatvoo.com%'
UNION ALL
SELECT 'Videos Updated:', COUNT(*) FROM event_planners WHERE video_url LIKE 'https://events.chatvoo.com%'
UNION ALL
SELECT 'Images Updated:', COUNT(*) FROM planner_images WHERE image_url LIKE 'https://events.chatvoo.com%';

-- Show sample of updated URLs
SELECT 'Sample Logos' as type, logo as url FROM event_planners WHERE logo IS NOT NULL LIMIT 3
UNION ALL
SELECT 'Sample Images', image_url FROM planner_images LIMIT 3;
