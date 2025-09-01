document.addEventListener('DOMContentLoaded', function() {
  // OTP countdown
  <?php if (is_otp_sent()): ?>
  (function(){
    const exp = <?= (int)($_SESSION['contact']['otp_exp'] ?? time()) ?> * 1000;
    const el = document.getElementById('otpTimer');
    function tick(){
      const s = Math.max(0, Math.floor((exp - Date.now())/1000));
      if (el) el.textContent = s ? `Expires in ${s}s` : 'Expired';
    }
    tick(); setInterval(tick, 1000);
  })();
  <?php endif; ?>

  function showPaymentFields(method){
    const upi = document.getElementById('upiFields');
    const card = document.getElementById('cardFields');
    if (upi) upi.style.display = (method==='upi')?'block':'none';
    if (card) card.style.display = (method==='debit_card'||method==='credit_card')?'block':'none';
  }
  document.querySelectorAll('input[name="payment_method"]').forEach(r=>{
    r.addEventListener('change', ()=>showPaymentFields(r.value));
    if (r.checked) showPaymentFields(r.value);
  });

  // Card formatting + brand
  const cardNumberInput = document.getElementById('card_number');
  const cardBrandIcon = document.getElementById('cardBrandIcon');
  const cardBrandInput = document.getElementById('card_brand');
  function detectCardBrandJS(v){
    v = v.replace(/\D/g,'');
    if (/^4/.test(v)) return 'visa';
    if (/^(5[1-5])/.test(v)||/^(222[1-9]|22[3-9]\d|2[3-6]\d{2}|27[01]\d|2720)/.test(v)) return 'mastercard';
    if (/^(60|6521|6522|508|65)/.test(v)) return 'rupay';
    return 'unknown';
  }
  function updateBrandUI(brand){
    if (cardBrandInput) cardBrandInput.value = brand;
    let html = '<small class="text-muted">Card</small>';
    if (brand==='visa') html='<img src="assets/cards/visa.svg" style="height:18px">';
    else if (brand==='mastercard') html='<img src="assets/cards/mastercard.svg" style="height:18px">';
    else if (brand==='rupay') html='<img src="assets/cards/rupay.svg" style="height:18px">';
    if (cardBrandIcon) cardBrandIcon.innerHTML = html;
  }
  function luhnCheckJS(v){
    v = v.replace(/\D/g,''); let s=0,d=false;
    for(let i=v.length-1;i>=0;i--){let n=parseInt(v[i],10); if(d){n*=2;if(n>9)n-=9} s+=n; d=!d;}
    return (s%10)===0;
  }
  if (cardNumberInput){
    cardNumberInput.addEventListener('input', function(e){
      let v = e.target.value.replace(/\s+/g,'').replace(/[^0-9]/g,'');
      if (v.length>19) v=v.slice(0,19);
      e.target.value = (v.match(/.{1,4}/g)||[]).join(' ');
      updateBrandUI(detectCardBrandJS(v));
    });
  }
  const expiryInput = document.getElementById('expiry');
  if (expiryInput){
    expiryInput.addEventListener('input', function(e){
      let v=e.target.value.replace(/\D/g,''); if (v.length>4) v=v.slice(0,4);
      if (v.length>=3) v=v.slice(0,2)+'/'+v.slice(2,4); e.target.value=v;
    });
  }
  const cvv = document.getElementById('cvv');
  if (cvv) cvv.addEventListener('input', e=> e.target.value = e.target.value.replace(/\D/g,'').slice(0,4));

  const paymentForm = document.getElementById('paymentForm');
  if (paymentForm){
    paymentForm.addEventListener('submit', function(e){
      const method = document.querySelector('input[name="payment_method"]:checked')?.value;
      if (!method){ e.preventDefault(); alert('Select a payment method.'); return; }
      if (method==='upi'){
        const v = (document.querySelector('input[name="upi_id"]')?.value || '').trim();
        if (!v){ e.preventDefault(); alert('Enter your UPI ID (or choose card).'); }
        return;
      }
      if (method==='debit_card' || method==='credit_card'){
        const num = (document.getElementById('card_number')?.value || '').replace(/\D/g,'');
        const exp = (document.getElementById('expiry')?.value || '');
        const c   = (document.getElementById('cvv')?.value || '');
        const brand = detectCardBrandJS(num);
        if (!['visa','mastercard','rupay'].includes(brand)){ e.preventDefault(); alert('Visa / Mastercard / RuPay only.'); return; }
        if (num.length<12 || num.length>19 || !luhnCheckJS(num)){ e.preventDefault(); alert('Invalid card number.'); return; }
        const parts = exp.split('/'); if (parts.length!==2) { e.preventDefault(); alert('Invalid expiry.'); return; }
        const mm = parseInt(parts[0],10), yy = 2000 + parseInt(parts[1],10);
        const now = new Date(); const expDate = new Date(yy, mm, 0, 23,59,59);
        if (isNaN(mm)||mm<1||mm>12||expDate<now){ e.preventDefault(); alert('Card expired/invalid.'); return; }
        if (c.length<3 || c.length>4){ e.preventDefault(); alert('Invalid CVV.'); return; }
        if (document.getElementById('card_brand')) document.getElementById('card_brand').value = brand;
      }
    });
  }
});