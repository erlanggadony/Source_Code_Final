<!DOCTYPE html>
  <head>
      <title>Isi data diri</title>
      <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset("/css/styles_list_surat.css") }}" rel="stylesheet" type="text/css">

  </head>

  <body>
    <div>
        <img id=banner src="{{ asset("/images/banner ftis.png") }}" />
    </div>


    <!-- Navigation here -->
    @include('mahasiswa.menu')

    <div class="container">
      <div class="main">
          <div class="row">
            <div class="col-md-8 content">
              <!-- <form class="form-horizontal">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 prevLabel">Email</label>
                  <div class="col-sm-10">
                    erlangga.dony@gmail.com
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                  </div>
                </div>
              </form> -->

              <a href="{{ URL::to('/tambah_data_mahasiswa') }}" class="btn btn-default">Tambah Data Mahasiswa</a>
              <form class="form-inline">
                <div class="form-group">
                  <label for="exampleInputName2">Cari berdasarkan :</label><br>
                  <!-- <select name="kategori_mahasiswa" class="form-control">
                    <option value="tanggalBuat">Cari semua surat</option>
                    <option value="nirm">NIRM</option>
                    <option value="npm">NPM</option>
                    <option value="nama_mahasiswa">Nama Mahasiswa</option>
                    <option value="prodi">Program Studi</option>
                    <option value="angkatan">Angkatan</option>
                    <option value="kota_lahir">Kota Lahir</option>
                    <option value="tanggal_lahir">Tanggal Lahir</option>
                    <option value="foto">Foto</option>
                  </select> -->
                  Form::select('size', array('L' => 'Large', 'S' => 'Small'));
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2">Kata kunci :</label><br>
                  <input type="text" name="searchBox" class="form-control" size = "63">
                  <button type="submit" name="findmail" class="btn btn-primary">Cari surat</button>
                </div>
              </form>

              {{ Form::open(array('url' => 'foo/bar')) }}
                echo Form::label('email', 'E-Mail Address');
              {{ Form::close() }}
            </div>
            <div class="col-md-4 profile">.col-md-4</div>
          </div>
            <!-- <div id="profile">
                <img id=profpict src="{{ asset("/images/2012730071.jpg") }}" />
                <br>
                <h2>Dony Erlangga</h2>
                <h3>2012730071</h3>
                </div>
            </div>

            <div id = "content">

            </div> -->

      </div>
    </div>
    <div class="footer">
        hahahahahahahahahahahahahahahhahahahahahaha
    </div>
  </body>
</html>
