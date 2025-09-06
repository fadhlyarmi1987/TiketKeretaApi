document.querySelectorAll(".seat-btn").forEach(seatEl => {
    if (seatEl.classList.contains("booked")) {
        return; // kursi sudah terisi, skip
    }

    seatEl.addEventListener("click", () => {
        const seatId = seatEl.dataset.seat;

        if (selectedSeats.includes(seatId)) {
            selectedSeats = selectedSeats.filter(s => s !== seatId);
            seatEl.classList.remove("selected");
        } else {
            if (selectedSeats.length < maxSeats) {
                selectedSeats.push(seatId);
                seatEl.classList.add("selected");
            } else {
                alert(`Maksimal ${maxSeats} kursi untuk booking ini.`);
            }
        }

        updateSelectedSeats();
    });
});
