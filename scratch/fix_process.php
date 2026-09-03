<?php
$f = "process.php";
$c = file_get_contents($f);

// Since the file is messed up, I will just rewrite the bottom part
$s = strpos($c, "<!-- Step 6 -->");
if ($s !== false) {
    $c = substr($c, 0, $s) . <<<EOD
      <!-- Step 6 -->
      <div class="journey-dot" style="top: 85%; left: 50%;"></div>
      <div class="journey-box center-side" style="top: 85%; left: 50%;">
        <div class="journey-content text-center pt-8 w-full max-w-md mx-auto" style="width: 85vw; transform: translateX(-50%);">
          <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 06</p>
          <h2 class="font-serif text-3xl sm:text-4xl mb-2 text-[#173F35]"><?= htmlspecialchars(\$sections["step6"]["heading"] ?? "Global Shipment") ?></h2>
          <p class="text-[#173F35]/70 text-sm sm:text-base leading-relaxed"><?= nl2br(htmlspecialchars(\$sections["step6"]["subtext"] ?? "Complete customs clearance and seamless logistics management from port to port.")) ?></p>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    gsap.registerPlugin(ScrollTrigger);
    
    // Slight delay to ensure SVG is fully rendered
    setTimeout(() => {
        const path = document.getElementById("journey-path");
        if (!path) return;
        
        const length = path.getTotalLength();
        
        gsap.set(path, {
            strokeDasharray: length,
            strokeDashoffset: length
        });
        
        // Draw the line as you scroll
        gsap.to(path, {
            strokeDashoffset: 0,
            ease: "none",
            scrollTrigger: {
                trigger: "#journey-container",
                start: "top 60%",
                end: "bottom 80%",
                scrub: 1,
            }
        });

        // Animate the text boxes and dots popping in
        gsap.utils.toArray(".journey-box").forEach((box, i) => {
            const content = box.querySelector(".journey-content");
            
            // Determine direction for slide-in
            const isRight = box.classList.contains("right-side");
            const isCenter = box.classList.contains("center-side");
            
            // Setup initial state natively with GSAP so it handles the matrices safely
            if (isRight) {
                gsap.set(content, { x: 50, opacity: 0 });
            } else if (isCenter) {
                gsap.set(content, { y: 50, opacity: 0 });
            } else {
                gsap.set(content, { x: -50, opacity: 0 });
            }

            gsap.to(content, {
                x: 0,
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: box,
                    start: "top 75%",
                    toggleActions: "play none none reverse"
                }
            });
        });

        // Animate dots scaling in
        gsap.utils.toArray(".journey-dot").forEach((dot) => {
            gsap.set(dot, { scale: 0, opacity: 0 });
            gsap.to(dot, {
                scale: 1,
                opacity: 1,
                duration: 0.5,
                ease: "back.out(1.5)",
                scrollTrigger: {
                    trigger: dot,
                    start: "top 75%",
                    toggleActions: "play none none reverse"
                }
            });
        });
    }, 100);
});
</script>
</body>
</html>
EOD;
    file_put_contents($f, $c);
    echo "Fixed process.php";
} else {
    echo "Could not find Step 6 marker";
}
?>
