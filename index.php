<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP Insert Update Delete with Vue.js</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <style>
   .modal-mask {
     position: fixed;
     z-index: 9998;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: rgba(0, 0, 0, .5);
     display: table;
     transition: opacity .3s ease;
   }

   .modal-wrapper {
     display: table-cell;
     vertical-align: middle;
   }
  </style>
 </head>
 <body>
  <div class="container" id="crudApp">
   <br />
   <h3 align="center">Delete or Remove Data From Mysql using Vue.js with PHP</h3>
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <div class="row">
      <div class="col-md-6">
       <h3 class="panel-title">Sample Data</h3>
      </div>
      <div class="col-md-6" align="right">
       <input type="button" class="btn btn-success btn-xs" @click="openModel" value="Add" />
       <input type="button" class="btn btn-success btn-xs" @click="openModel2" value="GetRunno" />
      </div>
     </div>
    </div>
    <div class="panel-body">
     <div class="table-responsive">
      <table class="table table-bordered table-striped">
       <tr>
        <th>Instance ID</th>
        <th>Serial No</th>
        <th>Edit</th>
        <th>Delete</th>
        <th>Generate Running no</th>
       </tr>
       <tr v-for="row in allData">
        <td>{{ row.instanceid }}</td>
        <td>{{ row.serialno }}</td>
        <td><button type="button" name="edit" class="btn btn-primary btn-xs edit" @click="fetchData(row.sid)">Edit</button></td>
        <td><button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row.sid)">Delete</button></td>
        <td><button type="button" name="getrunno" class="btn btn-danger btn-xs delete" @click="genRunnoData(row.sid)">Gen runno</button></td>
       </tr>
      </table>
     </div>
    </div>
   </div>
   <div v-if="myModel">
    <transition name="model">
     <div class="modal-mask">
      <div class="modal-wrapper">
       <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" @click="myModel=false"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{ dynamicTitle }}</h4>
         </div>
         <div class="modal-body">
          <div class="form-group">
           <label>Enter Instance ID</label>
           <input type="text" class="form-control" v-model="instanceid"  />
          </div>
          <div class="form-group">
           <label>Enter Running no</label>
           <input type="text" class="form-control" v-model="serialno"  />
          </div>
          <br />
          <div align="center">
           <input type="hidden" v-model="hiddenId" />
           <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click="submitData" />
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
    </transition>
   </div>
  </div>
 </body>
</html>

<script>

var application = new Vue({
 el:'#crudApp',
 data:{
  allData:'',
  myModel:false,
  actionButton:'Insert',
  dynamicTitle:'Add Data',
  genRunnoTitle:'Generate running no',
  userid:'',
  instanceid:'',
  serialno:'',
 },
 methods:{
  fetchAllData:function(){
   axios.post('action.php', {
    action:'fetchall'
   }).then(function(response){
    application.allData = response.data;
   });
  },
  openModel:function(){
   application.instanceid = '';
   application.serialno= '';
   application.actionButton = "Insert";
   application.dynamicTitle = "Add Data";
   application.genRunnoTitle = "Generate running no";
   application.userid = "cct3000";
   application.myModel = true;
  },
  openModel2:function(){

    application.instanceid = this.instanceid;
    application.serialno = this.serialno;
    application.sid = this.sid;
    application.userid = this.userid;
    application.myModel = true;
    application.dynamicTitle = "Generate running no";
   application.actionButton = "GenRunno";
   application.genRunnoTitle = "Generate running no";
   },
//    application.instanceid = '';
//    application.serialno= '';
//    application.dynamicTitle = "Generate running no";
//    application.actionButton = "GenRunno";
//    application.genRunnoTitle = "Generate running no";
//    application.myModel = true;      
    
  submitData:function(){
   if(application.instanceid != '' && application.serialno != '')
   {
    if(application.actionButton == 'Insert')
    {
     axios.post('action.php', {
      action:'insert',
      instanceid:application.instanceid, 
      serialno:application.serialno
     }).then(function(response){
      application.myModel = false;
      application.fetchAllData();
      //application.instanceid = '';
      //application.serialno = '';
      alert(response.data.message);
     });
    }
    if(application.actionButton == 'GenRunno')
    {
        // userid = this.userid;
        // serialno = this.serialno;
        // instanceid = this.instanceid;
     axios.post('action.php', {
      action:'genrunno',
      instanceid:application.instanceid, 
      serialno:application.serialno,
      userid: application.userid
    //   instanceid:application.instanceid, 
    //   serialno:application.serialno,

     }).then(function(response){
      application.myModel = false;
      application.fetchAllData();
      //application.instanceid = '';
      //application.serialno = '';
      alert(response.data.message);
     });
    }    
    if(application.actionButton == 'Update')
    {
     axios.post('action.php', {
      action:'update',
      instanceid:application.instanceid, 
      serialno:application.serialno,
      sid : application.sid
     }).then(function(response){
      application.myModel = false;
      application.fetchAllData();
     // application.instanceid = '';
      //application.serialno = '';
      //application.sid = '';
      alert(response.data.message);
     });
    }
   }
   else
   {
    alert("Fill All Field");
   }
  },
  genRunnoData:function(sid){
    axios.post('action.php', {
    action:'fetchSingle',
    sid:sid
   }).then(function(response){
    application.instanceid = response.data.instanceid;
    application.serialno = response.data.serialno;
    application.sid = response.data.sid;
    application.myModel = true;
    application.actionButton = 'GenRunno';
    application.dynamicTitle = 'Generate running no';
   });      
      
  },
  fetchData:function(sid){
   axios.post('action.php', {
    action:'fetchSingle',
    sid:sid
   }).then(function(response){
    application.instanceid = response.data.instanceid;
    application.serialno = response.data.serialno;
    application.sid = response.data.sid;
    application.myModel = true;
    application.actionButton = 'Update';
    application.dynamicTitle = 'Edit Data';
   });
  },
  deleteData:function(sid){
   if(confirm("Are you sure you want to remove this data?"))
   {
    axios.post('action.php', {
     action:'delete',
     sid:sid
    }).then(function(response){
     application.fetchAllData();
     alert(response.data.message);
    });
   }
  }
 },
 beforeMount: function () {
        params = new URLSearchParams(location.search);
        this.instanceid = params.post('instanceid');
        this.userid = params.post('userid');
        this.serialno = params.post('serialno');
        
    },
    mounted: function(){
        console.log(this.instanceid);
        console.log(this.serialno);
        console.log(this.userid);
    },
 created:function(){
  this.fetchAllData();
 }
});

</script>