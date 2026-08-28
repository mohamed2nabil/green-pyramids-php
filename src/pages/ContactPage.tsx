import { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";

const PRODUCTS_LIST = [
  "Egyptian Mango", "Pomegranate", "Strawberry", "Guava",
  "Navel Orange", "Lemon", "Mandarin",
  "Potato", "Onion", "Sweet Pepper", "Garlic",
  "Other / Multiple Products",
];

export default function ContactPage() {
  const location = useLocation();
  const [form, setForm] = useState({
    name: "", company: "", country: "", email: "", phone: "",
    product: "", quantity: "", packaging: "", message: "",
  });
  const [submitted, setSubmitted] = useState(false);

  useEffect(() => {
    const params = new URLSearchParams(location.search);
    const product = params.get("product");
    if (product) setForm((f) => ({ ...f, product }));
  }, [location.search]);

  const handle = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
  };

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitted(true);
  };

  return (
    <div className="bg-[#F6F3EC] min-h-screen">

      {/* ── HERO — welcoming, warm, premium feel ───────────────── */}
      <div className="bg-[#173F35] pt-[72px] relative overflow-hidden">

        {/* Warm agricultural image — left side */}
        <div
          className="absolute left-0 top-0 bottom-0 w-2/5 opacity-25 hidden lg:block"
          style={{
            backgroundImage: "url(https://images.unsplash.com/photo-1649192537902-7b06265dd08f?w=900&h=800&fit=crop&auto=format)",
            backgroundSize: "cover",
            backgroundPosition: "right center",
            clipPath: "polygon(0% 0%, 75% 0%, 100% 100%, 0% 100%)",
          }}
        />
        {/* Right-to-left gradient over image */}
        <div className="absolute inset-0 bg-gradient-to-r from-[#173F35]/55 via-[#173F35]/75 to-[#173F35]" />

        {/* Contact quick access — top right strip */}
        <div className="absolute top-[72px] right-0 hidden lg:flex items-stretch border-l border-[#F6F3EC]/8">
          {[
            { label: "Email", value: "info@greenpyramids.eg", icon: "✉" },
            { label: "WhatsApp", value: "+20 (10) 000-0000", icon: "◑" },
          ].map((c) => (
            <div key={c.label} className="px-6 py-4 border-b border-[#F6F3EC]/8 last:border-b-0">
              <p className="text-[9px] tracking-[0.2em] uppercase text-[#F6F3EC]/35 mb-1">{c.label}</p>
              <p className="text-[12px] text-[#F6F3EC]/65">{c.value}</p>
            </div>
          ))}
        </div>

        <div className="relative max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-20">
          <div className="flex items-center gap-3 mb-5">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Get in Touch</p>
          </div>
          <h1 className="font-serif text-5xl lg:text-[68px] text-[#F6F3EC] leading-[1.03] mb-5 max-w-xl">
            {"Let's Start a"}
            <br /><em>Conversation.</em>
          </h1>
          <p className="text-[#F6F3EC]/50 text-[15px] max-w-md leading-relaxed">
            Tell us what products you are looking for and our export team will get back to you within one business day.
          </p>
        </div>
      </div>

      {/* Form + Info */}
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-20 lg:py-28">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-16">

          {/* Contact info sidebar */}
          <div>
            <h3 className="font-serif text-2xl text-[#173F35] mb-8">Contact Information</h3>
            <div className="space-y-0">
              {[
                { icon: "✉", label: "Email", value: "info@greenpyramids.eg" },
                { icon: "☎", label: "Phone", value: "+20 (2) 000-0000" },
                { icon: "◑", label: "WhatsApp", value: "+20 (10) 000-0000" },
                { icon: "◎", label: "Location", value: "Cairo, Egypt" },
              ].map((c) => (
                <div key={c.label} className="flex items-start gap-4 py-5 border-b border-[#D8C7A1]/35">
                  <div className="w-9 h-9 rounded-full bg-[#173F35]/6 flex items-center justify-center flex-shrink-0 text-[#8FAE5D] text-sm">
                    {c.icon}
                  </div>
                  <div>
                    <p className="text-[10px] text-[#173F35]/38 tracking-[0.18em] uppercase mb-0.5">{c.label}</p>
                    <p className="text-[13px] text-[#173F35]">{c.value}</p>
                  </div>
                </div>
              ))}
            </div>

            <div className="mt-8 p-6 bg-[#173F35] rounded-xl">
              <div className="flex items-center gap-2 mb-3">
                <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
                <p className="text-[10px] tracking-[0.2em] uppercase text-[#8FAE5D]">Quick WhatsApp</p>
              </div>
              <p className="text-[13px] text-[#F6F3EC]/60 mb-4 leading-relaxed">
                For fast inquiries, reach our export team directly on WhatsApp.
              </p>
              <a
                href="https://wa.me/200000000000"
                className="flex items-center justify-center gap-2 py-3 bg-[#8FAE5D] text-[#173F35] text-[12px] font-medium rounded-full hover:bg-[#D8C7A1] transition-colors"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#173F35">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Open WhatsApp
              </a>
            </div>
          </div>

          {/* Inquiry form */}
          <div className="lg:col-span-2">
            {submitted ? (
              <div className="flex flex-col items-center justify-center h-full min-h-96 text-center">
                <div className="w-14 h-14 rounded-full bg-[#8FAE5D]/15 flex items-center justify-center mb-6">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12l5 5L20 7" stroke="#8FAE5D" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>
                </div>
                <h3 className="font-serif text-3xl text-[#173F35] mb-4">Inquiry Sent</h3>
                <p className="text-[#173F35]/55 max-w-sm leading-relaxed text-[14px]">
                  Thank you for reaching out. Our export team will review your inquiry and respond within one business day.
                </p>
              </div>
            ) : (
              <form onSubmit={submit} className="space-y-5">
                <div className="mb-8">
                  <h3 className="font-serif text-2xl text-[#173F35] mb-1">Export Inquiry Form</h3>
                  <p className="text-[12px] text-[#173F35]/40 tracking-wide">Fields marked * are required.</p>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  {[
                    { name: "name", label: "Full Name", placeholder: "Your full name", required: true, type: "text" },
                    { name: "company", label: "Company Name", placeholder: "Your company", required: true, type: "text" },
                    { name: "country", label: "Country", placeholder: "Your country", required: true, type: "text" },
                    { name: "email", label: "Business Email", placeholder: "email@company.com", required: true, type: "email" },
                  ].map((f) => (
                    <div key={f.name}>
                      <label className="block text-[10px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-2">
                        {f.label}{f.required ? " *" : ""}
                      </label>
                      <input
                        name={f.name}
                        type={f.type}
                        required={f.required}
                        value={(form as Record<string, string>)[f.name]}
                        onChange={handle}
                        placeholder={f.placeholder}
                        className="w-full px-4 py-3 bg-white border border-[#D8C7A1]/50 rounded-xl text-[13px] text-[#173F35] placeholder:text-[#173F35]/28 focus:outline-none focus:border-[#173F35]/50 transition-colors"
                      />
                    </div>
                  ))}
                </div>

                <div>
                  <label className="block text-[10px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-2">Phone / WhatsApp</label>
                  <input
                    name="phone" value={form.phone} onChange={handle} type="tel"
                    placeholder="+1 (000) 000-0000"
                    className="w-full px-4 py-3 bg-white border border-[#D8C7A1]/50 rounded-xl text-[13px] text-[#173F35] placeholder:text-[#173F35]/28 focus:outline-none focus:border-[#173F35]/50 transition-colors"
                  />
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-[10px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-2">Product Interested In *</label>
                    <select
                      name="product" required value={form.product} onChange={handle}
                      className="w-full px-4 py-3 bg-white border border-[#D8C7A1]/50 rounded-xl text-[13px] text-[#173F35] focus:outline-none focus:border-[#173F35]/50 transition-colors appearance-none"
                    >
                      <option value="">Select a product</option>
                      {PRODUCTS_LIST.map((p) => (
                        <option key={p} value={p}>{p}</option>
                      ))}
                    </select>
                  </div>
                  <div>
                    <label className="block text-[10px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-2">Estimated Quantity</label>
                    <input
                      name="quantity" value={form.quantity} onChange={handle}
                      placeholder="e.g. 5 tons / week"
                      className="w-full px-4 py-3 bg-white border border-[#D8C7A1]/50 rounded-xl text-[13px] text-[#173F35] placeholder:text-[#173F35]/28 focus:outline-none focus:border-[#173F35]/50 transition-colors"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-[10px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-2">Preferred Packaging</label>
                  <input
                    name="packaging" value={form.packaging} onChange={handle}
                    placeholder="e.g. 4 kg carton, bulk, custom"
                    className="w-full px-4 py-3 bg-white border border-[#D8C7A1]/50 rounded-xl text-[13px] text-[#173F35] placeholder:text-[#173F35]/28 focus:outline-none focus:border-[#173F35]/50 transition-colors"
                  />
                </div>

                <div>
                  <label className="block text-[10px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-2">Message</label>
                  <textarea
                    name="message" value={form.message} onChange={handle} rows={5}
                    placeholder="Tell us more about your requirements..."
                    className="w-full px-4 py-3 bg-white border border-[#D8C7A1]/50 rounded-xl text-[13px] text-[#173F35] placeholder:text-[#173F35]/28 focus:outline-none focus:border-[#173F35]/50 transition-colors resize-none"
                  />
                </div>

                <button
                  type="submit"
                  className="w-full py-4 bg-[#173F35] text-[#F6F3EC] font-medium tracking-wide rounded-full hover:bg-[#8FAE5D] hover:text-[#173F35] transition-colors duration-200 text-[13px]"
                >
                  Send Export Inquiry
                </button>
              </form>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
