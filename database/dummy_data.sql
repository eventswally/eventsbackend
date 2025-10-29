-- Dummy Data for Events Wally
-- Run this after creating the main database structure

USE eventswally;

-- Insert Dummy Event Planners

-- Karachi Planners
INSERT INTO event_planners (name, slug, city_id, category_id, short_description, description, phone, whatsapp, email, address, rating, is_featured) VALUES
('Elite Wedding Planners', 'elite-wedding-planners', 1, 1, 'Premium wedding planning services in Karachi', 'We specialize in creating unforgettable wedding experiences with attention to detail and luxury arrangements. Our team handles everything from venue selection to decoration and catering.', '+92-321-1234567', '+92-321-1234567', 'info@eliteweddings.pk', 'Clifton, Karachi', 4.8, 1),
('Capture Moments Photography', 'capture-moments-photography', 1, 2, 'Professional wedding and event photography', 'Award-winning photography studio specializing in candid wedding photography, pre-wedding shoots, and cinematic videography.', '+92-300-9876543', '+92-300-9876543', 'contact@capturemoments.pk', 'DHA Phase 5, Karachi', 4.7, 1),
('Royal Caterers', 'royal-caterers', 1, 3, 'Authentic Pakistani & International Cuisine', 'Serving delicious food for weddings, corporate events, and parties. Specializing in traditional Pakistani cuisine, BBQ, and continental dishes.', '+92-333-5554444', '+92-333-5554444', 'royal@caterers.pk', 'Gulshan-e-Iqbal, Karachi', 4.5, 0),
('Glam & Glow Makeup Studio', 'glam-glow-makeup', 1, 5, 'Bridal makeup and beauty services', 'Professional bridal makeup, hairstyling, and beauty treatments. We use premium products and stay updated with latest trends.', '+92-311-7778888', '+92-311-7778888', 'glamglow@beauty.pk', 'Tariq Road, Karachi', 4.6, 1),
('Sparkle Decorators', 'sparkle-decorators', 1, 4, 'Creative event decoration and stage setup', 'Transform your venue with our creative decoration ideas. We handle stage setup, lighting, floral arrangements, and complete venue transformation.', '+92-322-4445566', '+92-322-4445566', 'info@sparkledecor.pk', 'Saddar, Karachi', 4.4, 0);

-- Lahore Planners
INSERT INTO event_planners (name, slug, city_id, category_id, short_description, description, phone, whatsapp, email, address, rating, is_featured) VALUES
('Grand Events Lahore', 'grand-events-lahore', 2, 1, 'Complete wedding and event management', 'Full-service event planning company offering customized solutions for weddings, corporate events, and private parties.', '+92-300-1112233', '+92-300-1112233', 'info@grandevents.pk', 'Gulberg III, Lahore', 4.9, 1),
('Lens Stories', 'lens-stories', 2, 2, 'Cinematic wedding films and photography', 'Creating beautiful wedding stories through photography and videography. Specializing in cinematic films and candid moments.', '+92-321-9998877', '+92-321-9998877', 'hello@lensstories.pk', 'Model Town, Lahore', 4.8, 1),
('Taste Paradise Catering', 'taste-paradise-catering', 2, 3, 'Exquisite catering for all occasions', 'Premium catering service with diverse menu options including Pakistani, Chinese, Continental, and BBQ specialties.', '+92-333-7776655', '+92-333-7776655', 'orders@tasteparadise.pk', 'Johar Town, Lahore', 4.6, 0),
('Pearl Banquet Hall', 'pearl-banquet-hall', 2, 6, 'Luxurious wedding and event venue', 'State-of-the-art banquet hall with capacity for 1000+ guests. Modern facilities, ample parking, and professional service.', '+92-42-11223344', '+92-42-11223344', 'booking@pearlbanquet.pk', 'DHA Phase 6, Lahore', 4.7, 1),
('Kids Party Express', 'kids-party-express', 2, 8, 'Creative birthday party planning for kids', 'Specialized in children birthday parties with themes, entertainment, decorations, and complete party packages.', '+92-311-5556677', '+92-311-5556677', 'fun@kidsparty.pk', 'Valencia Town, Lahore', 4.5, 0);

-- Islamabad Planners
INSERT INTO event_planners (name, slug, city_id, category_id, short_description, description, phone, whatsapp, email, address, rating, is_featured) VALUES
('Capital Events', 'capital-events', 3, 1, 'Premier event planning in Islamabad', 'Experienced team handling weddings, corporate events, and social gatherings with professionalism and creativity.', '+92-300-4445566', '+92-300-4445566', 'info@capitalevents.pk', 'F-7 Markaz, Islamabad', 4.8, 1),
('Picture Perfect Studio', 'picture-perfect-studio', 3, 2, 'Contemporary photography and videography', 'Modern photography studio offering wedding shoots, portraits, corporate photography, and event coverage.', '+92-333-8889900', '+92-333-8889900', 'shoot@pictureperfect.pk', 'Blue Area, Islamabad', 4.6, 0),
('Savory Delights', 'savory-delights', 3, 3, 'Gourmet catering and food services', 'High-quality catering with focus on presentation and taste. Custom menus available for all types of events.', '+92-321-6667788', '+92-321-6667788', 'info@savorydelights.pk', 'G-9 Markaz, Islamabad', 4.5, 0),
('Elegance Makeup Artist', 'elegance-makeup-artist', 3, 5, 'Professional bridal and party makeup', 'Certified makeup artist with years of experience in bridal makeup, party makeup, and beauty consultations.', '+92-311-2223344', '+92-311-2223344', 'book@elegancemua.pk', 'F-10 Markaz, Islamabad', 4.7, 1),
('Corporate Events Pro', 'corporate-events-pro', 3, 9, 'Professional corporate event management', 'Specializing in conferences, seminars, product launches, and corporate gatherings with complete AV support.', '+92-51-8887766', '+92-51-8887766', 'corporate@eventspro.pk', 'I-8 Markaz, Islamabad', 4.6, 1);

