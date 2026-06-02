@php
    $colors = ['#2563eb','#64748b','#22c55e','#eab308','#a855f7','#ef4444','#06b6d4','#f97316'];
@endphp
<script>
(function () {
    const caborData = @json($entitiesPerCabor);
    const colors = @json($colors);

    function pieChart(id, dataObj) {
        const el = document.getElementById(id);
        if (!el) return;
        const labels = Object.keys(dataObj || {});
        const values = Object.values(dataObj || {});
        if (!labels.length) return;
        const total = values.reduce((a, b) => a + b, 0) || 1;
        new Chart(el, {
            type: 'pie',
            data: { labels, datasets: [{ data: values, backgroundColor: colors }] },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: (c) => c.label + ': ' + c.parsed + ' (' + Math.round(c.parsed / total * 100) + '%)' } }
                }
            }
        });
    }

    const barId = document.getElementById('chartCaborBar') ? 'chartCaborBar' : null;
    if (barId && caborData.length) {
        new Chart(document.getElementById(barId), {
            type: 'bar',
            data: {
                labels: caborData.map(r => r.name),
                datasets: [
                    { label: 'Atlet', data: caborData.map(r => r.atlet), backgroundColor: '#2563eb' },
                    { label: 'Pelatih', data: caborData.map(r => r.pelatih), backgroundColor: '#22c55e' },
                    { label: 'Wasit & Juri', data: caborData.map(r => r.wasit_juri), backgroundColor: '#eab308' },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    x: { ticks: { maxRotation: 45, minRotation: 45 } },
                    y: { beginAtZero: true, title: { display: true, text: 'Jumlah Entitas' } }
                }
            }
        });
    }

    pieChart('piePelatih', @json($pelatihByLevel));
    pieChart('pieWasitJuri', @json($wasitJuriByLevel));
    pieChart('piePrestasi', @json($prestasiByLevel));

    const yearData = @json($prestasiByYear ?? collect());
    if (document.getElementById('chartPrestasiYear') && yearData.length) {
        new Chart(document.getElementById('chartPrestasiYear'), {
            type: 'bar',
            data: {
                labels: yearData.map(r => r.year),
                datasets: [{ label: 'Prestasi', data: yearData.map(r => r.total), backgroundColor: '#dc2626' }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
})();
</script>
