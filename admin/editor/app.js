/* ====================================================
   Sovereign Ledger — Live Site Editor (Vanilla JS)
   ==================================================== */

const ICON_OPTIONS = [
  'Sprout', 'Leaf', 'Droplets', 'Snowflake', 'Package', 'Truck',
  'Globe', 'ShieldCheck', 'Award', 'Factory', 'Ship', 'TreePine',
];

const defaultData = {
  home: {
    slides: [
      {
        id: 's1',
        heading: 'Stewards of land, sea & ledger',
        subtext: 'Five generations of vertically integrated estates — from highland soils to ocean-frozen exports.',
        image: '',
      },
    ],
    statsVisible: true,
    stats: [
      { id: 'st1', value: '125', label: 'Years of Experience' },
      { id: 'st2', value: '850k', label: 'Tons Exported' },
      { id: 'st3', value: '42', label: 'Estate Properties' },
      { id: 'st4', value: '28', label: 'Export Markets' },
    ],
  },
  about: {
    legacy: 'Founded in 1899 on the windward slopes of the highlands, the Sovereign Ledger has cultivated a singular discipline: every harvest, every freeze, every shipment recorded in one unbroken chain of custody.',
    mission: 'To deliver provenance you can trace, quality you can taste, and continuity that endures generations.',
    vision: 'A world where every premium ingredient carries the name of its estate — and the signature of its steward.',
  },
  process: {
    steps: [
      { id: 'p1', title: 'Sourcing', description: 'Hand-selected from estate-owned plantations and trusted partner farms.', icon: 'Sprout' },
      { id: 'p2', title: 'Quality Control', description: 'Lab-tested at intake. Only the top grade enters the line.', icon: 'ShieldCheck' },
      { id: 'p3', title: 'Washing & Sorting', description: 'Multi-stage cold-water wash and optical sorting to remove imperfections.', icon: 'Droplets' },
      { id: 'p4', title: 'IQF Freezing', description: 'Individually Quick Frozen at -40°C to lock in cellular integrity.', icon: 'Snowflake' },
      { id: 'p5', title: 'Packaging', description: 'Food-grade, retail-ready packaging with full lot traceability codes.', icon: 'Package' },
      { id: 'p6', title: 'Logistics & Export', description: 'Reefer-controlled shipping to 28 markets with real-time chain-of-custody.', icon: 'Truck' },
    ],
  },
  products: {
    categories: [],
    products: []
  },
};

/* -------- State -------- */
let state = JSON.parse(JSON.stringify(defaultData));
let snapshot = JSON.parse(JSON.stringify(state));
let activeTab = 'home';
let device = 'mobile';
let fullscreen = false;

function persist() {}
function uid() { return 'id_' + Math.random().toString(36).slice(2, 9); }

/* -------- Toast -------- */
function toast(title, desc) {
  const el = document.getElementById('toast');
  document.getElementById('toastTitle').textContent = title;
  document.getElementById('toastDesc').textContent = desc || '';
  el.classList.remove('hidden');
  clearTimeout(toast._t);
  toast._t = setTimeout(() => el.classList.add('hidden'), 2400);
}

