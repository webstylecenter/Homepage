import { FluentRevealEffect } from 'fluent-reveal-effect';

FluentRevealEffect.applyEffect(".fluent", {
  clickEffect: true,
  lightColor: "rgba(255, 255, 255, 1)",
  gradientSize: 150,
});

FluentRevealEffect.applyEffect(".fluent-dark", {
  clickEffect: true,
  lightColor: "rgba(0, 0, 0, 0.5)",
  gradientSize: 150,
});

FluentRevealEffect.applyEffect(".fluent-light", {
  clickEffect: true,
  lightColor: "rgba(255, 255, 255, 0.3)",
  gradientSize: 150,
});

FluentRevealEffect.applyEffect(".fluent-blue", {
  clickEffect: true,
  lightColor: "rgba(6, 71, 187, 0.7)",
  gradientSize: 100,
});

FluentRevealEffect.applyEffect(".checklist li:nth-child(2n)", {
  clickEffect: true,
  lightColor: "rgba(255, 255, 255, 0.7)",
  gradientSize: 200,
});

FluentRevealEffect.applyEffect(".profileMenu li", {
  clickEffect: true,
  lightColor: "rgba(0, 0, 0, 0.2)",
  gradientSize: 200,
});
