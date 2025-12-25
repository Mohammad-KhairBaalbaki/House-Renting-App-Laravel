
window.addEventListener("load", () => {
  document.body.classList.add("loaded");
});

document.addEventListener("click", (e) => {
 
  const passBtn = e.target.closest("[data-toggle-password]");
  if (passBtn) {
    const selector = passBtn.getAttribute("data-toggle-password");
    const input = document.querySelector(selector);
    if (!input) return;

    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";

    const icon = passBtn.querySelector("i");
    if (icon) {
      icon.className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
    }
    return; 
  }

  const fitBtn = e.target.closest("[data-fit-toggle]");
  if (fitBtn) {
    if (fitBtn.closest("a")) {
      e.preventDefault();
      e.stopPropagation();
    }

    const container = fitBtn.closest(".house-card-cover, .house-hero-cover");
    if (!container) return;

    const img = container.querySelector("img.cover-img");
    if (!img) return;

    const isCover = img.classList.contains("is-cover");

    img.classList.toggle("is-cover", !isCover);
    img.classList.toggle("is-contain", isCover);

    fitBtn.textContent = isCover ? "Fill" : "Fit";
    return;
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const govSelect = document.getElementById("govSelect");
  const citySelect = document.getElementById("citySelect");
  if (!govSelect || !citySelect || !window.__GOVS__) return;

  function fillCities(govId) {
    citySelect.innerHTML = `<option value="">Select City</option>`;
    const gov = window.__GOVS__.find(g => String(g.id) === String(govId));
    if (!gov) return;

    gov.cities.forEach(c => {
      const opt = document.createElement("option");
      opt.value = c.id;
      opt.textContent = c.name || `City #${c.id}`;
      citySelect.appendChild(opt);
    });

    if (window.__CURRENT_CITY__) citySelect.value = window.__CURRENT_CITY__;
  }

  fillCities(govSelect.value);

  govSelect.addEventListener("change", function () {
    window.__CURRENT_CITY__ = null;
    fillCities(this.value);
  });
});
