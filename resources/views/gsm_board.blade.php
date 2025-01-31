<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<html>
<head>
<style>

/* Modal Content/Box */

.modal-header{
     background-color: #5cb85c;
     color: white;
     text-align: center; /* Center-align text */
     padding: 10px 30px;
  
}

.modal-footer {
    padding: 5px; /* Reduce the padding inside the footer */
    margin: 5px 0; /* Reduce the margin above and below the footer */
}

.modal-title {
    font-weight: bold; /* Make the title text bold */
    text-align: center;
}


/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.button1 {
  background-color: blue;
  border-radius: 4px;
  color: white;
  padding: 10px 20px;
  text-align: center;
  font-size: 16px;
  margin: 4px 2px;
  opacity: 0.6;
  transition: 0.3s;
  display: inline-block;
  text-decoration: none;
  cursor: pointer;
}

.button1:hover {opacity: 1}

input[type=number]:focus {
    border: 3px solid #555;
}


span {
    display: inline-block;
    text-align: center; /* Center the text horizontally within <span> elements */
    color: red;
}

.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }

.dataTables_processing{
    display:none !important;
}
</style>
</head>
<body>

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
<div class="px-3" style="text-align:center; color: black;  height: 50px; padding-top: 8px;">
        <h2 class="text-2xl font-medium" style="font-weight: bold;"> GSM Board List</h2>
    </div>
               

    <!-- Campaign List Table -->    
<div class="card mt-4">
    <div class="card">
        <div class="col card-body table-responsive">
            <table class="gsm_table hover stripe" id="gsm_table"   style="width:100%">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Board Name</th>
		            <th>Server Id</th>
                    <th>Ip Address</th>
                    <th>Board Status</th>
                    <th>Running Status</th>
                    <th>Board Connection Time</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 


<div class="modal" tabindex="-1" role="dialog" id="channelStatusModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:100%;">
      <div class="modal-header">
        <h5 style="text-align:center;" class="modal-title" id="channelStatusModalLabel">Channel Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
        <div id="channelStatusDetails"></div>
      </div>
    </div>
  </div>
</div>


<!-- Add name Modal -->
<div class="modal" tabindex="-1" role="dialog" id="add_name_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:80%;">

            <div class="modal-header">
                <h5 style="text-align:center;" class="modal-title" id="addName">Add name</h5>
        <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  -->
            </div>
            <div class="modal-body">
            <p>Server_id: <span id="server_id" class="credit-value"></span></p>
                <div class="form-group">
                    <label for="creditAmount">Enter the name for the board:</label>
                    <input type="text" autofocus class="form-control" id="add_name" required min="1" >
		    <span id="add_name_error" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-footer">
            <input type="hidden" name="hidd_server_id" id="hidd_server_id" class="form-control" value="">
		            <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
                <button type="button" class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="addNameBtn">Add</button>
            </div>
        </div>
    </div>
</div>


<!-- Success Modal -->
<div class="modal" tabindex="-1" role="dialog" id="successModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
        </div>
    </div>
</div>


<script>
  function add_name(server_id) 
  {

      $('#server_id').text(server_id);
      $("#hidd_server_id").val(server_id);

      $('#add_name_error').text('');
      $('#add_name').val('');

      $('#add_name_modal').modal('show');
  }

  document.getElementById('add_name').addEventListener('input', function () 
  {
    var input = this.value;
    var maxLength = 15; // Change this to the desired maximum length

     // Check if the input is '0' and is the first character
    if (input === '0') {
        this.value = ''; // Clear the input
    }

    if (input.length > maxLength) {
        this.value = input.slice(0, maxLength); // Trim input to the maximum length
    }

    // Display an error message if the length exceeds the maximum
    var errorMessage = document.getElementById('add_name_error');
    if (input.length > maxLength) {
        errorMessage.textContent = 'Board Name Should be' + maxLength + ' character or less.';
    } else {
        errorMessage.textContent = '';
    }
  });

  $(document).ready(function() 
  {
    // Function to reload the DataTable
    function reloadDataTable() 
    {
        $('#gsm_table').DataTable().ajax.reload(); // Reload the DataTable
    }

    // Reload the DataTable every 2 seconds (2000 milliseconds)
    setInterval(reloadDataTable, 30000);
});


  $('#addNameBtn').click(function () 
  {
      var addName = $('#add_name').val();

      console.log(addName);
	
      $('#add_name_error').text('');

      var serverId = $('#hidd_server_id').val();
      console.log(serverId);

      // Check if the creditAmount is empty
      if (addName.trim() === '') 
      {
          $('#add_name_error').text('Name is required.'); // Display validation message
          return; // Prevent form submission
      } 
      else 
      {
          $('#add_name_error').text(''); // Clear validation message
      }


      $.ajax(
      {
          url: '{{ route('add_board_name') }}',
          type: 'POST',
          data: 
          {
            server_id: serverId,
            add_name: addName,
            _token: '{{ csrf_token() }}',
          },
	        success: function (response) 
          {
              // Close the modal
              $('#add_name_modal').modal('hide');
    
              // Display the success message in the modal
              $('#successMessage').text('Name updated successfully');
              $('#successModal').modal('show');
    
            // Reload the credit page after a short delay (e.g., 1 second)
            setTimeout(function () 
            {
                $('#gsm_table').DataTable().ajax.reload();
                $('#successModal').modal('hide');
            }, 500);

          },

          error: function (error) 
          {
            // Handle errors if any
            console.error(error);
          },
      });
  });
</script>


</body>
</html>

@endsection

