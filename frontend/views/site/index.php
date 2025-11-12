<?php

/**
 * Landing Page View
 * 
 * This view contains the content sections for the landing page.
 * The layout (landing.php) handles the HTML structure, head, and scripts.
 */

use yii\helpers\Url;
use common\components\MyHelpers;
use common\models\page\PageHeroSectionConfig;
use common\models\page\PagePortfolioSectionConfig;
use common\models\page\PageAboutSectionConfig;
use common\models\page\PageServiceConfig;
use common\models\page\PageCallToActionConfig;
use common\models\page\PageFooterContentConfig;
use common\models\page\PageSocialLinkConfig;

// ========================================
// LOAD DATA FROM MODELS
// ========================================

// Hero Section
$heroConfig = PageHeroSectionConfig::getConfig();
$hero_section = $heroConfig ? [
    'heading' => $heroConfig->heading ?? 'Familia Cuts',
    'heading_type' => $heroConfig->heading_type ?? 'image',
    'heading_image' => $heroConfig->heading_image ?? 'images/familia-logo.png',
    'heading_image_alt' => $heroConfig->heading_image_alt ?? 'Familia Cuts Logo',
    'heading_image_max_width' => $heroConfig->heading_image_max_width ?? '500px',
    'subheading' => $heroConfig->subheading ?? 'Discover the experience of an authentic barbershop',
    'media_type' => $heroConfig->media_type ?? 'image',
    'media_url' => $heroConfig->media_url ?? 'images/background1.jpeg',
    'video_format' => $heroConfig->video_format ?? null,
    'fallback_image_url' => $heroConfig->fallback_image_url ?? 'images/background1.jpeg',
    'alt_text' => $heroConfig->alt_text ?? 'Barbershop'
] : [
    'heading' => 'Familia Cuts',
    'heading_type' => 'image',
    'heading_image' => 'images/familia-logo.png',
    'heading_image_alt' => 'Familia Cuts Logo',
    'heading_image_max_width' => '500px',
    'subheading' => 'Discover the experience of an authentic barbershop',
    'media_type' => 'image',
    'media_url' => 'images/background1.jpeg',
    'video_format' => null,
    'fallback_image_url' => 'images/background1.jpeg',
    'alt_text' => 'Barbershop'
];

// Portfolio Section
$portfolioConfig = PagePortfolioSectionConfig::getConfig();
$portfolioImages = $portfolioConfig ? $portfolioConfig->portfolioImages : [];
$portfolio_section = [
    'badge' => $portfolioConfig ? ($portfolioConfig->badge ?? 'Our Work') : 'Our Work',
    'title' => $portfolioConfig ? ($portfolioConfig->title ?? 'PORTFOLIO') : 'PORTFOLIO',
    'description' => $portfolioConfig ? ($portfolioConfig->description ?? 'Explore our portfolio featuring precision fades, tailored beard work, and creative design cuts crafted for every client.') : 'Explore our portfolio featuring precision fades, tailored beard work, and creative design cuts crafted for every client.',
    'images' => !empty($portfolioImages) ? array_map(function ($img) {
        return [
            'url' => $img->url,
            'alt' => $img->alt ?? '',
            'title' => $img->title ?? ''
        ];
    }, $portfolioImages) : [
        [
            'url' => 'images/background1.jpeg',
            'alt' => 'Precision Fade Cut',
            'title' => 'Precision Fade'
        ],
        [
            'url' => 'images/background1.jpeg',
            'alt' => 'Beard Grooming',
            'title' => 'Beard Styling'
        ],
        [
            'url' => 'images/background1.jpeg',
            'alt' => 'Modern Cut',
            'title' => 'Modern Style'
        ],
        [
            'url' => 'images/background1.jpeg',
            'alt' => 'Classic Haircut',
            'title' => 'Classic Look'
        ]
    ]
];

// Call to Action
$ctaConfig = PageCallToActionConfig::getConfig();
$call_to_action = $ctaConfig ? [
    'text' => $ctaConfig->text ?? 'Book Your Appointment',
    'link' => $ctaConfig->link ?? 'https://wa.me/1234567890',
    'target' => $ctaConfig->target ?? '_blank',
    'icon' => $ctaConfig->icon ?? 'fas fa-arrow-right'
] : [
    'text' => 'Book Your Appointment',
    'link' => 'https://wa.me/1234567890',
    'target' => '_blank',
    'icon' => 'fas fa-arrow-right'
];

