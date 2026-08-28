import { Link } from "react-router-dom";
import { Reveal } from "../components/motion/Reveal";

const VALUES = [
  { title: "Quality First", body: "Every product we export meets strict international quality standards before it leaves Egyptian soil." },
  { title: "Transparency", body: "We operate with full visibility across our supply chain — from farm to destination." },
  { title: "Partnership", body: "We build long-term relationships based on trust, reliability, and mutual growth." },
  { title: "Sustainability", body: "We work with farms that adopt responsible practices for the long-term health of Egyptian land." },
];

export default function AboutPage() {
  return (
    <div className="bg-[#F6F3EC] min-h-screen relative">
      {/* ── HERO — layered image + geometric accent ────────────── */}
      <div className="relative bg-[#173F35] pt-[72px] overflow-hidden">
        {/* Background agricultural image, clipped to right side */}
        <div
          className="absolute inset-0 opacity-30"
          style={{
            backgroundImage: "url(https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=1600&h=900&fit=crop&auto=format)",
            backgroundSize: "cover",
            backgroundPosition: "center right",
            clipPath: "polygon(40% 0%, 100% 0%, 100% 100%, 55% 100%)",
          }}
        />
        {/* Fade from left */}
        <div className="absolute inset-0 bg-gradient-to-r from-[#173F35] via-[#173F35]/85 to-transparent" />

        {/* Architectural pyramid SVG — subtle right-side accent */}
        <div className="absolute right-0 bottom-0 opacity-[0.07] pointer-events-none hidden lg:block" aria-hidden>
          <svg width="420" height="420" viewBox="0 0 420 420" fill="none">
            <polygon points="210,30 30,390 390,390" stroke="#F6F3EC" strokeWidth="1.5" />
            <line x1="142" y1="150" x2="278" y2="150" stroke="#F6F3EC" strokeWidth="0.8" />
            <line x1="105" y1="220" x2="315" y2="220" stroke="#F6F3EC" strokeWidth="0.8" />
            <line x1="64" y1="300" x2="356" y2="300" stroke="#F6F3EC" strokeWidth="0.8" />
          </svg>
        </div>

        <div className="relative max-w-7xl mx-auto px-6 lg:px-10 pt-20 pb-24">
          <Reveal as="div" type="fade" className="flex items-center gap-3 mb-5">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Story</p>
          </Reveal>
          <Reveal as="h1" type="letter" className="font-serif text-5xl lg:text-[72px] text-[#F6F3EC] leading-[1.03] mb-6 max-w-xl">
            About<br />Green Pyramids
          </Reveal>
          <Reveal as="p" type="word" className="text-[#F6F3EC]/55 text-[15px] lg:text-lg max-w-md leading-relaxed">
            An Egyptian agricultural export company connecting the world to the finest fresh produce from Egypt's most fertile lands.
          </Reveal>
        </div>
      </div>

      {/* Introduction */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div className="relative">
            <Reveal as="div" type="scale" className="relative w-full aspect-[4/5] rounded-2xl overflow-hidden">
              <img
                src="https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=900&h=1000&fit=crop&auto=format"
                alt="Egyptian farm operations"
                className="w-full h-full object-cover"
              />
            </Reveal>
          </div>
          <div>
            <Reveal as="div" type="fade" className="flex items-center gap-3 mb-5">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Introduction</p>
            </Reveal>
            <Reveal as="h2" type="line" className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08] mb-8">
              Rooted in Egypt.<br />Reaching the World.
            </Reveal>
            <Reveal as="p" type="fade" className="text-[#173F35]/65 leading-relaxed mb-5 text-[15px]">
              Green Pyramids was founded with a clear vision: to bring the best of Egyptian agriculture to global markets with the professionalism and reliability that international buyers deserve. We specialize in sourcing, sorting, packing, and exporting premium fresh fruits and vegetables.
            </Reveal>
            <Reveal as="p" type="fade" className="text-[#173F35]/65 leading-relaxed mb-5 text-[15px]">
              We work closely with a network of trusted Egyptian farms — carefully selected based on soil quality, farming practices, and yield consistency.
            </Reveal>
            <Reveal as="p" type="fade" className="text-[#173F35]/65 leading-relaxed text-[15px]">
              Our clients include importers, distributors, wholesalers, supermarket chains, and food suppliers across Europe, the Gulf, the Middle East, and Asia.
            </Reveal>
          </div>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="py-24 bg-[#173F35]">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
          <Reveal as="div" type="fade" className="p-10 rounded-2xl border border-[#F6F3EC]/8 bg-[#F6F3EC]/4">
            <span className="font-serif text-6xl text-[#D8C7A1]/35 leading-none block mb-6">01</span>
            <h3 className="font-serif text-2xl text-[#F6F3EC] mb-4">Our Mission</h3>
            <p className="text-[#F6F3EC]/55 leading-relaxed text-[14px]">
              To connect Egyptian agricultural excellence to international markets through professional export operations, uncompromising quality standards, and reliable supply chain management.
            </p>
          </Reveal>
          <Reveal as="div" type="fade" className="p-10 rounded-2xl border border-[#F6F3EC]/8 bg-[#F6F3EC]/4">
            <span className="font-serif text-6xl text-[#D8C7A1]/35 leading-none block mb-6">02</span>
            <h3 className="font-serif text-2xl text-[#F6F3EC] mb-4">Our Vision</h3>
            <p className="text-[#F6F3EC]/55 leading-relaxed text-[14px]">
              To be recognized globally as Egypt's most trusted agricultural export partner — known for quality, transparency, and the authentic richness of Egyptian produce.
            </p>
          </Reveal>
        </div>
      </section>

      {/* Values */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <Reveal as="div" type="fade" className="flex items-center gap-3 mb-4">
          <div className="w-5 h-px bg-[#8FAE5D]" />
          <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">What Drives Us</p>
        </Reveal>
        <Reveal as="h2" type="letter" className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08] mb-14">
          Our Values
        </Reveal>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
          {VALUES.map((v, i) => (
            <Reveal as="div" type="fade" key={v.title}>
              <div className="font-serif text-[52px] text-[#D8C7A1]/75 mb-5 leading-none">0{i + 1}</div>
              <div className="w-7 h-px bg-[#8FAE5D]/45 mb-4" />
              <h3 className="font-serif text-[19px] text-[#173F35] mb-3">{v.title}</h3>
              <p className="text-[13px] text-[#173F35]/58 leading-relaxed">{v.body}</p>
            </Reveal>
          ))}
        </div>
      </section>

      {/* Egyptian Agriculture */}
      <section className="py-24 bg-[#D8C7A1]/15 border-y border-[#D8C7A1]/35">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <Reveal as="div" type="fade" className="flex items-center gap-3 mb-5">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Agricultural Heritage</p>
            </Reveal>
            <Reveal as="h2" type="line" className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08] mb-8">
              Why Egyptian<br />Agriculture?
            </Reveal>
            <Reveal as="p" type="fade" className="text-[#173F35]/65 leading-relaxed mb-7 text-[15px]">
              Egypt has one of the world's oldest and most productive agricultural traditions. The Nile Delta and Nile Valley create exceptionally fertile growing conditions that produce fresh fruits and vegetables of outstanding quality.
            </Reveal>
            <ul className="space-y-3.5">
              {[
                "Rich Nile alluvial soils with natural nutrients",
                "Warm sunny climate ideal for sweet, flavorful produce",
                "Year-round production across diverse growing zones",
                "Strategic location — close to Europe, Gulf, and Asia",
                "Competitive pricing with premium quality output",
              ].map((pt) => (
                <Reveal as="li" type="fade" key={pt} className="flex items-start gap-3">
                  <div className="w-4 h-4 rounded-full border border-[#8FAE5D]/55 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
                  </div>
                  <span className="text-[13px] text-[#173F35]/65">{pt}</span>
                </Reveal>
              ))}
            </ul>
          </div>
          <div>
            <Reveal as="div" type="scale" className="relative w-full aspect-[4/3] rounded-2xl overflow-hidden">
              <img
                src="https://images.unsplash.com/photo-1666987571450-29a997016a96?w=900&h=700&fit=crop&auto=format"
                alt="Egyptian agricultural landscape"
                className="w-full h-full object-cover"
              />
            </Reveal>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-24 bg-[#173F35] text-center">
        <Reveal as="div" type="fade" className="flex items-center justify-center gap-3 mb-4">
          <div className="w-5 h-px bg-[#8FAE5D]" />
          <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Work With Us</p>
          <div className="w-5 h-px bg-[#8FAE5D]" />
        </Reveal>
        <Reveal as="h2" type="line" className="font-serif text-3xl lg:text-5xl text-[#F6F3EC] mb-8 max-w-2xl mx-auto leading-[1.08]">
          Ready to Source Premium Egyptian Produce?
        </Reveal>
        <Reveal as="div" type="fade">
          <Link to="/contact" className="inline-block px-9 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors text-[13px]">
            Get in Touch
          </Link>
        </Reveal>
      </section>
    </div>
  );
}
