<?= $this->extend("layouts/master_app") ?>

<?= $this->section("content") ?>
<!-- Main content -->

<section class="content">
  <div class="box">
    <div class="box-header with-border">
      <h4 class="box-title text-capitalize">Data project</h4>
      <button type="button" class="btn float-end btn-primary btn-sm" onclick="save()" title="<?= lang("App.new") ?>"> <?= lang('App.new') ?></button>
    </div>
    <div class="box-body">
      <table id="data_table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Client name</th>
            <th>Project name</th>
            <th>Start date</th>
            <th>End date</th>
            <th>Link</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</section>

<!-- /Main content -->

<!-- ADD modal content -->
<div id="data-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="text-center bg-info p-3" id="model-header">
        <h4 class="modal-title text-white" id="info-header-modalLabel"></h4>
      </div>
      <div class="modal-body">
        <form id="data-form" class="pl-3 pr-3">
          <?= csrf_field() ?>
          <div class="row">
            <input type="hidden" id="id_project" name="id_project" class="form-control" placeholder="Id project" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="client_name" class="col-form-label"> Client name: <span class="text-danger">*</span> </label>
                <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Client name" minlength="0" maxlength="100" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="project_name" class="col-form-label"> Project name: <span class="text-danger">*</span> </label>
                <input type="text" id="project_name" name="project_name" class="form-control" placeholder="Project name" minlength="0" maxlength="100" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="start_date" class="col-form-label"> Start date: </label>
                <input type="datetime-local" type="1" id="start_date" name="start_date" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="end_date" class="col-form-label"> End date: </label>
                <input type="datetime-local" type="1" id="end_date" name="end_date" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="vendor_id" class="col-form-label"> Vendor Name: </label>
                    <select class="form-control select2bs4" style="width: 100%;" id="v1" name="v1">
                      <?php foreach ($v_a as $v) : ?>
                        <option value="<?= $v->id_vendor ?>"><?= $v->vendor_name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="vendor_id" class="col-form-label"> Vendor Name: </label>
                    <select class="form-control select2bs4" style="width: 100%;" id="v2" name="v2">
                      <?php foreach ($v_b as $v) : ?>
                        <option value="<?= $v->id_vendor ?>"><?= $v->vendor_name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="vendor_id" class="col-form-label"> Vendor Name: </label>
                    <select class="form-control select2bs4" style="width: 100%;" id="v3" name="v3">
                      <?php foreach ($v_c as $v) : ?>
                        <option value="<?= $v->id_vendor ?>"><?= $v->vendor_name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="vendor_id" class="col-form-label"> Vendor Name: </label>
                    <select class="form-control select2bs4" style="width: 100%;" id="v4" name="v4">
                      <?php foreach ($v_d as $v) : ?>
                        <option value="<?= $v->id_vendor ?>"><?= $v->vendor_name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="vendor_id" class="col-form-label"> Nama Pegawai: </label>
                    <select class="form-control select2bs4" style="width: 100%;" id="p2" name="p2">
                      <?php foreach ($p_b as $v) : ?>
                        <option value="<?= $v->id_pegawai ?>"><?= $v->pegawai_name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="vendor_id" class="col-form-label"> Nama Pegawai: </label>
                    <select class="form-control select2bs4" style="width: 100%;" id="p3" name="p3">
                      <?php foreach ($p_c as $v) : ?>
                        <option value="<?= $v->id_pegawai ?>"><?= $v->pegawai_name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <?php foreach ($r_v as $r) : ?>
                  <div class="col-md-12">
                    <div class="form-group mb-3">
                      <label for="vendor_id" class="col-form-label"> Pekerjaan: </label>
                      <input type="hidden" value="<?= $r->role_id ?>" readonly class="form-control" placeholder="Vendor id" minlength="0">
                      <input type="text" value="<?= $r->role_name ?>" readonly class="form-control" placeholder="Vendor id" minlength="0">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="link" class="col-form-label"> Link: <span class="text-danger">*</span> </label>
                <input type="text" id="link" name="link" class="form-control" placeholder="Link" minlength="0" maxlength="200" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <input type="hidden" name="status" value="0">
              </div>
            </div>
          </div>

          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn"><?= lang("App.save") ?></button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("App.cancel") ?></button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- /ADD modal content -->


