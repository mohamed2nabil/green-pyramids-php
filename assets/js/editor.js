// ============================================
// Content Editor - Enhanced Version
// With Error Handling, Toast Notifications, and Loading States
// ============================================

// Toast Notification System
class Toast {
    static show(message, type = 'success', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideInUp 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    static success(message) {
        this.show(message, 'success');
    }

    static error(message) {
        this.show(message, 'error', 5000);
    }
}

// Auto-save with debounce
const saveTimers = {};

function debounce(fn, delay, id) {
    clearTimeout(saveTimers[id]);
    saveTimers[id] = setTimeout(() => {
        fn();
    }, delay);
}

// Save slide heading or subtext
async function saveSlideField(slideId, field, value) {
    const syncIndicator = document.querySelector(
        `[data-slide-id="${slideId}"][${field === 'heading' ? 'class*="slide-heading' : 'class*="slide-subtext'}"]`
    )?.parentElement?.querySelector('.sync-indicator');

    try {
        if (syncIndicator) {
            syncIndicator.classList.add('saving');
            syncIndicator.textContent = 'Saving...';
        }

        const response = await fetch('api/save_content.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=update_slide&slide_id=${slideId}&heading=${encodeURIComponent(
                field === 'heading' ? value : document.querySelector(`[data-slide-id="${slideId}"].slide-heading`)?.value || ''
            )}&subtext=${encodeURIComponent(
                field === 'subtext' ? value : document.querySelector(`[data-slide-id="${slideId}"].slide-subtext`)?.value || ''
            )}`
        });

        const result = await response.json();

        if (syncIndicator) {
            if (result.success) {
                syncIndicator.classList.remove('saving');
                syncIndicator.textContent = '✓ Saved';
                syncIndicator.style.color = '#10B981';
                setTimeout(() => {
                    syncIndicator.textContent = '';
                    syncIndicator.style.color = '';
                }, 2000);
            } else {
                throw new Error(result.error || 'Failed to save');
            }
        }
    } catch (error) {
        console.error('Save error:', error);
        if (syncIndicator) {
            syncIndicator.classList.remove('saving');
            syncIndicator.textContent = '✗ Error';
            syncIndicator.style.color = '#EF4444';
        }
        Toast.error('Failed to save: ' + error.message);
    }
}

// Save section field
async function saveSectionField(page, section, field, value) {
    const card = document.querySelector(`[data-page="${page}"][data-section="${section}"]`);
    const syncIndicator = card?.querySelector(`[${field === 'heading' ? 'class*="section-heading' : 'class*="section-subtext'}"]`)?.parentElement?.querySelector('.sync-indicator');

    try {
        if (syncIndicator) {
            syncIndicator.classList.add('saving');
            syncIndicator.textContent = 'Saving...';
        }

        const heading = card?.querySelector('.section-heading')?.value || '';
        const subtext = card?.querySelector('.section-subtext')?.value || '';

        const response = await fetch('api/save_content.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=update_section&page=${page}&section=${section}&heading=${encodeURIComponent(heading)}&subtext=${encodeURIComponent(subtext)}`
        });

        const result = await response.json();

        if (syncIndicator) {
            if (result.success) {
                syncIndicator.classList.remove('saving');
                syncIndicator.textContent = '✓ Saved';
                syncIndicator.style.color = '#10B981';
                setTimeout(() => {
                    syncIndicator.textContent = '';
                    syncIndicator.style.color = '';
                }, 2000);
            } else {
                throw new Error(result.error || 'Failed to save');
            }
        }
    } catch (error) {
        console.error('Save error:', error);
        if (syncIndicator) {
            syncIndicator.classList.remove('saving');
            syncIndicator.textContent = '✗ Error';
            syncIndicator.style.color = '#EF4444';
        }
        Toast.error('Failed to save: ' + error.message);
    }
}

// Upload slide image
async function uploadSlideImage(slideId, file) {
    const input = document.querySelector(`.slide-image-input[data-slide-id="${slideId}"]`);
    const uploader = input?.closest('.image-uploader');
    let preview = uploader?.querySelector('.slide-preview');

    if (!file) return;

    // Validate file
    if (!file.type.startsWith('image/')) {
        Toast.error('Please select a valid image file');
        return;
    }

    if (file.size > 10 * 1024 * 1024) {
        Toast.error('Image size must be less than 10MB');
        return;
    }

    try {
        // Show loading state
        uploader.style.opacity = '0.6';
        uploader.style.pointerEvents = 'none';

        const formData = new FormData();
        formData.append('action', 'upload_slide_image');
        formData.append('slide_id', slideId);
        formData.append('image', file);

        const response = await fetch('api/save_content.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            const timestamp = result.timestamp || Date.now();
            const imageUrl = result.image_path + '?t=' + timestamp;

            if (!preview && uploader) {
                preview = document.createElement('img');
                preview.className = 'slide-preview';
                preview.alt = 'Slide image';
                uploader.querySelector('i')?.remove();
                uploader.querySelector('p')?.remove();
                uploader.prepend(preview);
            }

            if (preview) {
                preview.src = imageUrl;
                preview.style.display = 'block';
            }
            Toast.success('Image uploaded successfully');
        } else {
            throw new Error(result.error || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload error:', error);
        Toast.error('Failed to upload image: ' + error.message);
    } finally {
        uploader.style.opacity = '1';
        uploader.style.pointerEvents = 'auto';
    }
}

// Upload section image
async function uploadSectionImage(page, section, file) {
    const card = document.querySelector(`[data-page="${page}"][data-section="${section}"]`);
    const uploader = card?.querySelector('.image-uploader');
    let preview = uploader?.querySelector('.section-preview');

    if (!file) return;

    // Validate file
    if (!file.type.startsWith('image/')) {
        Toast.error('Please select a valid image file');
        return;
    }

    if (file.size > 10 * 1024 * 1024) {
        Toast.error('Image size must be less than 10MB');
        return;
    }

    try {
        // Show loading state
        uploader.style.opacity = '0.6';
        uploader.style.pointerEvents = 'none';

        const formData = new FormData();
        formData.append('action', 'upload_section_image');
        formData.append('page', page);
        formData.append('section', section);
        formData.append('image', file);

        const response = await fetch('api/save_content.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            const timestamp = result.timestamp || Date.now();
            const imageUrl = result.image_path + '?t=' + timestamp;

            if (!preview && uploader) {
                preview = document.createElement('img');
                preview.className = 'section-preview';
                preview.alt = 'Section image';
                uploader.querySelector('i')?.remove();
                uploader.querySelector('p')?.remove();
                uploader.prepend(preview);
            }

            if (preview) {
                preview.src = imageUrl;
                preview.style.display = 'block';
            }
            Toast.success('Image uploaded successfully');
        } else {
            throw new Error(result.error || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload error:', error);
        Toast.error('Failed to upload image: ' + error.message);
    } finally {
        uploader.style.opacity = '1';
        uploader.style.pointerEvents = 'auto';
    }
}

// Toggle slide visibility
async function toggleSlideVisibility(slideId, isVisible) {
    console.log('toggleSlideVisibility called for slide:', slideId, 'target visibility:', isVisible);
    try {
        const response = await fetch('api/save_content.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=toggle_slide_visibility&slide_id=${slideId}&is_visible=${isVisible ? 1 : 0}`
        });

        const result = await response.json();

        if (result.success) {
            const btn = document.querySelector(`[data-slide-id="${slideId}"] .slide-visibility-btn`);
            const card = document.querySelector(`[data-slide-id="${slideId}"]`);
            
            if (btn) {
                const icon = btn.querySelector('i');
                const text = btn.querySelector('.btn-text');
                if (isVisible) {
                    icon.className = 'fas fa-eye';
                    if (text) text.textContent = 'Visible';
                    btn.classList.remove('hidden-state');
                    card.classList.remove('opacity-muted');
                } else {
                    icon.className = 'fas fa-eye-slash';
                    if (text) text.textContent = 'Stopped';
                    btn.classList.add('hidden-state');
                    card.classList.add('opacity-muted');
                }
                btn.dataset.visible = isVisible ? "1" : "0";
            }
            Toast.success(`Slide ${isVisible ? 'is now visible' : 'is now hidden'}`);
        } else {
            throw new Error(result.error || 'Failed to update visibility');
        }
    } catch (error) {
        console.error('Visibility toggle error:', error);
        Toast.error('Failed to update visibility: ' + error.message);
    }
}

// Initialize DOM when ready
document.addEventListener('DOMContentLoaded', () => {
    try {
        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active to clicked
                btn.classList.add('active');
                const tabId = btn.getAttribute('data-tab');
                document.getElementById(tabId + '-tab')?.classList.add('active');
            });
        });

        // Slide auto-save on input
        document.querySelectorAll('.slide-heading').forEach(input => {
            input.addEventListener('input', () => {
                const slideId = input.dataset.slideId;
                debounce(() => {
                    saveSlideField(slideId, 'heading', input.value);
                }, 800, `slide-heading-${slideId}`);
            });
        });

        document.querySelectorAll('.slide-subtext').forEach(input => {
            input.addEventListener('input', () => {
                const slideId = input.dataset.slideId;
                debounce(() => {
                    saveSlideField(slideId, 'subtext', input.value);
                }, 800, `slide-subtext-${slideId}`);
            });
        });

        // Slide image upload
        document.querySelectorAll('.slide-image-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const slideId = e.target.dataset.slideId;
                uploadSlideImage(slideId, e.target.files[0]);
            });
        });


        // Section auto-save on input
        document.querySelectorAll('.section-heading').forEach(input => {
            input.addEventListener('input', () => {
                const { page, section } = input.dataset;
                debounce(() => {
                    saveSectionField(page, section, 'heading', input.value);
                }, 800, `section-heading-${page}-${section}`);
            });
        });

        document.querySelectorAll('.section-subtext').forEach(input => {
            input.addEventListener('input', () => {
                const { page, section } = input.dataset;
                debounce(() => {
                    saveSectionField(page, section, 'subtext', input.value);
                }, 800, `section-subtext-${page}-${section}`);
            });
        });

        // Section image upload
        document.querySelectorAll('.section-image-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const { page, section } = e.target.dataset;
                uploadSectionImage(page, section, e.target.files[0]);
            });
        });

        // Publish button
        document.getElementById('publishBtn')?.addEventListener('click', async () => {
            try {
                const btn = document.getElementById('publishBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';

                // Simulate publish delay
                await new Promise(resolve => setTimeout(resolve, 1000));

                Toast.success('✓ All changes published live!');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-rocket"></i> Publish Live';
            } catch (error) {
                Toast.error('Failed to publish: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-rocket"></i> Publish Live';
            }
        });
    } catch (error) {
        console.error('Initialization error:', error);
        Toast.error('Failed to initialize editor');
    }
});

// Add fade out animation to CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        to { opacity: 0; transform: translateY(-10px); }
    }
`;
document.head.appendChild(style);