// About Section
$aboutConfig = PageAboutSectionConfig::getConfig();
$aboutFeatures = $aboutConfig ? $aboutConfig->aboutSectionImages : [];
$about_section = [
    'badge' => $aboutConfig ? ($aboutConfig->badge ?? 'Who We Are') : 'Who We Are',
    'title' => $aboutConfig ? ($aboutConfig->title ?? 'About Us') : 'About Us',
    'description' => $aboutConfig ? ($aboutConfig->description ?? 'With over 10 years of experience, we offer the best cuts and barbershop services. Our team of professionals is dedicated to bringing you the best style that suits your personality.') : 'With over 10 years of experience, we offer the best cuts and barbershop services. Our team of professionals is dedicated to bringing you the best style that suits your personality.',
    'image_url' => $aboutConfig ? ($aboutConfig->image_url ?? 'images/background1.jpeg') : 'images/background1.jpeg',
    'image_alt' => $aboutConfig ? ($aboutConfig->image_alt ?? 'About Us') : 'About Us',
    'features' => !empty($aboutFeatures) ? array_map(function ($feature) {
        return [
            'icon' => $feature->icon ?? 'fas fa-check-circle',
            'text' => $feature->text ?? ''
        ];
    }, $aboutFeatures) : [
        [
            'icon' => 'fas fa-check-circle',
            'text' => 'Certified Professionals'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'text' => 'Premium Products'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'text' => 'Personalized Attention'
        ]
    ]
];

// Services
$servicesData = PageServiceConfig::getAllOrdered();
$services = !empty($servicesData) ? array_map(function ($service) {
    return [
        'icon' => $service->icon ?? 'fas fa-cut',
        'title' => $service->title ?? '',
        'description' => $service->description ?? '',
        'delay' => $service->delay ?? '0'
    ];
}, $servicesData) : [
    [
        'icon' => 'fas fa-cut',
        'title' => 'Classic Cuts',
        'description' => 'Traditional cuts with a modern touch',
        'delay' => '0'
    ],
    [
        'icon' => 'fas fa-spa',
        'title' => 'Premium Shave',
        'description' => 'Straight razor shave with hot towel',
        'delay' => '100'
    ],
    [
        'icon' => 'fas fa-user-tie',
        'title' => 'Beard Grooming',
        'description' => 'Professional design and maintenance',
        'delay' => '200'
    ]
];

// Footer Content
$footerConfig = PageFooterContentConfig::getConfig();
$footer_content = $footerConfig ? [
    'brand_name' => $footerConfig->brand_name ?? 'FamiliaCuts',
    'brand_type' => $footerConfig->brand_type ?? 'image',
    'brand_logo' => $footerConfig->brand_logo ?? 'images/familia-logo.png',
    'brand_logo_alt' => $footerConfig->brand_logo_alt ?? 'FamiliaCuts Logo',
    'brand_logo_max_width' => $footerConfig->brand_logo_max_width ?? '200px',
    'brand_description' => $footerConfig->brand_description ?? 'Your style, our passion. Experience and quality in every service.',
    'address' => $footerConfig->address ?? '123 Main Street, City',
    'phone' => $footerConfig->phone ?? '+1 (234) 567-8900',
    'email' => $footerConfig->email ?? 'info@barbershop.com',
    'copyright' => $footerConfig->copyright ?? '© 2025 FamiliaCuts. All rights reserved.'
] : [
    'brand_name' => 'FamiliaCuts',
    'brand_type' => 'image',
    'brand_logo' => 'images/familia-logo.png',
    'brand_logo_alt' => 'FamiliaCuts Logo',
    'brand_logo_max_width' => '200px',
    'brand_description' => 'Your style, our passion. Experience and quality in every service.',
    'address' => '123 Main Street, City',
    'phone' => '+1 (234) 567-8900',
    'email' => 'info@barbershop.com',
    'copyright' => '© 2025 FamiliaCuts. All rights reserved.'
];

