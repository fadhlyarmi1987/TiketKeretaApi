async function getTrains() {
    try {
        const res = await fetch('/api/trains', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin' // penting, kirim cookie
        });

        const data = await res.json();
        console.log(data);
    } catch (err) {
        console.error(err);
    }
}

getTrains();
