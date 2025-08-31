document.addEventListener("DOMContentLoaded", function () {
    const allRows = document.querySelectorAll("#stations-body tr");
    const rowsPerPage = 10;
    let currentPage = 0;
    let filteredRows = Array.from(allRows); // default semua data

    function showPage(page) {
        // sembunyikan semua
        allRows.forEach(row => row.style.display = "none");

        // tampilkan hanya yang terfilter
        filteredRows.forEach((row, index) => {
            if (index >= page * rowsPerPage && index < (page + 1) * rowsPerPage) {
                row.style.display = "";
            }
        });

        // atur tombol navigasi
        document.getElementById("prev-btn").disabled = page === 0;
        document.getElementById("next-btn").disabled = (page + 1) * rowsPerPage >= filteredRows.length;
    }

    // tombol navigasi
    document.getElementById("prev-btn").addEventListener("click", function () {
        if (currentPage > 0) {
            currentPage--;
            showPage(currentPage);
        }
    });

    document.getElementById("next-btn").addEventListener("click", function () {
        if ((currentPage + 1) * rowsPerPage < filteredRows.length) {
            currentPage++;
            showPage(currentPage);
        }
    });

    // fitur search
    document.getElementById("station-search").addEventListener("keyup", function () {
        let q = this.value.toLowerCase().trim();
        filteredRows = Array.from(allRows).filter(row => {
            let rowText = row.innerText.toLowerCase();
            return rowText.includes(q);
        });
        currentPage = 0; // reset ke halaman pertama setiap search
        showPage(currentPage);
    });

    // tampilkan halaman pertama
    showPage(currentPage);
});