<?php

use yii\db\Migration;

/**
 * Handles inserting initial data for page configuration tables.
 */
class m250104_000012_insert_page_config_initial_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Insert page_site_config
        $this->insert('{{%page_site_config}}', [
            'name' => 'FamiliaCuts',
            'title' => 'FamiliaCuts - Your style, our passion',
            'description' => 'The best barbershop in the city with premium services',
        ]);

        // Insert page_brand_colors
        $this->insert('{{%page_brand_colors}}', [
            'primary' => '#2b9d1d',
            'on_primary' => '#ffffff',
            'primary_container' => '#b8f0ad',
            'on_primary_container' => '#002201',
            'secondary' => '#d4af37',
            'on_secondary' => '#1a1a1a',
            'secondary_container' => '#fff4d9',
            'on_secondary_container' => '#3d2f00',
            'tertiary' => '#1a1a1a',
            'on_tertiary' => '#ffffff',
            'tertiary_container' => '#3d3d3d',
            'on_tertiary_container' => '#e5e5e5',
            'error' => '#ba1a1a',
            'on_error' => '#ffffff',
            'error_container' => '#ffdad6',
            'on_error_container' => '#410002',
            'surface' => '#fefefe',
            'on_surface' => '#1c1c1c',
            'surface_variant' => '#e7e7e7',
            'on_surface_variant' => '#5f5f5f',
            'outline' => '#c7c7c7',
            'outline_variant' => '#e0e0e0',
            'shadow' => '#000000',
            'scrim' => '#000000',
            'inverse_surface' => '#1c1c1c',
            'inverse_on_surface' => '#f4f4f4',
            'inverse_primary' => '#9bdc8f',
            'background' => '#f8fdf7',
            'on_background' => '#1a1c1a',
        ]);

        // Insert page_hero_section
        $this->insert('{{%page_hero_section}}', [
            'heading' => 'Familia Cuts',
            'heading_type' => 'image',
            'heading_image' => 'assets/images/familia-logo.png',
            'heading_image_alt' => 'Familia Cuts Logo',
            'heading_image_max_width' => '500px',
            'subheading' => 'Discover the experience of an authentic barbershop',
            'media_type' => 'image',
            'media_url' => 'assets/images/background1.jpeg',
            'video_format' => null,
            'fallback_image_url' => 'assets/images/background1.jpeg',
            'alt_text' => 'Barbershop',
        ]);

        // Insert page_portfolio_section
        $this->insert('{{%page_portfolio_section}}', [
            'badge' => 'Our Work',
            'title' => 'PORTFOLIO',
            'description' => 'Explore our portfolio featuring precision fades, tailored beard work, and creative design cuts crafted for every client.',
        ]);

        // Get portfolio_section_id for portfolio images
        $portfolioSectionId = (int)$this->db->getLastInsertID();

        // Insert page_portfolio_images (4 records)
        $portfolioImages = [
            [
                'portfolio_section_id' => $portfolioSectionId,
                'url' => 'assets/images/background1.jpeg',
                'alt' => 'Precision Fade Cut',
                'title' => 'Precision Fade',
                'sort_order' => 0,
            ],
            [
                'portfolio_section_id' => $portfolioSectionId,
                'url' => 'assets/images/background1.jpeg',
                'alt' => 'Beard Grooming',
                'title' => 'Beard Styling',
                'sort_order' => 1,
            ],
            [
                'portfolio_section_id' => $portfolioSectionId,
                'url' => 'assets/images/background1.jpeg',
                'alt' => 'Modern Cut',
                'title' => 'Modern Style',
                'sort_order' => 2,
            ],
            [
                'portfolio_section_id' => $portfolioSectionId,
                'url' => 'assets/images/background1.jpeg',
                'alt' => 'Classic Haircut',
                'title' => 'Classic Look',
                'sort_order' => 3,
            ],
        ];

        foreach ($portfolioImages as $image) {
            $this->insert('{{%page_portfolio_images}}', $image);
        }

        // Insert page_call_to_action
        $this->insert('{{%page_call_to_action}}', [
            'text' => 'Book Your Appointment',
            'link' => 'https://wa.me/1234567890',
            'target' => '_blank',
            'icon' => 'fas fa-arrow-right',
        ]);

        // Insert page_about_section
        $this->insert('{{%page_about_section}}', [
            'badge' => 'Who We Are',
            'title' => 'About Us',
            'description' => 'With over 10 years of experience, we offer the best cuts and barbershop services. Our team of professionals is dedicated to bringing you the best style that suits your personality.',
            'image_url' => 'assets/images/background1.jpeg',
            'image_alt' => 'About Us',
        ]);

        // Get about_section_id for about section images
        $aboutSectionId = (int)$this->db->getLastInsertID();

        // Insert page_about_section_images (3 records)
        $aboutSectionImages = [
            [
                'about_section_id' => $aboutSectionId,
                'icon' => 'fas fa-check-circle',
                'text' => 'Certified Professionals',
                'sort_order' => 0,
            ],
            [
                'about_section_id' => $aboutSectionId,
                'icon' => 'fas fa-check-circle',
                'text' => 'Premium Products',
                'sort_order' => 1,
            ],
            [
                'about_section_id' => $aboutSectionId,
                'icon' => 'fas fa-check-circle',
                'text' => 'Personalized Attention',
                'sort_order' => 2,
            ],
        ];

        foreach ($aboutSectionImages as $image) {
            $this->insert('{{%page_about_section_images}}', $image);
        }

        // Insert page_services (3 records)
        $services = [
            [
                'icon' => 'fas fa-cut',
                'title' => 'Classic Cuts',
                'description' => 'Traditional cuts with a modern touch',
                'delay' => '0',
                'sort_order' => 0,
            ],
            [
                'icon' => 'fas fa-spa',
                'title' => 'Premium Shave',
                'description' => 'Straight razor shave with hot towel',
                'delay' => '100',
                'sort_order' => 1,
            ],
            [
                'icon' => 'fas fa-user-tie',
                'title' => 'Beard Grooming',
                'description' => 'Professional design and maintenance',
                'delay' => '200',
                'sort_order' => 2,
            ],
        ];

        foreach ($services as $service) {
            $this->insert('{{%page_services}}', $service);
        }

        // Insert page_footer_content
        $this->insert('{{%page_footer_content}}', [
            'brand_name' => 'FamiliaCuts',
            'brand_type' => 'image',
            'brand_logo' => 'assets/images/familia-logo.png',
            'brand_logo_alt' => 'FamiliaCuts Logo',
            'brand_logo_max_width' => '200px',
            'brand_description' => 'Your style, our passion. Experience and quality in every service.',
            'address' => '123 Main Street, City',
            'phone' => '+1 (234) 567-8900',
            'email' => 'info@barbershop.com',
            'copyright' => '© 2025 FamiliaCuts. All rights reserved.',
        ]);

        // Insert page_social_links (3 records)
        $socialLinks = [
            [
                'platform' => 'instagram',
                'url' => 'https://instagram.com/barbershop',
                'icon' => 'fab fa-instagram',
                'sort_order' => 0,
            ],
            [
                'platform' => 'facebook',
                'url' => 'https://facebook.com/barbershop',
                'icon' => 'fab fa-facebook',
                'sort_order' => 1,
            ],
            [
                'platform' => 'whatsapp',
                'url' => 'https://wa.me/1234567890',
                'icon' => 'fab fa-whatsapp',
                'sort_order' => 2,
            ],
        ];

        foreach ($socialLinks as $link) {
            $this->insert('{{%page_social_links}}', $link);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Delete all data in reverse order (respecting foreign keys)
        $this->delete('{{%page_social_links}}');
        $this->delete('{{%page_footer_content}}');
        $this->delete('{{%page_services}}');
        $this->delete('{{%page_about_section_images}}');
        $this->delete('{{%page_about_section}}');
        $this->delete('{{%page_call_to_action}}');
        $this->delete('{{%page_portfolio_images}}');
        $this->delete('{{%page_portfolio_section}}');
        $this->delete('{{%page_hero_section}}');
        $this->delete('{{%page_brand_colors}}');
        $this->delete('{{%page_site_config}}');
    }
}