/* -------- Editor renderers -------- */
function renderHome() {
  const slides = document.getElementById('slidesContainer');
  slides.innerHTML = state.home.slides.map((s, i) => `
    <div class="border border-border rounded-xl p-4 space-y-3 bg-muted/20" data-slide="${s.id}">
      <div class="flex items-center justify-between">
        <p class="text-xs uppercase tracking-wider text-muted-foreground">Slide ${i + 1}</p>
        ${state.home.slides.length > 1 ? `<button class="text-xs text-muted-foreground hover:text-foreground" data-remove-slide="${s.id}">Remove</button>` : ''}
      </div>
      <div>
        <label class="text-xs text-muted-foreground">Heading</label>
        <input class="input mt-1" data-slide-field="heading" data-id="${s.id}" value="${escapeAttr(s.heading)}" />
      </div>
      <div>
        <label class="text-xs text-muted-foreground">Subtext</label>
        <textarea class="textarea mt-1" rows="2" data-slide-field="subtext" data-id="${s.id}">${escapeHtml(s.subtext)}</textarea>
      </div>
      <div class="flex items-center gap-3">
        <label class="upload-pill">
          <i data-lucide="image-up" class="h-4 w-4"></i>
          ${s.image ? 'Replace Image' : 'Upload Image'}
          <input type="file" accept="image/*" class="hidden" data-slide-image="${s.id}" />
        </label>
        ${s.image ? `<img src="${s.image}" class="h-10 w-16 object-cover rounded-md border border-border" />` : '<span class="text-xs text-muted-foreground">No image</span>'}
      </div>
    </div>
  `).join('');

  const stats = document.getElementById('statsContainer');
  stats.innerHTML = state.home.stats.map(st => `
    <div class="border border-border rounded-xl p-3 bg-muted/20">
      <label class="text-xs text-muted-foreground">Label</label>
      <input class="input mt-1" data-stat-field="label" data-id="${st.id}" value="${escapeAttr(st.label)}" />
      <label class="text-xs text-muted-foreground mt-2 block">Value</label>
      <input class="input mt-1" data-stat-field="value" data-id="${st.id}" value="${escapeAttr(st.value)}" />
    </div>
  `).join('');

  document.getElementById('statsVisible').checked = state.home.statsVisible;
  lucide.createIcons();
}

function renderAbout() {
  document.getElementById('aboutLegacy').value = state.about.legacy;
  document.getElementById('aboutMission').value = state.about.mission;
  document.getElementById('aboutVision').value = state.about.vision;
}

function renderProcess() {
  const c = document.getElementById('processContainer');
  c.innerHTML = state.process.steps.map((step, idx) => `
    <div class="process-row">
      <div class="flex flex-col items-center gap-2">
        <div class="h-11 w-11 rounded-lg bg-gradient-emerald text-primary-foreground flex items-center justify-center">
          <i data-lucide="${kebab(step.icon)}" class="h-5 w-5"></i>
        </div>
        <span class="font-serif text-accent text-sm">0${idx + 1}</span>
      </div>
      <div class="space-y-2 min-w-0">
        <div class="grid sm:grid-cols-[1fr_180px] gap-2">
          <input class="input" data-step-field="title" data-id="${step.id}" value="${escapeAttr(step.title)}" />
          <select class="select" data-step-field="icon" data-id="${step.id}">
            ${ICON_OPTIONS.map(o => `<option value="${o}" ${o === step.icon ? 'selected' : ''}>${o}</option>`).join('')}
          </select>
        </div>
        <textarea class="textarea" rows="2" data-step-field="description" data-id="${step.id}">${escapeHtml(step.description)}</textarea>
      </div>
    </div>
  `).join('');
  lucide.createIcons();
}

function renderProducts() {
  loadCategories();
  loadProducts();
}

async function loadCategories() {
  try {
    const response = await fetch('api/get_categories.php');
    const result = await response.json();
    if (result.success) {
      state.products.categories = result.data;
      const select = document.getElementById('productCategory');
      select.innerHTML = '<option value="">Select Category</option>' +
        result.data.map(cat => `<option value="${cat.category_id}">${escapeHtml(cat.category_name)}</option>`).join('');
    }
  } catch (error) {
    console.error('Failed to load categories:', error);
  }
}

async function loadProducts() {
  try {
    const response = await fetch('api/get_products.php');
    const result = await response.json();
    if (result.success) {
      state.products.products = result.data;
      renderProductsList(result.data);
    }
  } catch (error) {
    console.error('Failed to load products:', error);
  }
}