<?= $this->endSection() ?>
<!-- page script -->
<?= $this->section("script") ?>
<script>
  let csrfHash = '<?= csrf_hash(); ?>'
  let csrfToken = '<?= csrf_token(); ?>'
  // dataTables

  function master() {
    let form = $('#data-form')
    console.log(form.serialize());
  }

  $(function() {
    var table = $('#data_table').removeAttr('width').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollY": '45vh',
      "scrollX": true,
      "scrollCollapse": false,
      "responsive": false,
      "ajax": {
        "url": '<?php echo base_url($controller . "/getAll") ?>',
        "type": "POST",
        "dataType": "json",
        async: "true"
      }
    });
  });

  var urlController = '';
  var submitText = '';

  function getUrl() {
    return urlController;
  }

  function getSubmitText() {
    return submitText;
  }

  function save(id_project) {
    // reset the form 
    $("#data-form")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_project === 'undefined' || id_project < 1) { //add
      urlController = '<?= base_url($controller . "/add") ?>';
      submitText = '<?= lang("App.save") ?>';
      $("#info-header-modalLabel").text('<?= lang("App.add") ?>');
      $("#form-btn").text(submitText);
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = '<?= lang("App.update") ?>';
      $.ajax({
        url: '<?= base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_project: id_project,
          [csrfToken]: csrfHash
        },
        dataType: 'json',
        success: function(response) {
          $("#info-header-modalLabel").text('<?= lang("App.edit") ?>');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_project").val(response.id_project);
          $("#data-form #client_name").val(response.client_name);
          $("#data-form #project_name").val(response.project_name);
          $("#data-form #start_date").val(response.start_date);
          $("#data-form #end_date").val(response.end_date);
          $("#data-form #vendor_id").val(response.vendor_id);
          $("#data-form #link").val(response.link);
          $("#data-form #status").val(response.status);


        }
      });
    }
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        var form = $('#data-form');
        $(".text-danger").remove();
        $.ajax({
          // fixBug get url from global function only
          // get global variable is bug!
          url: getUrl(),
          type: 'post',
          data: form.serialize(),
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#form-btn').html('<i class="fad fa-spinner fa-spin"></i>');
          },
          success: function(response) {
            if (response.success === true) {
              $('#data-modal').modal('hide');
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_table').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var ele = $("#" + index);
                  ele.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
                  ele.after('<div class="invalid-feedback">' + response.messages[index] + '</div>');
                });
              } else {
                Swal.fire({
                  toast: false,
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 3000
                })

              }
            }
            $('#form-btn').html(getSubmitText());
          }
        });
        return false;
      }
    });

    $('#data-form').validate({

      //insert data-form to database

    });
  }

  function remove(id_project, id_vendor) {
    Swal.fire({
      title: "<?= lang("App.remove-title") ?>",
      text: "<?= lang("App.remove-text") ?>",
      icon: 'warning',
      showCancelButton: true,
      showClass: {
        popup: 'animate__animated animate__fadeInDown'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      },
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?= lang("App.confirm") ?>',
      cancelButtonText: '<?= lang("App.cancel") ?>'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url($controller . "/remove") ?>',
          type: 'post',
          data: {
            id_project: id_project,
            id_project: id_project,
            [csrfToken]: csrfHash
          },
          dataType: 'json',
          success: function(response) {
            if (response.success === true) {
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_table').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                toast: false,
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 3000
              })
            }
          }
        });
      }
    })
  }
</script>


<?= $this->endSection() ?>