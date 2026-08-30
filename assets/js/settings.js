document.addEventListener('DOMContentLoaded', () => {

    const formInputs = {
        primaryEmail: document.getElementById('primaryEmail'),
        salesEmail: document.getElementById('salesEmail'),
        phone: document.getElementById('phone'),
        whatsapp: document.getElementById('whatsapp'),
        address: document.getElementById('address'),
        mapsLink: document.getElementById('mapsLink'),
        facebook: document.getElementById('facebook'),
        instagram: document.getElementById('instagram'),
        linkedin: document.getElementById('linkedin')
    };

    const previewElements = {
        email: document.getElementById('preview-email'),
        phone: document.getElementById('preview-phone'),
        address: document.getElementById('preview-address')
    };

    // ✅ تحديث الـ preview
    const updatePreview = () => {
        if (previewElements.email)
            previewElements.email.textContent = formInputs.primaryEmail.value || 'info@sovereignledger.com';

        if (previewElements.phone)
            previewElements.phone.textContent = formInputs.phone.value || '+1 (555) 000-0000';

        if (previewElements.address)
            previewElements.address.textContent = formInputs.address.value || '123 Executive Way, Global District';
    };

    Object.values(formInputs).forEach(input => {
        if (input) {
            input.addEventListener('input', updatePreview);
        }
    });

    // ✅ زرار الحفظ
    const updateBtn = document.querySelector('.btn-global-update');

    if (updateBtn) {
        updateBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
            updateBtn.disabled = true;

            // ✅ نحول البيانات لـ FormData
            const formData = new FormData();

            formData.append('primary_email', formInputs.primaryEmail?.value?.trim() ?? '');
            formData.append('sales_email', formInputs.salesEmail?.value?.trim() ?? '');
            formData.append('general_phone', formInputs.phone?.value?.trim() ?? '');
            formData.append('whatsapp_number', formInputs.whatsapp?.value?.trim() ?? '');
            formData.append('physical_address', formInputs.address?.value?.trim() ?? '');
            formData.append('google_maps_embed', formInputs.mapsLink?.value?.trim() ?? '');
            formData.append('facebook_url', formInputs.facebook?.value?.trim() ?? '');
            formData.append('instagram_url', formInputs.instagram?.value?.trim() ?? '');
            formData.append('linkedin_url', formInputs.linkedin?.value?.trim() ?? '');

            try {
                const res = await fetch('/green-light-admin/api/save_settings.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    throw new Error(data.error || 'Failed to save settings');
                }

                // ✅ نجاح
                updateBtn.innerHTML = '<i class="fas fa-check-circle"></i> Updated Successfully';
                updateBtn.style.backgroundColor = '#10B981';

                setTimeout(() => {
                    location.reload();
                }, 1500);

            } catch (err) {
                // ❌ فشل
                updateBtn.innerHTML = '<i class="fas fa-triangle-exclamation"></i> Save Failed';
                updateBtn.style.backgroundColor = '#EF4444';

                alert(err.message);
            } finally {
                setTimeout(() => {
                    updateBtn.innerHTML = '<i class="fas fa-bolt"></i> Global Update';
                    updateBtn.style.backgroundColor = '';
                    updateBtn.disabled = false;
                }, 2500);
            }
        });
    }

});
