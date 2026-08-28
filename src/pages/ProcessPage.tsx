import { Link } from "react-router-dom";

const STEPS = [
  {
    n: "01",
    title: "Farm Sourcing",
    body: "We begin with rigorous farm selection. Our team evaluates farms across Egypt's key agricultural regions — the Nile Delta, Upper Egypt, and the Mediterranean coast — assessing soil quality, farming practices, and track record for consistency.",
    img: "https://images.unsplash.com/photo-1649192537902-7b06265dd08f?w=900&h=700&fit=crop&auto=format",
  },
  {
    n: "02",
    title: "Harvesting",
    body: "Crops are harvested at the optimal stage of maturity — timed precisely for export windows. Our field teams work directly with farm workers to ensure correct picking techniques that protect product quality and extend shelf life.",
    img: "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=900&h=700&fit=crop&auto=format",
  },
  {
    n: "03",
    title: "Quality Control",
    body: "Every batch undergoes thorough inspection at our packing facilities. Products are assessed for size uniformity, color, firmness, and absence of defects. Only produce that meets our export-grade criteria moves forward.",
    img: "https://images.unsplash.com/photo-1652211955967-99c892925469?w=900&h=700&fit=crop&auto=format",
  },
  {
    n: "04",
    title: "Packing",
    body: "Products are sorted by grade and size, then carefully packed in export-standard cartons and crates. Packaging is selected to meet the specific requirements of each destination market, including labeling and weight standards.",
    img: "https://images.unsplash.com/photo-1720807740685-d9cdcb0836a7?w=900&h=700&fit=crop&auto=format",
  },
  {
    n: "05",
    title: "Cold Chain Management",
    body: "From the moment produce is packed, it enters our controlled cold chain. Refrigerated storage and transport maintain optimal temperatures throughout — from our packing houses to Egyptian ports and onto refrigerated containers.",
    img: "https://images.unsplash.com/photo-1713859326033-f75e04439c3e?w=900&h=700&fit=crop&auto=format",
  },
  {
    n: "06",
    title: "Global Shipment",
    body: "We coordinate export documentation, customs clearance, and shipping logistics to ensure smooth delivery to international destinations. Our experienced export team manages the complete process so clients receive products on time and in excellent condition.",
    img: "https://images.unsplash.com/photo-1759272840538-ae4b07214c71?w=900&h=700&fit=crop&auto=format",
  },
];

export default function ProcessPage() {
  return (
    <div className="bg-[#F6F3EC] min-h-screen">

      {/* ── HERO — journey flow with step indicators ───────────── */}
      <div className="bg-[#173F35] pt-[72px] relative overflow-hidden">

        {/* Animated journey line — visual movement concept */}
        <div className="absolute inset-0 opacity-[0.05] pointer-events-none" aria-hidden>
          <svg width="100%" height="100%" viewBox="0 0 1200 400" preserveAspectRatio="xMinYMid slice" fill="none">
            {/* Flowing horizontal path */}
            <path d="M-50,200 C100,100 200,300 400,200 C600,100 700,300 900,200 C1100,100 1150,300 1300,200" stroke="#F6F3EC" strokeWidth="1" />
            <path d="M-50,250 C150,150 250,350 450,250 C650,150 750,350 950,250 C1150,150 1200,280 1350,250" stroke="#8FAE5D" strokeWidth="0.8" />
          </svg>
        </div>

        {/* Background image — subtle right side */}
        <div
          className="absolute right-0 top-0 bottom-0 w-1/2 opacity-20"
          style={{
            backgroundImage: "url(https://images.unsplash.com/photo-1759272840538-ae4b07214c71?w=900&h=600&fit=crop&auto=format)",
            backgroundSize: "cover",
            backgroundPosition: "left center",
            clipPath: "polygon(15% 0%, 100% 0%, 100% 100%, 0% 100%)",
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-r from-[#173F35] via-[#173F35]/90 to-[#173F35]/60" />

        {/* Main copy */}
        <div className="relative max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-10">
          <div className="flex items-center gap-3 mb-5">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">How We Work</p>
          </div>
          <h1 className="font-serif text-5xl lg:text-[68px] text-[#F6F3EC] leading-[1.03] mb-5 max-w-lg">
            Our Export Process
          </h1>
          <p className="text-[#F6F3EC]/50 text-[15px] max-w-md leading-relaxed mb-10">
            A disciplined, transparent supply chain — from Egyptian farms to your destination market.
          </p>
        </div>

        {/* Step flow indicator bar */}
        <div className="relative border-t border-[#F6F3EC]/10">
          <div className="max-w-7xl mx-auto px-6 lg:px-10">
            <div className="flex items-stretch overflow-x-auto">
              {STEPS.map((step, i) => (
                <div key={step.n} className="flex-shrink-0 flex items-center">
                  <div className="py-4 px-4 lg:px-6 flex items-center gap-3">
                    <span className="font-serif text-sm text-[#D8C7A1]/60">{step.n}</span>
                    <span className="text-[11px] text-[#F6F3EC]/50 tracking-wide whitespace-nowrap">{step.title}</span>
                  </div>
                  {i < STEPS.length - 1 && (
                    <div className="text-[#F6F3EC]/20 text-xs px-1">→</div>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Steps — alternating layout */}
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-20 space-y-24 lg:space-y-36">
        {STEPS.map((step, i) => (
          <div
            key={step.n}
            className={`grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center ${
              i % 2 === 1 ? "lg:grid-flow-col-dense" : ""
            }`}
          >
            <div className={i % 2 === 1 ? "lg:col-start-2" : ""}>
              <img
                src={step.img}
                alt={step.title}
                className="w-full aspect-[4/3] object-cover rounded-2xl"
              />
            </div>
            <div className={i % 2 === 1 ? "lg:col-start-1 lg:row-start-1" : ""}>
              <span className="font-serif text-[72px] text-[#D8C7A1]/70 leading-none block mb-5">{step.n}</span>
              <div className="w-8 h-px bg-[#8FAE5D]/50 mb-5" />
              <h2 className="font-serif text-3xl lg:text-[38px] text-[#173F35] mb-5">{step.title}</h2>
              <p className="text-[#173F35]/62 leading-relaxed text-[15px]">{step.body}</p>
            </div>
          </div>
        ))}
      </div>

      {/* CTA */}
      <section className="bg-[#173F35] py-24 text-center">
        <div className="flex items-center justify-center gap-3 mb-4">
          <div className="w-5 h-px bg-[#8FAE5D]" />
          <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Partner With Us</p>
          <div className="w-5 h-px bg-[#8FAE5D]" />
        </div>
        <h2 className="font-serif text-3xl lg:text-5xl text-[#F6F3EC] mb-8 max-w-xl mx-auto leading-[1.08]">
          Trust the Process. Trust Green Pyramids.
        </h2>
        <Link
          to="/contact"
          className="inline-block px-9 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors text-[13px]"
        >
          Request a Quote
        </Link>
      </section>
    </div>
  );
}