// Social Links
$socialLinksData = PageSocialLinkConfig::getAllOrdered();
$social_links = !empty($socialLinksData) ? array_map(function ($social) {
    return [
        'platform' => $social->platform ?? '',
        'url' => $social->url ?? '',
        'icon' => $social->icon ?? ''
    ];
}, $socialLinksData) : [
    [
        'platform' => 'instagram',
        'url' => 'https://instagram.com/barbershop',
        'icon' => 'fab fa-instagram'
    ],
    [
        'platform' => 'facebook',
        'url' => 'https://facebook.com/barbershop',
        'icon' => 'fab fa-facebook'
    ],
    [
        'platform' => 'whatsapp',
        'url' => 'https://wa.me/1234567890',
        'icon' => 'fab fa-whatsapp'
    ]
];

?>

<!-- Hero Section -->
<section id="hero" class="hero-section">
    <!-- Media Background -->
    <div class="hero-media" id="heroMedia">
        <?php if ($hero_section['media_type'] === 'video'): ?>
            <video autoplay muted loop playsinline class="hero-video" id="heroVideo">
                <source src="<?php echo MyHelpers::e($hero_section['media_url']); ?>" type="video/<?php echo MyHelpers::e($hero_section['video_format']); ?>">
                Your browser does not support the video element.
            </video>
            <img src="<?php echo MyHelpers::e($hero_section['fallback_image_url']); ?>" alt="<?php echo MyHelpers::e($hero_section['alt_text']); ?>" class="hero-image d-none" id="heroImage">
        <?php else: ?>
            <img src="<?php echo MyHelpers::e($hero_section['media_url']); ?>" alt="<?php echo MyHelpers::e($hero_section['alt_text']); ?>" class="hero-image" id="heroImage">
        <?php endif; ?>
    </div>

    <!-- Overlay -->
    <div class="hero-overlay"></div>

    <!-- Content -->
    <div class="hero-content">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <?php if (isset($hero_section['heading_type']) && $hero_section['heading_type'] === 'image'): ?>
                        <div class="hero-title-image" data-aos="fade-up">
                            <img src="<?php echo MyHelpers::e($hero_section['heading_image']); ?>"
                                alt="<?php echo MyHelpers::e($hero_section['heading_image_alt'] ?? $hero_section['heading']); ?>"
                                class="hero-heading-img"
                                style="<?php echo isset($hero_section['heading_image_max_width']) ? 'max-width: ' . MyHelpers::e($hero_section['heading_image_max_width']) . ';' : ''; ?>">
                        </div>
                    <?php else: ?>
                        <h1 class="hero-title" data-aos="fade-up"><?php echo MyHelpers::e($hero_section['heading']); ?></h1>
                    <?php endif; ?>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100"><?php echo MyHelpers::e($hero_section['subheading']); ?></p>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="<?php echo MyHelpers::e($call_to_action['link']); ?>"
                            id="cta-hero-main"
                            class="btn btn-cta"
                            target="<?php echo MyHelpers::e($call_to_action['target']); ?>">
                            <?php echo MyHelpers::e($call_to_action['text']); ?>
                            <i class="<?php echo MyHelpers::e($call_to_action['icon']); ?> ms-2"></i>
                        </a>
                        <br />
                        <a href="#portfolio"
                            id="cta-hero-portfolio"
                            class="btn btn-cta-secondary">
                            Portfolio
                            <i class="fas fa-images ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <a href="#portfolio" class="scroll-link">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</section>

