<div class="container">
    <h3>Admin Dashboard</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <div id="jumlah" style="margin: 1rem">
            <div class="row"></div>
        <div>
        <div style="position: relative; width: 100%; margin: 1rem">
            <canvas id="statistik"></canvas>
        </div>
        <table style="margin: 1rem" id="statistikTbl" class="striped highlight responsive-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prodi</th>
                    <th>Suara Masuk</th>
                    <th>SSO Aktif</th>
                    <th>Kuota</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function() {
    $.get('dashboard/getdashboardinfo', null, function(info) {
        info.jumlah.forEach(function(jumlah) {
            var card = '';

            card += '<div class="col s12 m6">';
            card += ' <div class="card horizontal white-text">';
            card += '  <div class="card-image teal darken-1" style="width: 6rem"><h3 style="margin: 1rem" class="fa fa-' + jumlah.icon + '"></h3></div>';
            card += '  <div class="card-stacked teal darken-3">';
            card += '   <div class="card-content"><b>' + jumlah.value + '</b> ' + _.escape(jumlah.name) + '</div>';
            card += '   <div class="card-action"><a href="' + jumlah.url + '">Detail &raquo;</a></div>';
            card += '  </div>';
            card += ' </div>';
            card += '</div>';

            $('#jumlah div.row').append(card);
        });

        var listProdi = [];
        var listSuaraMasuk = [];
        var listSSOAktif = [];
        var listKuota = [];
        info.statistik.forEach(function(prodi) {
            listProdi.push(prodi.nama);
            listSuaraMasuk.push(prodi.pemilih);
            listSSOAktif.push(prodi.useraktif);
            listKuota.push(prodi.kuota);

            var tr = '';

            tr += '<tr>';
            tr += ' <td>' + prodi.id + '</td>';
            tr += ' <td>' + _.escape(prodi.nama) + '</td>';
            tr += ' <td>' + prodi.pemilih + '</td>';
            tr += ' <td>' + prodi.useraktif + '</td>';
            tr += ' <td>' + prodi.kuota + '</td>';
            tr += ' <td>' + (Math.round(prodi.pemilih / prodi.kuota * 100.0 * 100) / 100) + '%</td>';
            tr += '</tr>';

            $('#statistikTbl tbody').append(tr);
        });

        var ctx = document.getElementById('statistik');
        var chart = new Chart(ctx, {
            type: 'bar',
            grouped: false,
            data: {
                labels: listProdi,
                datasets: [
                    {label: 'Suara Masuk', data: listSuaraMasuk, backgroundColor: 'rgba(255, 0, 0, 0.5)', borderColor: 'rgba(255, 0, 0, 1.0)'},
                    {label: 'SSO Aktif', data: listSSOAktif, backgroundColor: 'rgba(255, 255, 0, 0.5)', borderColor: 'rgba(255, 255, 0, 1.0)'},
                    {label: 'Kuota', data: listKuota, backgroundColor: 'rgba(0, 255, 0, 0.5)', borderColor: 'rgba(0, 255, 0, 1.0)'}
                ]
            },
            resizeDelay: 1000
        });
    });
});
</script>