function renderProductsList(products) {
  const container = document.getElementById('productsContainer');
  if (products.length === 0) {
    container.innerHTML = '<p class="text-muted-foreground text-sm text-center py-8">No products found. Add your first product above.</p>';
    return;
  }

  container.innerHTML = products.map(product => `
    <div class="border border-border rounded-xl p-4 bg-muted/20">
      <div class="flex items-start justify-between mb-3">
        <div class="flex items-start gap-3 min-w-0">
          <img src="${product.image_path ? '/green light/' + product.image_path : '/green light/../assets/images/product-fruits.jpg'}" 
               alt="${escapeHtml(product.product_name)}" 
               class="h-12 w-12 object-cover rounded-md border border-border flex-shrink-0"
               onerror="this.src='/green light/../assets/images/product-fruits.jpg'">
          <div class="min-w-0">
            <h4 class="font-serif text-sm font-medium truncate">${escapeHtml(product.product_name)}</h4>
            <p class="text-xs text-muted-foreground">${escapeHtml(product.category_name || 'No category')}</p>
            <p class="text-xs text-muted-foreground">Stock: ${product.stock_quantity || 0} ${product.unit || ''}</p>
          </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
          <span class="text-xs px-2 py-1 rounded-full ${product.is_visible ? 'bg-success/20 text-success' : 'bg-muted text-muted-foreground'}">
            ${product.is_visible ? 'Visible' : 'Hidden'}
          </span>
          <button class="text-xs text-muted-foreground hover:text-foreground" data-edit-product="${product.product_id}">
            <i data-lucide="edit" class="h-4 w-4"></i>
          </button>
          <button class="text-xs text-muted-foreground hover:text-destructive" data-delete-product="${product.product_id}">
            <i data-lucide="trash-2" class="h-4 w-4"></i>
          </button>
        </div>
      </div>
      ${product.description ? `<p class="text-xs text-muted-foreground line-clamp-2">${escapeHtml(product.description)}</p>` : ''}
    </div>
  `).join('');
  lucide.createIcons();
}

/* -------- Live preview -------- */
function renderPreview() {
  const c = document.getElementById('previewContent');
  if (activeTab === 'home') c.innerHTML = previewHome();
  else if (activeTab === 'about') c.innerHTML = previewAbout();
  else c.innerHTML = previewProcess();
  lucide.createIcons();
  c.classList.remove('pulse-update');
  void c.offsetWidth;
  c.classList.add('pulse-update');
}

function previewHome() {
  const s = state.home.slides[0] || {};
  const bg = s.image
    ? `background-image: linear-gradient(180deg, hsl(144 30% 10% / 0.3), hsl(144 30% 10% / 0.85)), url('${s.image}'); background-size: cover; background-position: center;`
    : '';
  return `
    <section class="relative min-h-[280px] bg-gradient-emerald text-primary-foreground p-6 flex flex-col justify-end" style="${bg}">
      <p class="text-[10px] uppercase tracking-[0.3em] text-accent mb-2">Sovereign Ledger</p>
      <h1 class="font-serif text-2xl leading-tight mb-2">${escapeHtml(s.heading || 'Your headline')}</h1>
      <p class="text-xs text-primary-foreground/80 leading-relaxed">${escapeHtml(s.subtext || 'Subtext appears here.')}</p>
      <div class="flex gap-2 mt-4">
        <span class="text-[10px] px-3 py-1.5 rounded-full bg-accent text-accent-foreground font-medium">Explore Estates</span>
        <span class="text-[10px] px-3 py-1.5 rounded-full border border-primary-foreground/30 text-primary-foreground">Our Story</span>
      </div>
    </section>
    ${state.home.statsVisible ? `
      <section class="bg-card px-4 py-5 grid grid-cols-2 gap-3 border-b border-border">
        ${state.home.stats.map(st => `
          <div class="text-center p-3 rounded-lg bg-muted/40">
            <p class="font-serif text-xl text-primary">${escapeHtml(st.value || '—')}</p>
            <p class="text-[10px] uppercase tracking-wider text-muted-foreground mt-1">${escapeHtml(st.label)}</p>
          </div>
        `).join('')}
      </section>` : ''}
    <section class="p-6 text-center">
      <p class="text-[10px] uppercase tracking-[0.3em] text-accent mb-2">Heritage</p>
      <h2 class="font-serif text-lg mb-2">Crafted across generations</h2>
      <p class="text-xs text-muted-foreground leading-relaxed">From soil to sea — every link in the chain answers to one ledger.</p>
    </section>
  `;
}