<!-- Portfolio Section -->
<section id="portfolio" class="portfolio-section py-6">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <span class="section-badge" data-aos="fade-up"><?php echo MyHelpers::e($portfolio_section['badge']); ?></span>
                <h2 class="portfolio-title" data-aos="fade-up" data-aos-delay="100"><?php echo MyHelpers::e($portfolio_section['title']); ?></h2>
                <p class="portfolio-description" data-aos="fade-up" data-aos-delay="200">
                    <?php echo MyHelpers::e($portfolio_section['description']); ?>
                </p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <a href="<?php echo MyHelpers::e($call_to_action['link']); ?>"
                    id="cta-portfolio-section"
                    class="btn btn-cta"
                    data-aos="fade-up"
                    data-aos-delay="400"
                    target="<?php echo MyHelpers::e($call_to_action['target']); ?>">
                    <?php echo MyHelpers::e($call_to_action['text']); ?>
                    <i class="<?php echo MyHelpers::e($call_to_action['icon']); ?> ms-2"></i>
                </a>
            </div>
        </div>

        <!-- Portfolio Gallery -->
        <div class="portfolio-gallery" data-aos="fade-up" data-aos-delay="300">
            <?php foreach ($portfolio_section['images'] as $index => $image): ?>
                <div class="portfolio-item" data-aos="zoom-in" data-aos-delay="<?php echo MyHelpers::e($index * 50); ?>">
                    <div class="portfolio-image-wrapper">
                        <img src="<?php echo MyHelpers::e($image['url']); ?>"
                            alt="<?php echo MyHelpers::e($image['alt']); ?>"
                            class="portfolio-image"
                            loading="lazy">
                        <div class="portfolio-overlay">
                            <span class="portfolio-title-overlay"><?php echo MyHelpers::e($image['title']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section py-6">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-image-wrapper">
                    <img src="<?php echo MyHelpers::e($about_section['image_url']); ?>"
                        alt="<?php echo MyHelpers::e($about_section['image_alt']); ?>"
                        class="img-fluid rounded shadow-lg">
                    <div class="about-decoration"></div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="about-content">
                    <span class="section-badge"><?php echo MyHelpers::e($about_section['badge']); ?></span>
                    <h2 class="section-title mb-4"><?php echo MyHelpers::e($about_section['title']); ?></h2>
                    <p class="section-text">
                        <?php echo MyHelpers::e($about_section['description']); ?>
                    </p>
                    <div class="about-features mt-4">
                        <?php foreach ($about_section['features'] as $feature): ?>
                            <div class="feature-item">
                                <i class="<?php echo MyHelpers::e($feature['icon']); ?> text-accent"></i>
                                <span><?php echo MyHelpers::e($feature['text']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Call to Action -->
                    <div class="mt-4">
                        <a href="<?php echo MyHelpers::e($call_to_action['link']); ?>"
                            id="cta-about-section"
                            class="btn btn-cta"
                            data-aos="fade-up"
                            target="<?php echo MyHelpers::e($call_to_action['target']); ?>">
                            <?php echo MyHelpers::e($call_to_action['text']); ?>
                            <i class="<?php echo MyHelpers::e($call_to_action['icon']); ?> ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <?php if (isset($footer_content['brand_type']) && $footer_content['brand_type'] === 'image'): ?>
                    <div class="footer-brand-logo">
                        <img src="<?php echo MyHelpers::e($footer_content['brand_logo']); ?>"
                            alt="<?php echo MyHelpers::e($footer_content['brand_logo_alt'] ?? $footer_content['brand_name']); ?>"
                            class="footer-logo-img"
                            style="<?php echo isset($footer_content['brand_logo_max_width']) ? 'max-width: ' . MyHelpers::e($footer_content['brand_logo_max_width']) . ';' : ''; ?>">
                    </div>
                <?php else: ?>
                    <h3 class="footer-brand"><?php echo MyHelpers::e($footer_content['brand_name']); ?></h3>
                <?php endif; ?>
                <p class="footer-text"><?php echo MyHelpers::e($footer_content['brand_description']); ?></p>
            </div>
            <div class="col-lg-4">
                <h4 class="footer-heading">Contact</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo MyHelpers::e($footer_content['address']); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span><?php echo MyHelpers::e($footer_content['phone']); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span><?php echo MyHelpers::e($footer_content['email']); ?></span>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h4 class="footer-heading">Follow Us</h4>
                <div class="social-links">
                    <?php foreach ($social_links as $social): ?>
                        <a href="<?php echo MyHelpers::e($social['url']); ?>"
                            target="_blank"
                            class="social-link"
                            aria-label="<?php echo ucfirst(MyHelpers::e($social['platform'])); ?>">
                            <i class="<?php echo MyHelpers::e($social['icon']); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="row">
            <div class="col-12 text-center">
                <p class="footer-copyright">
                    <?php echo MyHelpers::e($footer_content['copyright']); ?>
                </p>
            </div>
        </div>
    </div>
</footer>