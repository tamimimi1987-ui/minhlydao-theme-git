(function () {
  var modal = document.getElementById('mld-lich-modal');
  if (!modal) { return; }
  var box  = modal.querySelector('.mld-lich-modal-body');
  var closeBtn = modal.querySelector('.mld-lich-modal-close');

  function openModal(info) {
    var gioRows = info.gio.map(function (g) {
      var cls = g.hoang_dao ? 'is-hoangdao' : 'is-hacdao';
      return '<li class="' + cls + '"><span class="g-ten">' + g.gio + '</span><span class="g-sao">' + g.sao + '</span></li>';
    }).join('');

    var tietKhiRow = '<tr><th>Tiết khí</th><td>' + info.tietkhi +
      ' <span class="tk-i18n">(' + info.tietkhien + ' / ' + info.tietkhifr + ')</span>' +
      (info.tietkhistart ? ' <span class="tk-start-tag">— bắt đầu hôm nay</span>' : '') +
      '</td></tr>';

    box.innerHTML =
      '<h3>Ngày ' + info.duong + '</h3>' +
      '<table class="mld-lich-info-table">' +
      '<tr><th>Âm lịch</th><td>' + info.am + '</td></tr>' +
      '<tr><th>Can Chi ngày</th><td>' + info.ccngay + '</td></tr>' +
      '<tr><th>Can Chi tháng</th><td>' + info.ccthang + '</td></tr>' +
      '<tr><th>Can Chi năm</th><td>' + info.ccnam + '</td></tr>' +
      '<tr><th>Trực</th><td>' + info.truc + '</td></tr>' +
      '<tr><th>Sao (Nhị thập bát tú)</th><td>' + info.sao + ' — ' + (info.hoangdao ? 'Hoàng đạo (tốt)' : 'Hắc đạo (xấu)') + '</td></tr>' +
      tietKhiRow +
      '</table>' +
      '<h4>Giờ hoàng đạo trong ngày</h4>' +
      '<ul class="mld-lich-gio-list">' + gioRows + '</ul>';

    modal.hidden = false;
    document.body.classList.add('mld-lich-modal-open');
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('mld-lich-modal-open');
  }

  document.querySelectorAll('.mld-lich-cell').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var raw = btn.getAttribute('data-info');
      if (!raw) { return; }
      try {
        openModal(JSON.parse(raw));
      } catch (e) { /* noop */ }
    });
  });

  closeBtn && closeBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', function (e) {
    if (e.target === modal) { closeModal(); }
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) { closeModal(); }
  });
})();