function previewAbout() {
  return `
    <div class="p-6 space-y-6">
      <header>
        <p class="text-[10px] uppercase tracking-[0.3em] text-accent mb-2">About the House</p>
        <h1 class="font-serif text-2xl">Our Legacy</h1>
      </header>
      <p class="text-xs text-muted-foreground leading-relaxed">${escapeHtml(state.about.legacy)}</p>
      <div class="grid gap-4">
        <div class="p-4 rounded-lg bg-muted/40 border border-border">
          <p class="text-[10px] uppercase tracking-wider text-accent mb-1">Mission</p>
          <p class="text-xs leading-relaxed">${escapeHtml(state.about.mission)}</p>
        </div>
        <div class="p-4 rounded-lg bg-gradient-emerald text-primary-foreground">
          <p class="text-[10px] uppercase tracking-wider text-accent mb-1">Vision</p>
          <p class="text-xs leading-relaxed">${escapeHtml(state.about.vision)}</p>
        </div>
      </div>
    </div>
  `;
}

function previewProcess() {
  return `
    <div class="p-6">
      <header class="mb-5">
        <p class="text-[10px] uppercase tracking-[0.3em] text-accent mb-2">The Six-Step Journey</p>
        <h1 class="font-serif text-2xl">From estate to export</h1>
      </header>
      <ol class="space-y-3">
        ${state.process.steps.map((step, idx) => `
          <li class="flex gap-3 p-3 rounded-lg border border-border bg-card">
            <div class="h-9 w-9 shrink-0 rounded-lg bg-gradient-emerald text-primary-foreground flex items-center justify-center">
              <i data-lucide="${kebab(step.icon)}" class="h-4 w-4"></i>
            </div>
            <div class="min-w-0">
              <div class="flex items-baseline gap-2">
                <span class="font-serif text-accent text-sm">0${idx + 1}</span>
                <p class="font-medium text-sm">${escapeHtml(step.title)}</p>
              </div>
              <p class="text-[11px] text-muted-foreground mt-0.5 leading-relaxed">${escapeHtml(step.description)}</p>
            </div>
          </li>
        `).join('')}
      </ol>
    </div>
  `;
}

