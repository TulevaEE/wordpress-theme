/**
 * II samba 2-4-6 maandumislehe kalkulaatorid (maksuvõit + vara kasv).
 * Kaks kalkulaatorit jagavad üht olekut: brutopalk + määr juhivad mõlemat,
 * vanus + tootlus juhivad vara-graafikut. Arvutusmootor on isoleeritud IIFE-s.
 * Eeldused (2026): tulumaks 22%, +4% riik, palgakasv 3%/a, pensioniiga 65.
 * Prototüübi allikas: work/kristel/kalkulaator/landing-v5.html
 */
(function () {
  var root = document.querySelector('.second-pillar-246-page');
  if (!root || !document.getElementById('segRate')) {
    return;
  }

  /* ===== Arvutusmootor (isoleeritud) ===== */
  var TulevaCalc = (function () {
    var TAX = 0.22, SALARY_GROWTH = 0.03;

    function fvGrowing(pmt0, annual, years, salaryGrowth) {
      var n = Math.round(years * 12);
      if (n <= 0) return 0;
      var i = Math.pow(1 + annual, 1 / 12) - 1;
      var gm = Math.pow(1 + salaryGrowth, 1 / 12) - 1;
      if (Math.abs(i - gm) < 1e-12) return pmt0 * n * Math.pow(1 + i, n - 1);
      return pmt0 * (Math.pow(1 + i, n) - Math.pow(1 + gm, n)) / (i - gm);
    }

    function sumGrowing(pmt0, years, salaryGrowth) {
      var n = Math.round(years * 12);
      if (n <= 0) return 0;
      var gm = Math.pow(1 + salaryGrowth, 1 / 12) - 1;
      if (gm === 0) return pmt0 * n;
      return pmt0 * (Math.pow(1 + gm, n) - 1) / gm;
    }

    return { fvGrowing: fvGrowing, sumGrowing: sumGrowing, TAX: TAX, SALARY_GROWTH: SALARY_GROWTH };
  })();

  var TAX = 0.22, STATE = 0.04, SG = 0.03;
  var $ = function (s) { return root.querySelector(s); };
  function eur(x, dec) {
    dec = dec || 0;
    var r = Math.round(x * (dec ? 100 : 1)) / (dec ? 100 : 1);
    return r.toLocaleString('et-EE', { minimumFractionDigits: dec, maximumFractionDigits: dec }) + ' €';
  }

  var state = { gross: 3000, rate: 0.06, age: 35, ret: 0 };

  function buildChart(gross, annual, age, selRate) {
    var years = Math.max(1, 65 - age), rates = [0.02, 0.04, 0.06], colors = ['#5C738C', '#00AEEA', '#002F63'];
    var W = 360, H = 230, padL = 50, padR = 16, padT = 12, padB = 26, pw = W - padL - padR, ph = H - padT - padB;
    var trajs = rates.map(function (r) {
      var a = [];
      for (var t = 0; t <= years; t++) a.push(TulevaCalc.fvGrowing((r + STATE) * gross, annual, t, SG));
      return a;
    });
    var maxY = Math.max(trajs[2][years], 1);
    var X = function (t) { return padL + (t / years) * pw; };
    var Y = function (v) { return padT + ph - (v / maxY) * ph; };
    var kEur = function (v) { return Math.round(v / 1000).toLocaleString('et-EE') + ' k€'; };
    var s = '<svg viewBox="0 0 ' + W + ' ' + H + '" role="img" aria-label="Vara kasv vanusega 2%, 4% ja 6% määraga — näitlik prognoos">';
    [0, 0.5, 1].forEach(function (f) {
      var yy = Y(maxY * f);
      s += '<line class="grid" x1="' + padL + '" y1="' + yy.toFixed(1) + '" x2="' + (W - padR) + '" y2="' + yy.toFixed(1) + '"/>'
        + '<text class="cy" x="' + (padL - 7) + '" y="' + (yy + 3).toFixed(1) + '" text-anchor="end">' + (f === 0 ? '0' : kEur(maxY * f)) + '</text>';
    });
    [0, 0.5, 1].forEach(function (f) {
      var t = Math.round(years * f), xx = X(t);
      s += '<text class="cx" x="' + xx.toFixed(1) + '" y="' + (H - 9) + '" text-anchor="middle">' + (age + t) + ' a</text>';
    });
    rates.forEach(function (r, i) {
      var pts = trajs[i].map(function (v, t) { return X(t).toFixed(1) + ',' + Y(v).toFixed(1); }).join(' ');
      var sel = Math.abs(r - selRate) < 1e-9;
      s += '<polyline points="' + pts + '" fill="none" stroke="' + colors[i] + '" stroke-width="' + (sel ? 3.6 : 2) + '" opacity="' + (sel ? 1 : 0.5) + '" stroke-linejoin="round" stroke-linecap="round"/>';
    });
    rates.forEach(function (r, i) {
      var v = trajs[i][years], sel = Math.abs(r - selRate) < 1e-9;
      s += '<text class="cend" x="' + (X(years) - 3).toFixed(1) + '" y="' + (Y(v) - 5).toFixed(1) + '" text-anchor="end" fill="' + colors[i] + '" opacity="' + (sel ? 1 : 0.65) + '">' + Math.round(r * 100) + '%</text>';
    });
    return s + '</svg>';
  }

  function render() {
    var g = state.gross, r = state.rate, years = Math.max(0, 65 - state.age);

    // 1) Maksuvõit — read
    var yourGross = r * g, netOutlay = (1 - TAX) * r * g, taxWin = TAX * r * g, stateAdd = STATE * g;
    $('#cNet').textContent = eur(netOutlay);
    $('#cPil').textContent = eur(yourGross);
    $('#cState').textContent = eur(stateAdd);
    $('#cInflow').textContent = eur(yourGross + stateAdd);
    $('#cTax').textContent = eur(taxWin * 12);

    // 2) Vara kasv — vertikaaltulbad 2/4/6, valitud esile
    var fv = function (rr) { return TulevaCalc.fvGrowing((rr + STATE) * g, state.ret, years, SG); };
    var fv2 = fv(0.02), fv4 = fv(0.04), fv6 = fv(0.06), maxB = Math.max(fv2, fv4, fv6) || 1, colH = 150;
    [['2', fv2, 0.02], ['4', fv4, 0.04], ['6', fv6, 0.06]].forEach(function (row) {
      var k = row[0], v = row[1], rr = row[2];
      $('#vv' + k).textContent = eur(v);
      var col = $('#vc' + k);
      col.style.height = Math.max(4, v / maxB * colH) + 'px';
      col.closest('.vbar').classList.toggle('dim', Math.abs(rr - r) > 1e-9);
    });
    $('#chart').innerHTML = buildChart(g, state.ret, state.age, r);

    // 3) Dekompositsioon — valitud määral, 4 osa
    var cNet = TulevaCalc.sumGrowing(netOutlay, years, SG),
      cTax = TulevaCalc.sumGrowing(taxWin, years, SG),
      cState = TulevaCalc.sumGrowing(stateAdd, years, SG);
    var fvTot = fv(r), grow = Math.max(0, fvTot - (cNet + cTax + cState)), tot = fvTot || 1;
    $('#sNet').style.width = (cNet / tot * 100) + '%';
    $('#sState').style.width = (cState / tot * 100) + '%';
    $('#sTax').style.width = (cTax / tot * 100) + '%';
    $('#sGrow').style.width = (grow / tot * 100) + '%';
    $('#lNet').textContent = eur(cNet);
    $('#lState').textContent = eur(cState);
    $('#lTax').textContent = eur(cTax);
    $('#lGrow').textContent = eur(grow);
  }

  root.querySelectorAll('#segRate button').forEach(function (b) {
    b.addEventListener('click', function () {
      root.querySelectorAll('#segRate button').forEach(function (x) { x.setAttribute('aria-pressed', 'false'); });
      b.setAttribute('aria-pressed', 'true');
      state.rate = parseFloat(b.dataset.r) / 100;
      render();
    });
  });
  $('#gross').addEventListener('input', function (e) { state.gross = Math.max(0, parseFloat(e.target.value) || 0); render(); });
  $('#age').addEventListener('input', function (e) { state.age = parseInt(e.target.value, 10); $('#ageVal').textContent = state.age; render(); });
  $('#ret').addEventListener('input', function (e) { state.ret = parseFloat(e.target.value) / 100; $('#retVal').textContent = parseFloat(e.target.value) + '%'; render(); });

  render();
})();
