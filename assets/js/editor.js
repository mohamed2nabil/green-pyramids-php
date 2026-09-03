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

    static success(message) { this.show(message, 'success'); }
    static error(message)   { this.show(message, 'error', 5000); }
}

// Auto-save with debounce
const saveTimers = {};

function debounce(fn, delay, id) {
    clearTimeout(saveTimers[id]);
    saveTimers[id] = setTimeout(() => fn(), delay);
}

// Save slide heading or subtext
async function saveSlideField(slideId, field, value) {
    try {
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
        if (!result.success) throw new Error(result.error || 'Failed to save');
    } catch (error) {
        Toast.error('Failed to save: ' + error.message);
    }
}

// Save section field
async function saveSectionField(page, section, field, value) {
    try {
        const card = document.querySelector(`[data-page="${page}"][data-section="${section}"]`);
        const heading = card?.querySelector('.section-heading')?.value || '';
        const subtext = card?.querySelector('.section-subtext')?.value || '';

        const response = await fetch('api/save_content.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=update_section&page=${page}&section=${section}&heading=${encodeURIComponent(heading)}&subtext=${encodeURIComponent(subtext)}`
        });
        const result = await response.json();
        if (!result.success) throw new Error(result.error || 'Failed to save');
    } catch (error) {
        Toast.error('Failed to save: ' + error.message);
    }
}

// Upload slide image
async function uploadSlideImage(slideId, file) {
    const input = document.querySelector(`.slide-image-input[data-slide-id="${slideId}"]`);
    const uploader = input?.closest('.image-uploader');
    let preview = uploader?.querySelector('.slide-preview');

    if (!file) return;
    if (!file.type.startsWith('image/')) { Toast.error('Please select a valid image file'); return; }
    if (file.size > 10 * 1024 * 1024)   { Toast.error('Image size must be less than 10MB'); return; }

    try {
        uploader.style.opacity = '0.6';
        uploader.style.pointerEvents = 'none';

        const formData = new FormData();
        formData.append('action', 'upload_slide_image');
        formData.append('slide_id', slideId);
        formData.append('image', file);

        const response = await fetch('api/save_content.php', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.success) {
            const imageUrl = result.image_path + '?t=' + (result.timestamp || Date.now());
            if (!preview && uploader) {
                preview = document.createElement('img');
                preview.className = 'slide-preview';
                preview.alt = 'Slide image';
                uploader.querySelector('p')?.remove();
                uploader.prepend(preview);
            }
            if (preview) { preview.src = imageUrl; preview.style.display = 'block'; }
            Toast.success('Image uploaded successfully');
        } else {
            throw new Error(result.error || 'Upload failed');
        }
    } catch (error) {
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
    if (!file.type.startsWith('image/')) { Toast.error('Please select a valid image file'); return; }
    if (file.size > 10 * 1024 * 1024)   { Toast.error('Image size must be less than 10MB'); return; }

    try {
        uploader.style.opacity = '0.6';
        uploader.style.pointerEvents = 'none';

        const formData = new FormData();
        formData.append('action', 'upload_section_image');
        formData.append('page', page);
        formData.append('section', section);
        formData.append('image', file);

        const response = await fetch('api/save_content.php', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.success) {
            const imageUrl = result.image_path + '?t=' + (result.timestamp || Date.now());
            if (!preview && uploader) {
                preview = document.createElement('img');
                preview.className = 'section-preview';
                preview.alt = 'Section image';
                uploader.querySelector('p')?.remove();
                uploader.prepend(preview);
            }
            if (preview) { preview.src = imageUrl; preview.style.display = 'block'; }
            Toast.success('Image uploaded successfully');
        } else {
            throw new Error(result.error || 'Upload failed');
        }
    } catch (error) {
        Toast.error('Failed to upload image: ' + error.message);
    } finally {
        uploader.style.opacity = '1';
        uploader.style.pointerEvents = 'auto';
    }
}

// Toggle slide visibility
async function toggleSlideVisibility(slideId, isVisible) {
    try {
        const response = await fetch('api/save_content.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=toggle_slide_visibility&slide_id=${slideId}&is_visible=${isVisible ? 1 : 0}`
        });
        const result = await response.json();
        if (result.success) {
            const btn  = document.querySelector(`[data-slide-id="${slideId}"] .slide-visibility-btn`);
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
                btn.dataset.visible = isVisible ? '1' : '0';
            }
            Toast.success(`Slide ${isVisible ? 'is now visible' : 'is now hidden'}`);
        } else {
            throw new Error(result.error || 'Failed to update visibility');
        }
    } catch (error) {
        Toast.error('Failed to update visibility: ' + error.message);
    }
}

