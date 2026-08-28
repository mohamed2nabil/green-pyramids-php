import React from "react";
import { useInView } from "./useInView";

interface RevealProps {
  children: React.ReactNode;
  as?: keyof JSX.IntrinsicElements;
  className?: string;
  type?: "fade" | "slide-up" | "letter" | "word";
  delay?: number;
}

export function Reveal({ children, as: Component = "div", className = "", type = "slide-up", delay = 0 }: RevealProps) {
  const { ref, inView } = useInView({ threshold: 0.1, triggerOnce: true });

  // ponytail: CSS classes handle the animations. No heavy JS physics engine.
  if (type === "letter" && typeof children === "string") {
    return (
      <Component ref={ref} className={`${className} flex flex-wrap`}>
        {children.split(" ").map((word, wordIndex) => (
          <span key={wordIndex} className="inline-block whitespace-pre">
            {word.split("").map((char, charIndex) => {
              const stagger = (wordIndex * 5 + charIndex) * 0.03 + delay;
              return (
                <span
                  key={charIndex}
                  className={`inline-block transition-all duration-700 ease-out ${
                    inView ? "opacity-100 translate-y-0 blur-none" : "opacity-0 translate-y-4 blur-sm"
                  }`}
                  style={{ transitionDelay: `${stagger}s` }}
                >
                  {char}
                </span>
              );
            })}
            <span className="inline-block w-[0.25em]">&nbsp;</span>
          </span>
        ))}
      </Component>
    );
  }

  if (type === "word" && typeof children === "string") {
    return (
      <Component ref={ref} className={`${className} flex flex-wrap gap-[0.25em]`}>
        {children.split(" ").map((word, index) => (
          <span
            key={index}
            className={`inline-block transition-all duration-700 ease-out ${
              inView ? "opacity-100 translate-y-0 blur-none" : "opacity-0 translate-y-4 blur-sm"
            }`}
            style={{ transitionDelay: `${index * 0.1 + delay}s` }}
          >
            {word}
          </span>
        ))}
      </Component>
    );
  }

  const baseClasses = "transition-all duration-1000 ease-out";
  const stateClasses = {
    "fade": inView ? "opacity-100" : "opacity-0",
    "slide-up": inView ? "opacity-100 translate-y-0 blur-none" : "opacity-0 translate-y-8 blur-sm",
    "image-mask": inView ? "clip-path-unveiled scale-100 opacity-100" : "clip-path-veiled scale-105 opacity-0",
    "letter": "", // handled above
    "word": "", // handled above
  };

  return (
    <Component
      // @ts-ignore
      ref={ref}
      className={`${className} ${baseClasses} ${stateClasses[type]}`}
      style={{ transitionDelay: `${delay}s` }}
    >
      {children}
    </Component>
  );
}