/* -------- Helpers -------- */
function escapeHtml(s) { return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
function escapeAttr(s) { return escapeHtml(s); }
function kebab(s) { return String(s).replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase(); }

/* -------- Event wiring -------- */
function setTab(name) {
  activeTab = name;
  document.querySelectorAll('.tab-trigger').forEach(b => b.classList.toggle('active', b.dataset.tab === name));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
  const panel = document.getElementById('tab-' + name);
  panel.classList.remove('hidden');
  // Re-trigger animation
  panel.style.animation = 'none'; void panel.offsetWidth; panel.style.animation = '';
  renderPreview();
}

function setDevice(d) {
  device = d;
  document.querySelectorAll('.device-btn').forEach(b => b.classList.toggle('active', b.dataset.device === d));
  const f = document.getElementById('deviceFrame');
  if (d === 'mobile') {
    f.className = 'bg-card rounded-[28px] shadow-elevated overflow-hidden border-[10px] border-foreground/90 transition-all w-full max-w-[380px]';
  } else {
    f.className = 'bg-card rounded-2xl shadow-elevated overflow-hidden border-[6px] border-foreground/90 transition-all w-full max-w-[920px]';
  }
}

function bindEvents() {
  // Tabs
  document.querySelectorAll('.tab-trigger').forEach(b => b.addEventListener('click', () => setTab(b.dataset.tab)));

  // Discard / Publish
  document.getElementById('discardBtn').addEventListener('click', () => {
    state = JSON.parse(JSON.stringify(defaultData));
    snapshot = JSON.parse(JSON.stringify(state));
    persist(); renderAll();
    toast('Changes discarded', 'Editor restored to defaults.');
  });
  document.getElementById('publishBtn').addEventListener('click', () => {
    snapshot = JSON.parse(JSON.stringify(state));
    persist();
    toast('Published live', 'Your changes are now visible.');
  });

  // Device + fullscreen
  document.querySelectorAll('.device-btn').forEach(b => b.addEventListener('click', () => setDevice(b.dataset.device)));
  document.getElementById('fullscreenBtn').addEventListener('click', () => {
    fullscreen = !fullscreen;
    document.getElementById('previewWrap').classList.toggle('fullscreen', fullscreen);
    document.getElementById('fsLabel').textContent = fullscreen ? 'Exit' : 'Full Screen';
  });

  // Delegated input listener for live data binding
  document.addEventListener('input', e => {
    const t = e.target;
    if (t.matches('[data-slide-field]')) {
      const slide = state.home.slides.find(s => s.id === t.dataset.id);
      if (slide) { slide[t.dataset.slideField] = t.value; persist(); renderPreview(); }
    } else if (t.matches('[data-stat-field]')) {
      const st = state.home.stats.find(s => s.id === t.dataset.id);
      if (st) { st[t.dataset.statField] = t.value; persist(); renderPreview(); }
    } else if (t.matches('[data-step-field]')) {
      const step = state.process.steps.find(s => s.id === t.dataset.id);
      if (step) { step[t.dataset.stepField] = t.value; persist(); renderPreview(); if (t.dataset.stepField === 'icon') renderProcess(); }
    } else if (t.id === 'aboutLegacy') { state.about.legacy = t.value; persist(); renderPreview(); }
    else if (t.id === 'aboutMission') { state.about.mission = t.value; persist(); renderPreview(); }
    else if (t.id === 'aboutVision') { state.about.vision = t.value; persist(); renderPreview(); }
  });

  document.addEventListener('change', e => {
    const t = e.target;
    if (t.id === 'statsVisible') { state.home.statsVisible = t.checked; persist(); renderPreview(); }
    else if (t.matches('[data-slide-image]')) {
      const file = t.files?.[0]; if (!file) return;
      const reader = new FileReader();
      reader.onload = ev => {
        const slide = state.home.slides.find(s => s.id === t.dataset.slideImage);
        if (slide) { slide.image = ev.target.result; persist(); renderHome(); renderPreview(); }
      };
      reader.readAsDataURL(file);
    } else if (t.matches('[data-step-field="icon"]')) {
      // handled in input as well; ensure preview refresh
      renderProcess(); renderPreview();
    }
  });

  document.addEventListener('click', e => {
    const t = e.target.closest('[data-remove-slide]');
    if (t) {
      state.home.slides = state.home.slides.filter(s => s.id !== t.dataset.removeSlide);
      persist(); renderHome(); renderPreview();
    }
  });

  document.getElementById('addSlideBtn').addEventListener('click', () => {
    state.home.slides.push({ id: uid(), heading: 'New chapter', subtext: 'Tell its story.', image: '' });
    persist(); renderHome(); renderPreview();
  });

  // Product management events
  document.getElementById('addProductBtn').addEventListener('click', () => {
    showProductForm();
  });

  document.getElementById('cancelProductBtn').addEventListener('click', () => {
    hideProductForm();
  });

  document.getElementById('productFormData').addEventListener('submit', async (e) => {
    e.preventDefault();
    await saveProduct();
  });

  document.addEventListener('change', e => {
    const t = e.target;
    if (t.id === 'productImage') {
      handleImagePreview(t.files?.[0]);
    }
  });

  document.addEventListener('click', e => {
    const t = e.target.closest('[data-edit-product]');
    if (t) {
      editProduct(t.dataset.editProduct);
    }
    const deleteBtn = e.target.closest('[data-delete-product]');
    if (deleteBtn) {
      deleteProduct(deleteBtn.dataset.deleteProduct);
    }
  });
}

// Product management functions
function showProductForm(product = null) {
  const form = document.getElementById('productForm');
  const title = document.getElementById('formTitle');
  const formData = document.getElementById('productFormData');
  
  if (product) {
    title.textContent = 'Edit Product';
    document.getElementById('productId').value = product.product_id;
    document.getElementById('productName').value = product.product_name;
    document.getElementById('productCategory').value = product.category_id;
    document.getElementById('productDescription').value = product.description || '';
    document.getElementById('productPrice').value = product.price || '';
    document.getElementById('productUnit').value = product.unit || '';
    document.getElementById('productStock').value = product.stock_quantity || '';
    document.getElementById('productVisible').checked = product.is_visible == 1;
    document.getElementById('productActive').checked = product.is_active == 1;
    
    if (product.image_path) {
      handleImagePreview(null, product.image_path);
    }
  } else {
    title.textContent = 'Add New Product';
    formData.reset();
    document.getElementById('productId').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('uploadText').textContent = 'Upload Image';
  }
  
  form.classList.remove('hidden');
  form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function hideProductForm() {
  document.getElementById('productForm').classList.add('hidden');
}

async function saveProduct() {
  const formData = new FormData(document.getElementById('productFormData'));
  const saveBtn = document.getElementById('saveProductBtn');
  const originalText = saveBtn.textContent;
  
  saveBtn.textContent = 'Saving...';
  saveBtn.disabled = true;
  
  try {
    const response = await fetch('api/save_product.php', {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      toast('Success', result.message);
      hideProductForm();
      await loadProducts();
    } else {
      toast('Error', result.message);
    }
  } catch (error) {
    console.error('Save error:', error);
    toast('Error', 'Failed to save product');
  } finally {
    saveBtn.textContent = originalText;
    saveBtn.disabled = false;
  }
}

function handleImagePreview(file, existingPath = null) {
  const preview = document.getElementById('imagePreview');
  const img = document.getElementById('previewImg');
  const uploadText = document.getElementById('uploadText');
  
  if (existingPath) {
    img.src = existingPath;
    preview.classList.remove('hidden');
    uploadText.textContent = 'Replace Image';
    return;
  }
  
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      img.src = e.target.result;
      preview.classList.remove('hidden');
      uploadText.textContent = 'Replace Image';
    };
    reader.readAsDataURL(file);
  } else {
    preview.classList.add('hidden');
    uploadText.textContent = 'Upload Image';
  }
}

function editProduct(productId) {
  const product = state.products.products.find(p => p.product_id == productId);
  if (product) {
    showProductForm(product);
  }
}

async function deleteProduct(productId) {
  if (!confirm('Are you sure you want to delete this product?')) return;
  
  try {
    const response = await fetch('api/delete_product.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId })
    });
    
    const result = await response.json();
    
    if (result.success) {
      toast('Success', 'Product deleted');
      await loadProducts();
    } else {
      toast('Error', result.message);
    }
  } catch (error) {
    console.error('Delete error:', error);
    toast('Error', 'Failed to delete product');
  }
}

function renderAll() {
  renderHome(); renderAbout(); renderProcess(); renderProducts(); renderPreview();
}

/* -------- Boot -------- */
document.addEventListener('DOMContentLoaded', () => {
  bindEvents();
  renderAll();
  lucide.createIcons();
});

