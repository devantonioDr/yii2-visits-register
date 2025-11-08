/**
 * Page Config Management
 * Handles AJAX form submissions and toast notifications
 */

(function($) {
    'use strict';

    // Toast Notification System
    const Toast = {
        container: null,
        
        init: function() {
            this.container = $('#toast-container');
            if (this.container.length === 0) {
                $('body').append('<div id="toast-container" class="toast-container"></div>');
                this.container = $('#toast-container');
            }
        },
        
        show: function(message, type = 'success', duration = 4000) {
            this.init();
            
            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠'
            };
            
            const titles = {
                success: 'Success',
                error: 'Error',
                warning: 'Warning'
            };
            
            const toast = $('<div>', {
                class: 'toast-notification ' + type,
                html: `
                    <div class="toast-icon">${icons[type] || icons.success}</div>
                    <div class="toast-content">
                        <div class="toast-title">${titles[type] || titles.success}</div>
                        <p class="toast-message">${message}</p>
                    </div>
                    <button type="button" class="toast-close">&times;</button>
                `
            });
            
            this.container.append(toast);
            
            // Auto remove after duration
            const autoRemove = setTimeout(() => {
                this.remove(toast);
            }, duration);
            
            // Manual close
            toast.find('.toast-close').on('click', () => {
                clearTimeout(autoRemove);
                this.remove(toast);
            });
            
            return toast;
        },
        
        remove: function(toast) {
            toast.addClass('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        },
        
        success: function(message, duration) {
            return this.show(message, 'success', duration);
        },
        
        error: function(message, duration) {
            return this.show(message, 'error', duration || 6000);
        },
        
        warning: function(message, duration) {
            return this.show(message, 'warning', duration);
        }
    };

    // Form Handler
    const FormHandler = {
        init: function() {
            const self = this;
            
            // Handle all save buttons
            $(document).on('submit', '.config-form', function(e) {
                e.preventDefault();
                self.handleSubmit($(this));
            });
            
            // Initialize toast system
            Toast.init();
        },
        
        handleSubmit: function($form) {
            const $btn = $form.find('.save-btn');
            const section = $btn.data('section');
            const url = $btn.data('url') || $form.attr('action');
            
            if (!url) {
                Toast.error('No URL specified for this form');
                return;
            }
            
            // Get form data
            const formData = $form.serialize();
            
            // Set loading state
            this.setLoading($btn, true);
            $form.addClass('loading');
            
            // Send AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-Token': $('meta[name=csrf-token]').attr('content') || 
                                   $('input[name=_csrf]').val()
                }
            })
            .done(function(response) {
                if (response.success) {
                    Toast.success(response.message || 'Saved successfully');
                    
                    // Update form with new ID if returned
                    if (response.id) {
                        const $idInput = $form.find('input[name="id"]');
                        if ($idInput.length === 0) {
                            $form.prepend('<input type="hidden" name="id" value="' + response.id + '">');
                        } else {
                            $idInput.val(response.id);
                        }
                    }
                } else {
                    let errorMessage = 'An error occurred';
                    if (response.errors) {
                        const errors = [];
                        $.each(response.errors, function(field, messages) {
                            if (Array.isArray(messages)) {
                                errors.push(messages.join(', '));
                            } else {
                                errors.push(messages);
                            }
                        });
                        errorMessage = errors.join('<br>');
                    } else if (response.message) {
                        errorMessage = response.message;
                    }
                    Toast.error(errorMessage);
                }
            })
            .fail(function(xhr, status, error) {
                let errorMessage = 'An error occurred while saving';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    errorMessage = 'You do not have permission to perform this action';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later';
                }
                Toast.error(errorMessage);
            })
            .always(function() {
                FormHandler.setLoading($btn, false);
                $form.removeClass('loading');
            });
        },
        
        setLoading: function($btn, isLoading) {
            if (isLoading) {
                const originalText = $btn.html();
                $btn.data('original-text', originalText);
                $btn.addClass('loading');
                $btn.html('<span class="btn-text">' + originalText + '</span>');
            } else {
                const originalText = $btn.data('original-text') || $btn.text();
                $btn.removeClass('loading');
                $btn.html(originalText);
            }
        }
    };

    // Dynamic Item Management (for services, social links, etc.)
    const DynamicItems = {
        init: function() {
            const self = this;
            
            // Add service
            $('#add-service').on('click', function() {
                self.addService();
            });
            
            // Add social link
            $('#add-social-link').on('click', function() {
                self.addSocialLink();
            });
            
            // Add portfolio image
            $('#add-portfolio-image').on('click', function() {
                self.addPortfolioImage();
            });
            
            // Add about section image
            $('#add-about-section-image').on('click', function() {
                self.addAboutSectionImage();
            });
        },
        
        addService: function() {
            const index = $('#services-list .service-item').length;
            const html = `
                <div class="service-item mb-3 p-3 border rounded" data-id="">
                    <form class="config-form" id="service-form-new-${index}">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="PageServiceConfig[${index}][icon]" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="PageServiceConfig[${index}][title]" class="form-control" maxlength="255" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="PageServiceConfig[${index}][description]" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Delay</label>
                            <input type="text" name="PageServiceConfig[${index}][delay]" class="form-control" maxlength="50" value="0">
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="PageServiceConfig[${index}][sort_order]" class="form-control" value="${index}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary save-btn" data-section="service" data-url="/page-config/update-service">Save</button>
                    </form>
                </div>
            `;
            $('#services-list').append(html);
            $('#services-list').scrollTop($('#services-list')[0].scrollHeight);
        },
        
        addSocialLink: function() {
            const index = $('#social-links-list .social-link-item').length;
            const html = `
                <div class="social-link-item mb-3 p-3 border rounded" data-id="">
                    <form class="config-form" id="social-link-form-new-${index}">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label>Platform</label>
                            <input type="text" name="PageSocialLinkConfig[${index}][platform]" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" name="PageSocialLinkConfig[${index}][url]" class="form-control" maxlength="500" required>
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="PageSocialLinkConfig[${index}][icon]" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="PageSocialLinkConfig[${index}][sort_order]" class="form-control" value="${index}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary save-btn" data-section="social-link" data-url="/page-config/update-social-link">Save</button>
                    </form>
                </div>
            `;
            $('#social-links-list').append(html);
            $('#social-links-list').scrollTop($('#social-links-list')[0].scrollHeight);
        },
        
        addPortfolioImage: function() {
            const index = $('#portfolio-images-list .portfolio-image-item').length;
            const html = `
                <div class="portfolio-image-item mb-3 p-3 border rounded" data-id="">
                    <form class="config-form" id="portfolio-image-form-new-${index}">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" name="PagePortfolioImageConfig[${index}][url]" class="form-control" maxlength="500" required>
                        </div>
                        <div class="form-group">
                            <label>Alt</label>
                            <input type="text" name="PagePortfolioImageConfig[${index}][alt]" class="form-control" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="PagePortfolioImageConfig[${index}][title]" class="form-control" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="PagePortfolioImageConfig[${index}][sort_order]" class="form-control" value="${index}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary save-btn" data-section="portfolio-image" data-url="/page-config/update-portfolio-image">Save</button>
                    </form>
                </div>
            `;
            $('#portfolio-images-list').append(html);
            $('#portfolio-images-list').scrollTop($('#portfolio-images-list')[0].scrollHeight);
        },
        
        addAboutSectionImage: function() {
            const index = $('#about-section-images-list .about-section-image-item').length;
            const html = `
                <div class="about-section-image-item mb-3 p-3 border rounded" data-id="">
                    <form class="config-form" id="about-section-image-form-new-${index}">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="PageAboutSectionImageConfig[${index}][icon]" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label>Text</label>
                            <input type="text" name="PageAboutSectionImageConfig[${index}][text]" class="form-control" maxlength="255" required>
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="PageAboutSectionImageConfig[${index}][sort_order]" class="form-control" value="${index}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary save-btn" data-section="about-section-image" data-url="/page-config/update-about-section-image">Save</button>
                    </form>
                </div>
            `;
            $('#about-section-images-list').append(html);
            $('#about-section-images-list').scrollTop($('#about-section-images-list')[0].scrollHeight);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        FormHandler.init();
        DynamicItems.init();
    });

})(jQuery);

