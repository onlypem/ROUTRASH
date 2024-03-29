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
            <h5>Tabel Driver       <a class="fa fa-plus-square-o" href="/driver/tambah" title="Edit"></a>    </h5>
        </div>
        <div class="card-body" style="padding-top: 5px;">

            <div class="table-responsive">
                <table class="display" id="basic-2">
                    <thead>
                        <tr>
                            <?php $no=1; ?>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis Kendaraan</th>
                            <th>No. Polisi</th>
                            <th>Alamat</th>
                            <th>Username</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($driver as $key => $d)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $d->name }} </td>
                            <td>{{ $d->jeniskendaraan->jenis_kendaraan}} </td>
                            <td>{{ $d->no_polisi }} </td>
                            <td>{{ $d->alamat }} </td>
                            <td>{{ $d->username }} </td>
                            <td>{{ $d->no_telepon }} </td>


                            <td>
                                <a class="fa fa-edit" href="/driver/edit/{{ $d->id }}" title="Edit"></a> &nbsp;&nbsp;
                                <a class="fa fa-trash-o" href="/driver/hapus/{{ $d->id }}" title="Hapus"></a> &nbsp;&nbsp;
                                {{-- <a class="fa fa-search" href="/driver/detail/{{ $d->id }}" title="Detail"></a> --}}
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
