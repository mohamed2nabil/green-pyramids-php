(function () {
    "use strict";

    window.addEventListener("error", function (event) {
        console.error("Product Management runtime error:", event.message);
    });

    const tbody = document.getElementById("productTableBody");
    const feedback = document.getElementById("ajaxFeedback");
    const catFilter = document.getElementById("categoryFilter");
    const statFilter = document.getElementById("statusFilter");
    const searchInput = document.getElementById("searchInput");
    const searchBtn = document.getElementById("searchBtn");
    const scrollToAddBtn = document.getElementById("scrollToAddBtn");

    const quickAddForm = document.getElementById("quickAddForm");
    const quickAddBtn = document.getElementById("quickAddBtn");
    const quickImgInput = document.getElementById("quickImage");
    const quickImgPrev = document.getElementById("quickImagePreview");

    const editModal = document.getElementById("editModal");
    const editForm = document.getElementById("editProductForm");
    const editPid = document.getElementById("editProductId");
    const saveEditBtn = document.getElementById("saveEditBtn");
    const closeEditBtn = document.getElementById("closeEditModal");
    const cancelEditBtn = document.getElementById("cancelEdit");

    const deleteModal = document.getElementById("deleteModal");
    const confirmDelBtn = document.getElementById("confirmDelete");
    const cancelDelBtn = document.getElementById("cancelDelete");

    let pendingDeleteId = 0;
    let feedbackTimer = null;
    let fetchController = null; // لتحسين أداء الطلبات وإلغاء القديم منها

    const endpointList = "api/list_products.php";
    const endpointSave = "api/save_product.php";
    const endpointDelete = "api/delete_product.php";
    const endpointToggle = "api/toggle_visibility.php";

    function showFeedback(msg, isErr) {
        if (!feedback || !msg) return;
        feedback.textContent = msg;
        feedback.className = isErr ? "err" : "ok";
        feedback.style.display = "block";
        clearTimeout(feedbackTimer);
        feedbackTimer = setTimeout(() => {
            feedback.className = "";
            feedback.textContent = "";
            feedback.style.display = "none";
        }, 5000);
    }

    function filterParams() {
        return new URLSearchParams({
            ajax: "1",
            category: catFilter ? catFilter.value : "all",
            filter: statFilter ? statFilter.value : "all",
            search: searchInput ? searchInput.value : "",
        });
    }

    // 🌟 تحسين الأداء 1: إلغاء الطلبات المتداخلة لتسريع البحث
    async function loadProducts() {
        if (fetchController) fetchController.abort(); // إلغاء أي طلب قديم لم يكتمل
        fetchController = new AbortController();

        try {
            const r = await fetch(endpointList + "?" + filterParams(), {
                headers: { "X-Requested-With": "XMLHttpRequest" },
                signal: fetchController.signal
            });
            const data = await r.json();
            if (tbody) {
                tbody.innerHTML = data.rowsHtml || "";
                // لم نعد بحاجة لاستدعاء دالة ربط الأزرار هنا (تم حلها بالـ Event Delegation)
            }
        } catch (e) {
            if (e.name !== "AbortError") {
                console.error("loadProducts error:", e);
            }
        }
    }

    async function postAction(fd) {
        try {
            const r = await fetch(endpointSave, {
                method: "POST",
                body: fd,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            
            const data = await r.json();

            if (data.success) {
                showFeedback("Saved Successfully!", false);
                loadProducts(); 
                return data;
            } else {
                showFeedback(data.error || "Save Failed in Database", true);
                alert("Database Error: " + (data.error || "Unknown Error"));
                return { success: false };
            }
        } catch (e) {
            console.error("Fetch Error:", e);
            showFeedback("Server connection failed", true);
            loadProducts();
            return { success: false };
        }
    }

    function openModal(el) {
        if (!el) return;
        el.classList.add("open");
        el.setAttribute("aria-hidden", "false");
    }

    function closeModal(el) {
        if (!el) return;
        el.classList.remove("open");
        el.setAttribute("aria-hidden", "true");
    }

    if (catFilter) catFilter.addEventListener("change", loadProducts);
    if (statFilter) statFilter.addEventListener("change", loadProducts);
    if (searchBtn) searchBtn.addEventListener("click", loadProducts);

    if (searchInput) {
        let debounce;
        searchInput.addEventListener("input", () => {
            clearTimeout(debounce);
            debounce = setTimeout(loadProducts, 300);
        });
    }

    if (scrollToAddBtn) {
        scrollToAddBtn.addEventListener("click", () => {
            const f = document.getElementById("quickTitle");
            if (f) {
                f.scrollIntoView({ behavior: "smooth", block: "center" });
                f.focus();
            }
        });
    }

    async function handleQuickAdd() {
        if (!quickAddForm.checkValidity()) {
            quickAddForm.reportValidity();
            return;
        }

        quickAddBtn.disabled = true;
        quickAddBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        try {
            const fd = new FormData(quickAddForm);
            const data = await postAction(fd);
            if (data && data.success) {
                quickAddForm.reset();
                if (quickImgPrev) quickImgPrev.src = "../assets/images/default-product.png";
                if (quickSeasonality) quickSeasonality.updateDisplay();
                tbody.scrollIntoView({ behavior: "smooth", block: "nearest" });
            }
        } catch (err) {
            console.error("Form submit error", err);
        } finally {
            quickAddBtn.disabled = false;
            quickAddBtn.innerHTML = 'Add Product';
        }
    }

    if (quickAddForm) {
        quickAddForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            await handleQuickAdd();
        });
    }

    async function handleEditSave() {
        if (!editForm.checkValidity()) {
            editForm.reportValidity();
            return;
        }
        const pid = editPid ? editPid.value.trim() : "";
        
        saveEditBtn.disabled = true;
        saveEditBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        try {
            const fd = new FormData(editForm);
            fd.set("product_id", pid);
            const data = await postAction(fd);
            if (data && data.success) closeModal(editModal);
        } catch (err) {
            console.error("Edit submit error", err);
        } finally {
            saveEditBtn.disabled = false;
            saveEditBtn.innerHTML = 'Save Changes';
        }
    }

    if (editForm) {
        editForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            await handleEditSave();
        });
    }

    function setupSeasonalityDropdown(displayId, dropdownId) {
        const display = document.getElementById(displayId);
        const dropdown = document.getElementById(dropdownId);
        if (!display || !dropdown) return;

        display.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdown.classList.toggle("show");
            display.classList.toggle("active");
        });

        document.addEventListener("click", (e) => {
            if (!display.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove("show");
                display.classList.remove("active");
            }
        });

        const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
        const updateDisplay = () => {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.nextElementSibling.textContent.substring(0, 3));
            
            const placeholder = display.querySelector(".seasonality-placeholder");
            if (selected.length === 0) {
                placeholder.textContent = "Select Availability Months...";
                placeholder.style.color = "#94a3b8";
            } else if (selected.length === 12) {
                placeholder.textContent = "All Year Round";
                placeholder.style.color = "#1e293b";
            } else {
                placeholder.textContent = selected.join(", ");
                placeholder.style.color = "#1e293b";
            }
        };

        checkboxes.forEach(cb => cb.addEventListener("change", updateDisplay));
        updateDisplay();
        return { updateDisplay };
    }

    const quickSeasonality = setupSeasonalityDropdown("quickSeasonalityDisplay", "quickSeasonalityDropdown");
    const editSeasonality = setupSeasonalityDropdown("editSeasonalityDisplay", "editSeasonalityDropdown");

    function bindPreview(inp, img) {
        if (!inp || !img) return;
        inp.addEventListener("change", () => {
            const f = inp.files && inp.files[0];
            if (!f) return;
            const url = URL.createObjectURL(f);
            img.src = url;
        });
    }

    bindPreview(quickImgInput, quickImgPrev);
    bindPreview(document.getElementById("editImage"), document.getElementById("editImagePreview"));

    // 🌟 تحسين الأداء 2: استخدام Event Delegation بدلاً من عمل Loop مع كل تحديث للجدول
    if (tbody) {
        tbody.addEventListener("click", async function (e) {
            
            // 1. زرار التبديل (Toggle Visibility)
            const toggleBtn = e.target.closest(".toggle-visibility-btn");
            if (toggleBtn) {
                const fd = new FormData();
                fd.append("product_id", toggleBtn.dataset.productId || "0");
                await fetch(endpointToggle, { method: "POST", body: fd, headers: { "X-Requested-With": "XMLHttpRequest" } });
                loadProducts();
                return;
            }

            // 2. زرار الحذف (Delete)
            const deleteBtn = e.target.closest(".delete-product-btn");
            if (deleteBtn) {
                pendingDeleteId = parseInt(deleteBtn.dataset.productId, 10) || 0;
                openModal(deleteModal);
                return;
            }

            // 3. زرار التعديل (Edit)
            const editBtn = e.target.closest(".edit-product-btn");
            if (editBtn) {
                if (editPid) editPid.value = editBtn.dataset.productId || "";
                document.getElementById("editTitle").value = editBtn.dataset.name || "";
                document.getElementById("editCategory").value = editBtn.dataset.categoryId || "";
                document.getElementById("editGrade").value = editBtn.dataset.grade || "";
                document.getElementById("editDescription").value = editBtn.dataset.description || "";
                document.getElementById("editImagePreview").src = editBtn.dataset.image || "../assets/images/default-product.png";

                const hsCodeEl = document.getElementById("edit_hs_code");
                if (hsCodeEl) hsCodeEl.value = (editBtn.dataset.hsCode !== "null" && editBtn.dataset.hsCode) ? editBtn.dataset.hsCode : "";
                
                const varietyEl = document.getElementById("edit_variety");
                if (varietyEl) varietyEl.value = (editBtn.dataset.variety !== "null" && editBtn.dataset.variety) ? editBtn.dataset.variety : "";
                
                const sizesEl = document.getElementById("edit_sizes");
                if (sizesEl) sizesEl.value = (editBtn.dataset.sizes !== "null" && editBtn.dataset.sizes) ? editBtn.dataset.sizes : "";
                
                const packagingEl = document.getElementById("edit_packaging_types");
                if (packagingEl) packagingEl.value = (editBtn.dataset.packagingTypes !== "null" && editBtn.dataset.packagingTypes) ? editBtn.dataset.packagingTypes : "";
                
                const shippingEl = document.getElementById("edit_shipping_method");
                if (shippingEl) shippingEl.value = (editBtn.dataset.shippingMethod !== "null" && editBtn.dataset.shippingMethod) ? editBtn.dataset.shippingMethod : "";
                
                const capacityEl = document.getElementById("edit_container_capacity");
                if (capacityEl) capacityEl.value = (editBtn.dataset.containerCapacity !== "null" && editBtn.dataset.containerCapacity) ? editBtn.dataset.containerCapacity : "";

                const months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                months.forEach(m => {
                    const cb = document.getElementById("edit_avail_" + m);
                    if (cb) cb.checked = editBtn.dataset["avail" + m.charAt(0).toUpperCase() + m.slice(1)] === "1";
                });
                
                if (editSeasonality) editSeasonality.updateDisplay();
                openModal(editModal);
            }
        });
    }

    if (confirmDelBtn) {
        confirmDelBtn.addEventListener("click", async () => {
            const fd = new FormData();
            fd.append("product_id", String(pendingDeleteId));
            const r = await fetch(endpointDelete, { method: "POST", body: fd, headers: { "X-Requested-With": "XMLHttpRequest" } });
            const data = await r.json();
            if (data.success) {
                loadProducts();
                showFeedback("Product deleted", false);
            }
            closeModal(deleteModal);
        });
    }

    if (cancelDelBtn) cancelDelBtn.addEventListener("click", () => closeModal(deleteModal));
    if (closeEditBtn) closeEditBtn.addEventListener("click", () => closeModal(editModal));
    if (cancelEditBtn) cancelEditBtn.addEventListener("click", () => closeModal(editModal));

    // استدعاء أولي للمنتجات (اختياري لو مش موجودة بالـ PHP)
    // loadProducts();
})();
