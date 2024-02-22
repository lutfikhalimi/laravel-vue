@extends('layouts.admin')
@section('header', 'Member')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}} ">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection


@section('content')
      <div id="controller">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right">Create New Member</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 30px">No</th>
                      <th>Nama</th>
                      <th class="text-center">Gender</th>
                      <th class="text-center">Phone_Number</th>
                      <th class="text-center">Address</th>
                      <th class="text-center">Email</th>
                      <th class="text-center" style="width: 200px">Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      
        <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post" :action="actionUrl" autocomplete="off" @submit="submitForm($event, data.id)">
            <div class="modal-header">
              <h4 class="modal-title">Member</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              @csrf

              <input type="hidden" name="_method" value="PUT" v-if="editStatus">
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"  :value="data.name" required="">
                  </div>
                  <div class="form-group">
                        <label>Gender</label>
                        <input type="gender" name="gender" class="form-control"  :value="data.gender" required="" >
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" class="form-control"   :value="data.phone_number" required="">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control"  :value="data.address" required="" >
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"   :value="data.email" required="">
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      </div>
      </div>



@endsection

@section('js')

<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<script type="text/javascript">
var actionUrl = "{{url('members')}}";
var apiUrl = "{{url('api/members')}}";

var columns = [
    {data : 'DT_RowIndex', class: 'text-center', orderable: true},
    {data : 'name', class: 'text-center', orderable: true},
    {data : 'gender', class: 'text-center', orderable: true},
    {data : 'phone_number', class: 'text-center', orderable: true},
    {data : 'address', class: 'text-center', orderable: true},
    {data : 'email', class: 'text-center', orderable: true},
    {render : function (index, row, data, meta){
        return`
        <a href='#' class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">
        Edit
        </a>
        <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">
        Delete
        </a>`;
        }, orderable: false, width:'200px', class: 'text-center'},
];

var controller = new Vue({
        el:'#controller',
        data: {
            datas: [],
            data: {},
            actionUrl,
            apiUrl,
            editStatus: false,
        },
        mounted:function(){
            this.datatable();
        },
        methods: {
            datatable(){
                const _this = this;
                _this.table = $('#datatable').DataTable({
                    ajax: {
                        url: _this.apiUrl,
                        type: 'GET',
                    },
                    columns
                }).on('xhr',function(){
                    _this.datas = _this.table.ajax.json().data;
                });
            },
            addData() {
                this.data = {};
                // this.actionUrl = "{{ url('members')}}";
                this.editStatus = false;
                $('#modal-default').modal();
            },
            editData(event, row) {
            //   console.log(data);
                this.data = this.datas[row];
                // this.actionUrl = "{{ url('members') }}"+'/'+this.data.id;
                this.editStatus = true;
                $('#modal-default').modal();
            },
            deleteData(event, id) {
                // console.log(id)
                // this.actionUrl = "{{ url('members') }}"+'/'+id;
                if (confirm("Are you sure ?")) {
                    $(event.target).parents('tr').remove();
                    axios.post(this.actionUrl+'/'+id, {_method: 'DELETE'}).then(response => {
                        alert('Data has been removed');
                });
            }
        },
        submitForm(event, id){
            event.preventDefault();
            const _this = this;
            var actionUrl = ! this.editStatus ? this.actionUrl : this.actionUrl+'/'+id;
            axios.post(actionUrl, new FormData($(event.target)[0])).then(response => {
                $('#modal-default').modal('hide');
                _this.table.ajax.reload();
            });
        },
        }
    });
</script>

<!-- <script type="text/javascript">
$(function () {
    $("#datatable").DataTable();
    
  });

</script>
  <script type="text/javascript">

    var controller = new Vue({
      el: '#controller',
      data: {
        data: {},
        actionUrl: "{{ url('members')}}",
        editStatus : false
      },
      mounted: function() {

      },
      methods: {
        addData() {
          this.data = {};
          this.actionUrl = "{{ url('members')}}";
          this.editStatus = false;
          $('#modal-default').modal();
        },
        editData(data) {
        //   console.log(data);
          this.data = data;
          this.actionUrl = "{{ url('members') }}"+'/'+data.id;
          this.editStatus = true;
          $('#modal-default').modal();
        },
        deleteData(id) {
            // console.log(id)
            this.actionUrl = "{{ url('members') }}"+'/'+id;
            if (confirm("Are you sure ?")) {
                axios.post(this.actionUrl, {_method: 'DELETE'}).then(response => {
                    location.reload();
                });
            }

        }
      }
    });

  </script> -->
@endsection