// js/main.js
document.addEventListener('DOMContentLoaded', () => {
  // simple floating text effect already handled by CSS animation
  // small helper to preview uploaded photo in profile edit pages
  const photoInput = document.querySelector('#photoInput');
  if (photoInput) {
    photoInput.addEventListener('change', e => {
      const f = e.target.files[0];
      if (!f) return;
      const img = document.querySelector('#photoPreview');
      const url = URL.createObjectURL(f);
      if (img) img.src = url;
    });
  }

  // filtering table (for member dashboard)
  const searchInputs = document.querySelectorAll('.filter-input');
  if (searchInputs.length) {
    searchInputs.forEach(inp => {
      inp.addEventListener('input', () => {
        const table = document.querySelector('#recordsTable');
        if (!table) return;
        const rows = table.querySelectorAll('tbody tr');
        const valName = document.querySelector('#filterCrop').value.toLowerCase();
        const valVillage = document.querySelector('#filterVillage').value.toLowerCase();
        const valTaluk = document.querySelector('#filterTaluk').value.toLowerCase();
        rows.forEach(r => {
          const crop = r.dataset.crop.toLowerCase();
          const village = r.dataset.village.toLowerCase();
          const taluk = r.dataset.taluk.toLowerCase();
          const show = (valName===''||crop.includes(valName)) &&
                       (valVillage===''||village.includes(valVillage)) &&
                       (valTaluk===''||taluk.includes(valTaluk));
          r.style.display = show ? '' : 'none';
        });
      });
    });
  }
});