-- Faisalabad Planners
INSERT INTO event_planners (name, slug, city_id, category_id, short_description, description, phone, whatsapp, email, address, rating, is_featured) VALUES
('Textile City Weddings', 'textile-city-weddings', 4, 1, 'Complete wedding solutions in Faisalabad', 'Offering comprehensive wedding planning services including venue booking, decoration, catering, and photography.', '+92-300-3334455', '+92-300-3334455', 'info@textilecityweddings.pk', 'Peoples Colony, Faisalabad', 4.4, 0),
('Celebration Decorators', 'celebration-decorators', 4, 4, 'Creative decoration for all events', 'Expert decorators providing stage design, floral arrangements, lighting, and complete venue transformation.', '+92-333-5557799', '+92-333-5557799', 'decor@celebration.pk', 'Susan Road, Faisalabad', 4.3, 0);

-- Multan Planners
INSERT INTO event_planners (name, slug, city_id, category_id, short_description, description, phone, whatsapp, email, address, rating, is_featured) VALUES
('Multan Magic Events', 'multan-magic-events', 6, 1, 'Magical wedding experiences in Multan', 'Creating memorable weddings and events with innovative ideas and flawless execution.', '+92-321-4447788', '+92-321-4447788', 'magic@multanevents.pk', 'Cantt Area, Multan', 4.5, 0),
('DJ Sound & Entertainment', 'dj-sound-entertainment', 6, 7, 'Professional DJ and entertainment services', 'Premium sound systems, DJ services, lighting, and entertainment for weddings and parties.', '+92-300-6669988', '+92-300-6669988', 'book@djsound.pk', 'Gulgasht Colony, Multan', 4.4, 0);

-- Rawalpindi Planners
INSERT INTO event_planners (name, slug, city_id, category_id, short_description, description, phone, whatsapp, email, address, rating, is_featured) VALUES
('Twin City Events', 'twin-city-events', 5, 1, 'Professional event planning in Rawalpindi', 'Full-service event planning for weddings, birthdays, and corporate events with attention to detail.', '+92-333-2229944', '+92-333-2229944', 'info@twincityevents.pk', 'Saddar, Rawalpindi', 4.5, 0),
('Royal Garden Venue', 'royal-garden-venue', 5, 6, 'Beautiful outdoor event venue', 'Spacious garden venue perfect for weddings and outdoor events. Beautiful landscaping and modern amenities.', '+92-51-5554466', '+92-51-5554466', 'booking@royalgarden.pk', 'Bahria Town, Rawalpindi', 4.6, 1);

-- Add some dummy images for featured planners
INSERT INTO planner_images (planner_id, image_url, is_primary) VALUES
(1, 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800', 1),
(2, 'https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=800', 1),
(4, 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=800', 1),
(6, 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800', 1),
(7, 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800', 1),
(10, 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=800', 1),
(11, 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800', 1),
(14, 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=800', 1),
(15, 'https://images.unsplash.com/photo-1505236858219-8359eb29e329?w=800', 1);

-- Add some sample packages
INSERT INTO planner_packages (planner_id, package_name, price, description, features) VALUES
(1, 'Basic Wedding Package', 'PKR 150,000', 'Perfect for intimate weddings', 'Venue booking assistance\nBasic decoration\nPhotography (4 hours)\n50-100 guests catering'),
(1, 'Premium Wedding Package', 'PKR 350,000', 'Complete luxury wedding experience', 'Complete venue decoration\nProfessional photography & videography\nMakeup artist\n200-300 guests catering\nWelcome drinks\nStage setup'),
(2, 'Photography Only', 'PKR 50,000', 'Professional photography coverage', '8 hours coverage\n2 photographers\n500+ edited photos\nOnline gallery'),
(2, 'Photo + Video Package', 'PKR 120,000', 'Complete coverage with cinematic film', 'Full day coverage\n2 photographers + 2 videographers\n1000+ photos\n15-minute cinematic film\n4K video quality'),
(6, 'Standard Event Package', 'PKR 200,000', 'Professional event management', 'Venue coordination\nComplete decoration\nCatering for 150 guests\nSound system'),
(7, 'Wedding Photography Deluxe', 'PKR 90,000', 'Premium photography service', '12 hours coverage\n3 photographers\nPre-wedding shoot\nAlbum design\nUSB with all photos');
