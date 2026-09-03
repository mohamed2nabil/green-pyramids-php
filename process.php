<?php include "includes/header.php"; 
$sections = [];
if (isset($conn)) {
    $r = $conn->query("SELECT * FROM page_sections WHERE page = 'process'");
    while ($row = $r->fetch_assoc()) {
        $sections[$row['section']] = $row;
    }
}
?>


<div class="bg-white min-h-screen pt-32 pb-32 relative text-[#173F35] overflow-hidden" id="process-wrapper">
  
  <div class="max-w-3xl mx-auto px-6 relative z-10 text-center mb-24">
    <h1 class="anim-heading font-serif text-5xl lg:text-6xl mb-4 text-[#173F35]">The Journey</h1>
    <p class="text-[#173F35]/60 text-lg">Every great journey starts with a single step. A transparent path from Egyptian soil to global markets.</p>
  </div>

  <style>
    .journey-dot {
      position: absolute;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background-color: #173F35;
      box-shadow: 0 0 0 6px rgba(143, 174, 93, 0.25);
      transform: translate(-50%, -50%);
      z-index: 10;
    }
    
    .journey-box {
      position: absolute;
      width: 60%;
      max-width: 450px;
      z-index: 20;
    }
    
    /* Mobile adjustments */
    @media (max-width: 768px) {
      .journey-box { width: 70%; }
      .text-responsive { text-align: left !important; }
    }
  </style>

  <div class="relative w-full max-w-7xl mx-auto px-0" style="height: 2800px;" id="journey-container">
    
    <!-- The SVG curved path line -->
    <div class="absolute inset-0 z-0 pointer-events-none w-full h-full">
      <svg width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 1000 3000" class="overflow-visible">
        <path id="journey-path-bg" 
              d="M 500,0 C 500,150 200,150 200,300 C 200,525 800,525 800,750 C 800,975 200,975 200,1200 C 200,1425 800,1425 800,1650 C 800,1875 200,1875 200,2100 C 200,2325 500,2325 500,2550 C 500,2775 500,3000 500,3000" 
              fill="none" stroke="#FFFFFF" stroke-width="3" vector-effect="nonScalingStroke"></path>
        
        <path id="journey-path" 
              d="M 500,0 C 500,150 200,150 200,300 C 200,525 800,525 800,750 C 800,975 200,975 200,1200 C 200,1425 800,1425 800,1650 C 800,1875 200,1875 200,2100 C 200,2325 500,2325 500,2550 C 500,2775 500,3000 500,3000" 
              fill="none" stroke="#8FAE5D" stroke-width="3" stroke-linecap="round" vector-effect="nonScalingStroke"></path>
      </svg>
    </div>

    <!-- Step 1 -->
    <div class="journey-dot" style="top: 10%; left: 20%;"></div>
    <div class="journey-box left-side" style="top: 10%; left: 24%;">
      <div class="journey-content pl-2 sm:pl-6 text-left">
        <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 01</p>
        <h2 class="font-serif text-2xl sm:text-3xl mb-2 text-[#173F35]"><?= htmlspecialchars($sections['step1']['heading'] ?? 'Farm Sourcing') ?></h2>
        <p class="text-[#173F35]/70 text-xs sm:text-sm leading-relaxed"><?= htmlspecialchars($sections['step1']['subtext'] ?? 'Rigorous farm selection across Egypt\'s key regions to ensure premium export quality.') ?></p>
      </div>
    </div>

    <!-- Step 2 -->
    <div class="journey-dot" style="top: 25%; left: 80%;"></div>
    <div class="journey-box right-side" style="top: 25%; right: 24%;">
      <div class="journey-content pr-2 sm:pr-6 text-right text-responsive">
        <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 02</p>
        <h2 class="font-serif text-2xl sm:text-3xl mb-2 text-[#173F35]"><?= htmlspecialchars($sections['step2']['heading'] ?? 'Harvesting') ?></h2>
        <p class="text-[#173F35]/70 text-xs sm:text-sm leading-relaxed"><?= htmlspecialchars($sections['step2']['subtext'] ?? 'Carefully harvested precisely for optimal export windows, maintaining structural integrity.') ?></p>
      </div>
    </div>

    <!-- Step 3 -->
    <div class="journey-dot" style="top: 40%; left: 20%;"></div>
    <div class="journey-box left-side" style="top: 40%; left: 24%;">
      <div class="journey-content pl-2 sm:pl-6 text-left">
        <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 03</p>
        <h2 class="font-serif text-2xl sm:text-3xl mb-2 text-[#173F35]"><?= htmlspecialchars($sections['step3']['heading'] ?? 'Quality Control') ?></h2>
        <p class="text-[#173F35]/70 text-xs sm:text-sm leading-relaxed"><?= htmlspecialchars($sections['step3']['subtext'] ?? 'Stringent export-grade assessment for size, color, firmness, and natural perfection.') ?></p>
      </div>
    </div>

    <!-- Step 4 -->
    <div class="journey-dot" style="top: 55%; left: 80%;"></div>
    <div class="journey-box right-side" style="top: 55%; right: 24%;">
      <div class="journey-content pr-2 sm:pr-6 text-right text-responsive">
        <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 04</p>
        <h2 class="font-serif text-2xl sm:text-3xl mb-2 text-[#173F35]"><?= htmlspecialchars($sections['step4']['heading'] ?? 'Packing') ?></h2>
        <p class="text-[#173F35]/70 text-xs sm:text-sm leading-relaxed"><?= htmlspecialchars($sections['step4']['subtext'] ?? 'Packed in breathable, export-standard cartons designed to withstand long transit.') ?></p>
      </div>
    </div>

    <!-- Step 5 -->
    <div class="journey-dot" style="top: 70%; left: 20%;"></div>
    <div class="journey-box left-side" style="top: 70%; left: 24%;">
      <div class="journey-content pl-2 sm:pl-6 text-left">
        <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 05</p>
        <h2 class="font-serif text-2xl sm:text-3xl mb-2 text-[#173F35]"><?= htmlspecialchars($sections['step5']['heading'] ?? 'Cold Chain') ?></h2>
        <p class="text-[#173F35]/70 text-xs sm:text-sm leading-relaxed"><?= htmlspecialchars($sections['step5']['subtext'] ?? 'Continuous refrigerated transport that strictly maintains optimal holding temperatures.') ?></p>
      </div>
    </div>

          <!-- Step 6 -->
      <div class="journey-dot" style="top: 85%; left: 50%;"></div>
      <div class="journey-box center-side" style="top: 85%; left: 50%;">
        <div class="journey-content text-center pt-8 w-full max-w-md mx-auto" style="width: 85vw; transform: translateX(-50%);">
          <p class="font-bold text-[10px] sm:text-xs tracking-widest uppercase mb-1 text-[#D8C7A1]">STEP 06</p>
          <h2 class="font-serif text-3xl sm:text-4xl mb-2 text-[#173F35]"><?= htmlspecialchars($sections["step6"]["heading"] ?? "Global Shipment") ?></h2>
          <p class="text-[#173F35]/70 text-sm sm:text-base leading-relaxed"><?= nl2br(htmlspecialchars($sections["step6"]["subtext"] ?? "Complete customs clearance and seamless logistics management from port to port.")) ?></p>
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