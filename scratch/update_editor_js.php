<?php
$f = "assets/js/editor.js";
$c = file_get_contents($f);

$append = <<<EOD

// ==========================================
// PHCARD LOGIC
// ==========================================

async function savePHCardField(cardId, field, value) {
    try {
        const fd = new FormData();
        fd.append("action", "update_phcard");
        fd.append("card_id", cardId);
        fd.append("field", field);
        fd.append("value", value);
        const r = await fetch("api/save_content.php", { method: "POST", body: fd });
        const res = await r.json();
        if(!res.success) throw new Error(res.error || "Failed to save card field");
        Toast.success("Card updated");
    } catch(e) {
        Toast.error(e.message);
    }
}

async function uploadPHCardImage(cardId, file, previewEl) {
    try {
        const fd = new FormData();
        fd.append("action", "upload_phcard_image");
        fd.append("card_id", cardId);
        fd.append("image", file);
        const r = await fetch("api/save_content.php", { method: "POST", body: fd });
        const res = await r.json();
        if(!res.success) throw new Error(res.error || "Failed to upload");
        if(res.image_path) {
            previewEl.src = "../" + res.image_path + "?t=" + Date.now();
            Toast.success("Image updated");
        }
    } catch(e) {
        Toast.error(e.message);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Inputs
    document.querySelectorAll(".phcard-title, .phcard-category, .phcard-link").forEach(input => {
        input.addEventListener("input", (e) => {
            const cardId = e.target.dataset.phcardId;
            let field = "title";
            if(e.target.classList.contains("phcard-category")) field = "category";
            if(e.target.classList.contains("phcard-link")) field = "link_url";
            
            debounce(() => {
                savePHCardField(cardId, field, e.target.value);
            }, 1000, "phcard_" + cardId + "_" + field);
        });
    });

    // Image Upload
    document.querySelectorAll(".phcard-image-input").forEach(input => {
        input.addEventListener("change", (e) => {
            const cardId = e.target.dataset.phcardId;
            const file = e.target.files[0];
            if(!file) return;
            const preview = e.target.parentElement.querySelector(".phcard-preview");
            if(preview) uploadPHCardImage(cardId, file, preview);
        });
    });
});
EOD;

file_put_contents($f, $c . "\n" . $append);
echo "Updated editor.js";
?>
