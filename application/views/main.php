<script type="text/javascript">
  function message(message, type) {
    $.notify({
      icon: 'fas fa-exclamation-triangle',
      message: message,
    }, {
      // settings
      element: 'body',
      type: type,
      allow_dismiss: false,
      newest_on_top: false,
      placement: {
        from: "top",
        align: "right"
      },
      spacing: 10,
      z_index: 2000,
      delay: 2000,
      timer: 1000,
      animate: {
        enter: 'animated fadeInDown',
        exit: 'animated fadeOutUp'
      }
    });
  }
  $(document).ready(function() {
    $("#save").click(function() {
      var data = $("#form-add").serializeArray();
      $.ajax({
        url: '<?= base_url('main/save'); ?>',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(response) {
          $(".field").val("");
          $("#participants").append(response.row);
          $("#modal-add .cancel").click();
          message("Participant added successfuly", "success");
        }
      });
    });
    $("#upload").click(function() {
      var file = $("#santas-list")[0].files[0];

      var formData = new FormData();
      formData.append("file", file);

      $.ajax({
        url: '<?= base_url('main/upload') ?>',
        method: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
          if (response.status == 0) {
            message(response.error.error, "danger");
          } else {

            $("#participants").empty().append(response.table);
            message("List uploaded successfuly.", "success");
          }
          $("#santas-list").empty().val("");
          $("#modal-upload .cancel").click();
        }
      });
    });
    $("#clear-list").click(function() {
      $.ajax({
        url: '<?= base_url('main/clear'); ?>',
        type: 'POST',
        dataType: 'json',
        success: function() {
          message("The list has been cleared", "warning");
          $("#participants").empty();
        }
      });
    });
    $("#shuffle").click(function() {
      $("#pairs-list").empty();
      $.ajax({
        url:'<?= base_url('main/shuffle'); ?>',
        type:'POST',
        dataType:'json',
        success: function(response){
          if(response.status==0){
            message("There's not enough people to make pairs");
          }else{
            $("#pairs-list").append(response.table);
            $("#modal-pairs").modal({
        backdrop: 'static',
        keyboard: false,
        show: true
      });
          }
        }
      });
    });
  });
</script>
<br>
<div class="w3-blue-gray w3-card-4 w3-round">

  <div class="w3-container">
    <br>
    <h1 class="w3-text-white"> Secret Santa</h1>
    <br>
  </div>

</div>
<div class="container w3-light-gray">
  <hr>
  <div class="btn-group">
    <button class="btn btn-success" data-toggle="modal" data-target="#modal-add" data-backdrop="static"><i class="fas fa-user-plus"></i> Add Participant</button>
    <button class="btn btn-success" data-toggle="modal" data-target="#modal-upload" data-backdrop="static"><i class="fas fa-upload"></i> Upload file</button>
    <button class="btn btn-success" id="shuffle"><i class="fas fa-list"></i> Create list</button>
    <button class="btn btn-success" id="clear-list"><i class="fas fa-trash-alt"></i> Clear list</button>
  </div>
  <hr>
  <table class="table table-warning table-striped table-hover w3-card-4">
    <thead class="">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Last Name</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody id="participants">
      <?php foreach ($secretsanta as $santa) : ?>
        <tr>
          <td><?= $santa['id']; ?></td>
          <td><?= $santa['firstName']; ?></td>
          <td><?= $santa['lastName']; ?></td>
          <td><?= $santa['eMail']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br><br>
</div>
<!-- Modal add participant -->

<div id="modal-add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert-success">
        <h5 class="modal-title" id="my-modal-title">Add Participant</h5>
      </div>
      <div class="modal-body">
        <form id="form-add" class="form-vertical">
          <label for="name">Name:</label>
          <input type="text" name="firstName" id="firstName" value="" autocomplete="off" class="form-control field required">
          <label for="name">Last Name:</label>
          <input type="text" name="lastName" id="lastName" value="" autocomplete="off" class="form-control field required">
          <label for="name">E-Mail:</label>
          <input type="text" name="eMail" id="eMail" value="" autocomplete="off" class="form-control field required">
        </form>
      </div>
      <div class="modal-footer alert-secondary">
        <button class="btn btn-success" id="save">Save</button>
        <button class="btn btn-danger cancel" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal upload file -->

<div id="modal-upload" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header alert-success">
        <h4 class="modal-title">Upload List</h4>
      </div>
      <div class="modal-body">
        <br>
        <form class="form-vertical" id="form-upload">
          <input type="file" name="santas-list" id="santas-list" value="">
        </form>
        <br>
      </div>
      <div class="modal-footer w3-light-gray">
        <button class="btn btn-success" id="upload">Upload</button>
        <button type="button" class="btn btn-danger cancel" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pairs -->

<div id="modal-pairs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header alert-primary">
        <h4 class="modal-title" id="myModalLabel">Pairs</h4>
      </div>
      <div class="modal-body">
        <table class="table table-success">
          <thead>
            <th>Giver</th>
            <th>Receiver</th>
          </thead>
          <tbody id="pairs-list"></tbody>
        </table>
      </div>
      <div class="modal-footer w3-light-gray">
        <button class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>