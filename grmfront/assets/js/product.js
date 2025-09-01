
document.addEventListener('DOMContentLoaded', () => {
  /* ---------------------- Helpers ---------------------- */
  function decodeDataVar(raw) {
    if (!raw) return null;
    try {
      const parser = new DOMParser();
      const doc = parser.parseFromString(raw, "text/html");
      const decoded = doc.documentElement.textContent || raw;
      return JSON.parse(decoded);
    } catch (e) {
      try {
        return JSON.parse(raw);
      } catch (e2) {
        console.error("decodeDataVar parse error", e, e2, raw);
        return null;
      }
    }
  }

  function updateQtyButtonsState() {
    const minus = document.querySelector('.quantity__minus');
    const plus = document.querySelector('.quantity__plus');
    const qtyInput = document.getElementById('qtyInput');
    if (!qtyInput) return;

    const val = parseInt(qtyInput.value) || 0;
    const min = parseInt(qtyInput.getAttribute('min')) || 1;
    const max = parseInt(qtyInput.getAttribute('max')) || 0;

    if (minus) {
      minus.disabled = (val <= min) || (max <= 0);
      minus.setAttribute('aria-disabled', minus.disabled.toString());
    }
    if (plus) {
      plus.disabled = (val >= max) || (max <= 0);
      plus.setAttribute('aria-disabled', plus.disabled.toString());
    }
  }

  function wireQtyControls() {
    const minus = document.querySelector('.quantity__minus');
    const plus = document.querySelector('.quantity__plus');
    const qtyInput = document.getElementById('qtyInput');
    if (!minus || !plus || !qtyInput) return;

    minus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value) || 1;
      const min = parseInt(qtyInput.getAttribute('min')) || 1;
      if (val > min) qtyInput.value = val - 1;
      qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
      updateQtyButtonsState();
    });

    plus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value) || 1;
      const max = parseInt(qtyInput.getAttribute('max')) || 1;
      if (val < max) qtyInput.value = val + 1;
      qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
      updateQtyButtonsState();
    });

    qtyInput.addEventListener('input', () => {
      let val = parseInt(qtyInput.value);
      const min = parseInt(qtyInput.getAttribute('min')) || 1;
      const max = parseInt(qtyInput.getAttribute('max')) || 1;

      if (isNaN(val)) val = min;
      if (val < min) val = min;
      if (val > max) val = max;

      qtyInput.value = val;
      updateQtyButtonsState();
    });

    qtyInput.addEventListener('wheel', e => e.preventDefault(), { passive: false });

    qtyInput.addEventListener('keydown', e => {
      const allowed = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Delete'];
      if (allowed.includes(e.key) || /^[0-9]$/.test(e.key)) return;
      e.preventDefault();
    });

    updateQtyButtonsState();
  }

  /* ---------------------- Init ---------------------- */
  const variations = decodeDataVar(document.getElementById('variationData')?.textContent);
  if (!variations) return;

  const sizeBtns = document.querySelectorAll('.size-button');
  const colorBtns = document.querySelectorAll('.color-button');
  const qtyInput = document.getElementById('qtyInput');
  const addCartBtn = document.getElementById('addCartBtn');
  const buyBtn = document.getElementById('buyBtn');

  let selectedSize = null;
  let selectedColor = null;

  // Disable out-of-stock options
  function disableOutOfStockOptions() {
    sizeBtns.forEach(btn => {
      const s = btn.getAttribute('data-size');
      const hasStock = variations.some(v => v.size === s && v.stock > 0);
      if (!hasStock) {
        btn.disabled = true;
        btn.classList.add('out-of-stock');
      }
    });

    colorBtns.forEach(btn => {
      const c = btn.getAttribute('data-color');
      const hasStock = variations.some(v => v.color === c && v.stock > 0);
      if (!hasStock) {
        btn.disabled = true;
        btn.classList.add('out-of-stock');
      }
    });
  }

  function applyVariation(v) {
    if (!v) return;

    document.getElementById('skuSpan').textContent = v.sku;
    document.getElementById('stockSpan').textContent = v.stock;

    document.getElementById('selectedVarId').value = v.variation_id;
    document.getElementById('selectedSku').value = v.sku;
    document.getElementById('selectedColor').value = v.color;
    document.getElementById('selectedSize').value = v.size;

    const priceHtml = (v.offer_price > 0 && v.offer_price < v.price)
      ? `<span class="price">₹${v.offer_price}</span>
         <span class="old-price">₹${v.price}</span>`
      : `<span class="price">₹${v.price}</span>`;
    document.getElementById('priceDiv').innerHTML = priceHtml;

    const maxVal = parseInt(v.stock) || 0;
    if (qtyInput) {
      if (maxVal <= 0) {
        qtyInput.value = 0;
        qtyInput.disabled = true;
        qtyInput.min = 0;
        qtyInput.max = 0;
        addCartBtn?.setAttribute('disabled', 'true');
        buyBtn?.setAttribute('disabled', 'true');
      } else {
        qtyInput.disabled = false;
        qtyInput.min = 1;
        qtyInput.max = maxVal;
        if (parseInt(qtyInput.value) < 1) qtyInput.value = 1;
        addCartBtn?.removeAttribute('disabled');
        buyBtn?.removeAttribute('disabled');
      }
    }
    updateQtyButtonsState();
  }

  function findVariation() {
    return variations.find(v =>
      v.size === selectedSize && v.color === selectedColor
    );
  }

  sizeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.disabled) return;
      sizeBtns.forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      selectedSize = btn.getAttribute('data-size');
      applyVariation(findVariation());
    });
  });

  colorBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.disabled) return;
      colorBtns.forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      selectedColor = btn.getAttribute('data-color');
      applyVariation(findVariation());
    });
  });

  // Init
  disableOutOfStockOptions();
  wireQtyControls();

  // Auto-select first available variation
  const firstAvailable = variations.find(v => v.stock > 0);
  if (firstAvailable) {
    selectedSize = firstAvailable.size;
    selectedColor = firstAvailable.color;
    document.querySelector(`.size-button[data-size="${selectedSize}"]`)?.classList.add('selected');
    document.querySelector(`.color-button[data-color="${selectedColor}"]`)?.classList.add('selected');
    applyVariation(firstAvailable);
  }
});

