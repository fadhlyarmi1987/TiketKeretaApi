document.addEventListener("DOMContentLoaded", function () {
    let rowIndex = window.initialTripStationsCount || 0;
    let stations = window.stationsData || [];

    const addRowBtn = document.getElementById("addRow");
    const tableBody = document.querySelector("#stationTable tbody");

    addRowBtn.addEventListener("click", function () {
        let newRow = document.createElement("tr");

        // Buat opsi stasiun secara dinamis
        let options = stations.map(station =>
            `<option value="${station.id}">${station.name} (${station.city})</option>`
        ).join("");

        newRow.innerHTML = `
            <td>
                <select name="stations[${rowIndex}][station_id]" class="form-control">
                    ${options}
                </select>
            </td>
            <td><input type="time" name="stations[${rowIndex}][arrival_time]" class="form-control"></td>
            <td><input type="time" name="stations[${rowIndex}][departure_time]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger removeRow">❌</button></td>
        `;

        tableBody.appendChild(newRow);
        rowIndex++;
    });

    // Event untuk hapus row
    tableBody.addEventListener("click", function (e) {
        if (e.target.classList.contains("removeRow")) {
            e.target.closest("tr").remove();
        }
    });
});
