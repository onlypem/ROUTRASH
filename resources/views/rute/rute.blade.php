@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
        <div class="container-fluid">
<div class="page-header">
<div class="row">
 <!-- Feature Unable /Disable Order Starts-->
 <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h6>Data Rute</h6>
            <h5>Pilihan Driver</h5>
            <span>Buka data jarak terlebih dahulu sebelum memulai rute</span>


        </div>
        <div class="card-body" style="padding-top: 5px;">
            <div class="table-responsive">
                <table class="display" id="basic-2">
                    <thead>
                        <tr>
                            <?php $no=1; ?>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($driver as $key => $d)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $d->name }} </td>

                            <td>

                                <a class="fa fa-location-arrow" href="/rute/detail/{{ $d->id }}" title="Detail"></a> &nbsp;&nbsp;
                                <a class="fa fa-repeat" href="/rute/reset/{{ $d->id }}"  title="Reset"></a>

                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                    </table>
                </div>
            </div>

</div></div></div></div></div></div></div>
@include('layout.footer')
@include('layout.js')