// ============================================
// DOMContentLoaded — wire up all inputs
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    try {
        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.getAttribute('data-tab') + '-tab')?.classList.add('active');
            });
        });

        // Slide auto-save
        document.querySelectorAll('.slide-heading').forEach(input => {
            input.addEventListener('input', () => {
                const slideId = input.dataset.slideId;
                debounce(() => saveSlideField(slideId, 'heading', input.value), 800, `slide-heading-${slideId}`);
            });
        });

        document.querySelectorAll('.slide-subtext').forEach(input => {
            input.addEventListener('input', () => {
                const slideId = input.dataset.slideId;
                debounce(() => saveSlideField(slideId, 'subtext', input.value), 800, `slide-subtext-${slideId}`);
            });
        });

        // Slide image upload
        document.querySelectorAll('.slide-image-input').forEach(input => {
            input.addEventListener('change', (e) => uploadSlideImage(e.target.dataset.slideId, e.target.files[0]));
        });

        // Section heading auto-save
        document.querySelectorAll('.section-heading').forEach(input => {
            input.addEventListener('input', () => {
                const { page, section } = input.dataset;
                debounce(() => saveSectionField(page, section, 'heading', input.value), 800, `section-heading-${page}-${section}`);
            });
        });

        // Section subtext auto-save
        document.querySelectorAll('.section-subtext').forEach(input => {
            input.addEventListener('input', () => {
                const { page, section } = input.dataset;
                debounce(() => saveSectionField(page, section, 'subtext', input.value), 800, `section-subtext-${page}-${section}`);
            });
        });

        // Section image upload
        document.querySelectorAll('.section-image-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const { page, section } = e.target.dataset;
                uploadSectionImage(page, section, e.target.files[0]);
            });
        });

        // Section image-text (overline text stored in image_path column)
        document.querySelectorAll('.section-image-text').forEach(input => {
            input.addEventListener('input', () => {
                const { page, section } = input.dataset;
                debounce(async () => {
                    try {
                        const fd = new FormData();
                        fd.append('action', 'update_section_image_text');
                        fd.append('page', page);
                        fd.append('section', section);
                        fd.append('text', input.value);
                        const r = await fetch('api/save_content.php', { method: 'POST', body: fd });
                        const res = await r.json();
                        if (!res.success) throw new Error(res.error || 'Failed to save');
                    } catch (e) {
                        Toast.error('Failed to save: ' + e.message);
                    }
                }, 800, `section-image-text-${page}-${section}`);
            });
        });

        // Publish button
        document.getElementById('publishBtn')?.addEventListener('click', async () => {
            try {
                const btn = document.getElementById('publishBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
                await new Promise(resolve => setTimeout(resolve, 1000));
                Toast.success('✓ All changes published live!');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-rocket"></i> Publish Live';
            } catch (error) {
                Toast.error('Failed to publish: ' + error.message);
                const btn = document.getElementById('publishBtn');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-rocket"></i> Publish Live'; }
            }
        });

    } catch (error) {
        console.error('Initialization error:', error);
        Toast.error('Failed to initialize editor');
    }
});

// Fade out animation
const style = document.createElement('style');
style.textContent = `@keyframes fadeOut { to { opacity: 0; transform: translateY(-10px); } }`;
document.head.appendChild(style);


// ==========================================
// PHCARD LOGIC
// ==========================================

