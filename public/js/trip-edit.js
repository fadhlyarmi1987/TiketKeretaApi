document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("stationTable");
  if (!table) return;

  const tbody = table.querySelector("tbody");
  const addBtn = document.getElementById("addRow");

  let rowIndex = parseInt(table.dataset.initialCount || "0", 10);

  // Ambil data stasiun dari <script>
  let stations = [];
  try {
    const jsonEl = document.getElementById("stationsData");
    stations = JSON.parse(jsonEl?.textContent || "[]");
  } catch (e) {
    console.error("Gagal parsing stationsData:", e);
    stations = [];
  }

  // Generate <option>
  const makeOptions = () =>
    stations
      .map((s) => `<option value="${s.id}">${s.name} (${s.code}) | ${s.city}</option>`)
      .join("");

  // Tambah baris baru
  if (addBtn) {
    addBtn.addEventListener("click", function () {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>
          <select name="stations[${rowIndex}][station_id]" class="form-select station-select w-100">
            ${makeOptions()}
          </select>
        </td>
        <td>
          <input type="time" name="stations[${rowIndex}][arrival_time]" class="form-control w-100">
        </td>
        <td>
          <input type="time" name="stations[${rowIndex}][departure_time]" class="form-control w-100">
        </td>
        <td class="text-center">
          <input type="hidden" name="stations[${rowIndex}][day_offset]" value="0">
          <input type="checkbox" class="form-check-input"
            name="stations[${rowIndex}][day_offset]"
            value="1">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-outline-danger btn-sm removeRow">
            <i class="bi bi-trash"></i> Hapus
          </button>
        </td>
      `;
      tbody.appendChild(tr);
      rowIndex++;

      // aktifkan select2 pada baris baru
      $(tr).find(".station-select").select2({
        placeholder: "Pilih stasiun...",
        width: "100%"
      });
    });
  }

  // Hapus baris
  tbody.addEventListener("click", function (e) {
    const btn = e.target.closest(".removeRow");
    if (btn) {
      const row = btn.closest("tr");
      if (row) row.remove();
    }
  });

  // Aktifkan select2 pada select yang sudah ada
  $(".station-select").select2({
    placeholder: "Pilih stasiun...",
    width: "100%"
  });
});
