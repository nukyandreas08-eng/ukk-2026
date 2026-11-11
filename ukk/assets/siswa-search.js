/* Pencarian pada dropdown siswa dan guru berdasarkan nama/NIS. */
(function () {
  function aktifkanPencarian() {
    document.querySelectorAll('select[name="siswa_id"], select[name="guru_id"]').forEach(function (select) {
      if (select.hasAttribute('data-search-ready')) return;
      select.setAttribute('data-search-ready', '1');
      var input = document.createElement('input');
      input.type = 'search';
      input.className = 'form-control mb-2';
      input.placeholder = select.name === 'guru_id'
        ? 'Cari guru berdasarkan nama...'
        : 'Cari siswa berdasarkan NIS atau nama...';
      input.setAttribute('aria-label', input.placeholder);
      select.parentNode.insertBefore(input, select);
      var opsi = Array.from(select.options).map(function (option) {
        return { node: option, text: option.textContent.toLocaleLowerCase('id') };
      });
      input.addEventListener('input', function () {
        var kata = input.value.trim().toLocaleLowerCase('id');
        var terpilih = select.value;
        opsi.forEach(function (item) {
          item.node.hidden = !!kata && item.node.value !== '' && !item.text.includes(kata);
        });
        if (terpilih && select.selectedOptions.length && select.selectedOptions[0].hidden) select.value = '';
      });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', aktifkanPencarian);
  else aktifkanPencarian();
})();
