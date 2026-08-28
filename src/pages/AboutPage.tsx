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
    <div className="bg-[#050c0a] min-h-screen relative text-[#F6F3EC]">
      {/* ── HERO — Cinematic dark gradient ────────────── */}
      <div className="relative pt-[120px] pb-32 overflow-hidden flex items-center min-h-[75vh]">
        {/* Background dark farm image */}
        <div
          className="absolute inset-0 opacity-[0.15]"
          style={{
            backgroundImage: "url(https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=1600&h=900&fit=crop&auto=format)",
            backgroundSize: "cover",
            backgroundPosition: "center",
          }}
        />
        {/* Cinematic dark gradients serving 3D aesthetic */}
        <div className="absolute inset-0 bg-gradient-to-t from-[#050c0a] via-transparent to-[#050c0a]" />
        <div className="absolute inset-0 bg-gradient-to-r from-[#050c0a] via-transparent to-[#050c0a]" />
        
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[80vw] max-w-[800px] max-h-[800px] bg-[#173F35] rounded-full blur-[120px] opacity-20 pointer-events-none" />

        <div className="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full text-center">
          <Reveal as="div" type="fade" className="flex items-center justify-center gap-3 mb-6">
            <div className="w-8 h-px bg-[#8FAE5D]" />
            <p className="text-[12px] tracking-[0.3em] uppercase text-[#8FAE5D]">Our Legacy</p>
            <div className="w-8 h-px bg-[#8FAE5D]" />
          </Reveal>
          <Reveal as="h1" type="letter" className="font-serif text-6xl lg:text-[100px] leading-[0.9] mb-8">
            Rooted In <br /> <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#D8C7A1] to-[#8FAE5D]">Deep Soil</span>
          </Reveal>
          <Reveal as="p" type="word" className="text-[#F6F3EC]/50 text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed">
            Connecting the world to the finest fresh produce from Egypt's most fertile lands, engineered for global export.
          </Reveal>
        </div>
      </div>

      {/* Introduction — 3D style floating image */}
      <section className="py-24 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
          <div className="order-2 lg:order-1">
            <Reveal as="div" type="fade" className="flex items-center gap-3 mb-6">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Introduction</p>
            </Reveal>
            <Reveal as="h2" type="line" className="font-serif text-4xl lg:text-5xl leading-[1.1] mb-8">
              A Vision Built <br /> On Reliability.
            </Reveal>
            <Reveal as="p" type="fade" className="text-[#F6F3EC]/60 leading-relaxed mb-6 text-[16px]">
              Green Pyramids was founded with a clear vision: to bring the best of Egyptian agriculture to global markets with the professionalism and reliability that international buyers deserve. We specialize in sourcing, sorting, packing, and exporting premium fresh fruits and vegetables.
            </Reveal>
            <Reveal as="p" type="fade" className="text-[#F6F3EC]/60 leading-relaxed text-[16px]">
              We work closely with a network of trusted Egyptian farms — carefully selected based on soil quality, farming practices, and yield consistency.
            </Reveal>
          </div>
          <div className="relative order-1 lg:order-2">
            <Reveal as="div" type="scale" className="relative w-full aspect-[4/5] rounded-[2rem] overflow-hidden shadow-[0_0_80px_rgba(23,63,53,0.3)] border border-[#8FAE5D]/10">
              <img
                src="https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=900&h=1000&fit=crop&auto=format"
                alt="Egyptian farm operations"
                className="w-full h-full object-cover opacity-80"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-[#050c0a] to-transparent" />
            </Reveal>
          </div>
        </div>
      </section>

      {/* Values Grid - Dark Glassmorphism */}
      <section className="py-32 max-w-7xl mx-auto px-6 lg:px-10 relative">
        <div className="absolute top-1/2 left-0 w-[500px] h-[500px] bg-[#8FAE5D] rounded-full blur-[150px] opacity-[0.05] pointer-events-none -translate-y-1/2" />
        
        <Reveal as="div" type="fade" className="flex items-center gap-3 mb-4">
          <div className="w-5 h-px bg-[#8FAE5D]" />
          <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Values</p>
        </Reveal>
        <Reveal as="h2" type="letter" className="font-serif text-4xl lg:text-6xl leading-[1.08] mb-20">
          The Foundation
        </Reveal>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
          {VALUES.map((v, i) => (
            <Reveal as="div" type="fade" key={v.title} className="p-10 rounded-3xl bg-gradient-to-b from-[#173F35]/20 to-[#050c0a] border border-[#8FAE5D]/10 backdrop-blur-sm hover:border-[#8FAE5D]/30 transition-colors duration-500 group">
              <div className="font-serif text-6xl text-[#D8C7A1]/20 mb-8 leading-none group-hover:text-[#D8C7A1]/40 transition-colors">0{i + 1}</div>
              <h3 className="font-serif text-2xl mb-4">{v.title}</h3>
              <p className="text-[15px] text-[#F6F3EC]/50 leading-relaxed">{v.body}</p>
            </Reveal>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="py-32 text-center relative overflow-hidden">
        <div className="absolute inset-0 bg-[#173F35]/20" />
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-[1000px] h-px bg-gradient-to-r from-transparent via-[#8FAE5D]/50 to-transparent" />
        
        <div className="relative z-10 max-w-3xl mx-auto px-6">
          <Reveal as="div" type="fade" className="flex items-center justify-center gap-3 mb-8">
            <div className="w-8 h-px bg-[#8FAE5D]" />
            <p className="text-[12px] tracking-[0.3em] uppercase text-[#8FAE5D]">Work With Us</p>
            <div className="w-8 h-px bg-[#8FAE5D]" />
          </Reveal>
          <Reveal as="h2" type="line" className="font-serif text-5xl lg:text-7xl mb-12 leading-[1.08]">
            Source Premium <br /> Egyptian Produce.
          </Reveal>
          <Reveal as="div" type="fade">
            <Link to="/contact" className="inline-flex px-10 py-5 bg-[#8FAE5D] text-[#050c0a] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] hover:scale-105 transition-all duration-300 text-[14px]">
              Partner With Us
            </Link>
          </Reveal>
        </div>
      </section>
    </div>
  );
}