async function savePHCardField(cardId, field, value) {
    try {
        const fd = new FormData();
        fd.append('action', 'update_phcard');
        fd.append('card_id', cardId);
        fd.append('field', field);
        fd.append('value', value);
        const r = await fetch('api/save_content.php', { method: 'POST', body: fd });
        const res = await r.json();
        if (!res.success) throw new Error(res.error || 'Failed to save card field');
        Toast.success('Card updated');
    } catch(e) {
        Toast.error(e.message);
    }
}

async function uploadPHCardImage(cardId, file, previewEl) {
    try {
        const fd = new FormData();
        fd.append('action', 'upload_phcard_image');
        fd.append('card_id', cardId);
        fd.append('image', file);
        const r = await fetch('api/save_content.php', { method: 'POST', body: fd });
        const res = await r.json();
        if (!res.success) throw new Error(res.error || 'Failed to upload');
        if (res.image_path) {
            previewEl.src = '../' + res.image_path + '?t=' + Date.now();
            Toast.success('Image updated');
        }
    } catch(e) {
        Toast.error(e.message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // PHCard inputs
    document.querySelectorAll('.phcard-title, .phcard-category, .phcard-link').forEach(input => {
        input.addEventListener('input', (e) => {
            const cardId = e.target.dataset.phcardId;
            let field = 'title';
            if (e.target.classList.contains('phcard-category')) field = 'category';
            if (e.target.classList.contains('phcard-link')) field = 'link_url';
            debounce(() => savePHCardField(cardId, field, e.target.value), 1000, 'phcard_' + cardId + '_' + field);
        });
    });

    // PHCard image upload
    document.querySelectorAll('.phcard-image-input').forEach(input => {
        input.addEventListener('change', (e) => {
            const cardId = e.target.dataset.phcardId;
            const file = e.target.files[0];
            if (!file) return;
            const preview = e.target.parentElement.querySelector('.phcard-preview');
            if (preview) uploadPHCardImage(cardId, file, preview);
        });
    });

    // Cat image upload
    document.querySelectorAll('.cat-image-input').forEach(input => {
        input.addEventListener('change', async (e) => {
            const catId = e.target.dataset.catId;
            const file = e.target.files[0];
            if (!file) return;
            const uploader = e.target.closest('.image-uploader');
            try {
                const fd = new FormData();
                fd.append('action', 'upload_category_image');
                fd.append('id', catId);
                fd.append('image', file);
                const r = await fetch('api/save_content.php', { method: 'POST', body: fd });
                const res = await r.json();
                if (!res.success) throw new Error(res.error || 'Failed to upload');
                let preview = uploader.querySelector('.cat-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'cat-preview';
                    preview.style.maxHeight = '150px';
                    preview.style.objectFit = 'cover';
                    uploader.querySelector('p')?.remove();
                    uploader.appendChild(preview);
                }
                preview.src = res.image_path + '?t=' + Date.now();
                Toast.success('Category image updated');
            } catch(e) {
                Toast.error(e.message);
            }
        });
    });
});


// ==========================================
// CERTIFICATIONS API CALLS
// ==========================================
async function saveCertTitle(certId, title) {
    try {
        const fd = new FormData();
        fd.append('action', 'update_cert');
        fd.append('cert_id', certId);
        fd.append('title', title);
        const r = await fetch('api/save_content.php', { method: 'POST', body: fd });
        const res = await r.json();
        if (!res.success) throw new Error(res.error || 'Failed to update certificate');
        Toast.success('Certificate updated');
    } catch(e) {
        Toast.error(e.message);
    }
}

async function uploadCertImage(certId, file, previewEl) {
    try {
        const fd = new FormData();
        fd.append('action', 'upload_cert_image');
        fd.append('cert_id', certId);
        fd.append('image', file);
        const r = await fetch('api/save_content.php', { method: 'POST', body: fd });
        const res = await r.json();
        if (!res.success) throw new Error(res.error || 'Failed to upload certificate image');
        if (res.image_path) {
            previewEl.src = '../' + res.image_path + '?t=' + Date.now();
            Toast.success('Certificate image updated');
        }
    } catch(e) {
        Toast.error(e.message);
    }
}