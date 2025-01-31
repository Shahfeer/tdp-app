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
.bg-gray-700{
    background-color: #00ee5a !important;

}
.bg-gray-800{
    background-color: #fa8072 !important;

}
</style>
</head>
<body>

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
<div class="px-3" style="text-align:center; color: black;  height: 50px; padding-top: 8px;">
        <h2 class="text-2xl font-medium" style="font-weight: bold;">User Credit List</h2>
    </div>
               

    <!-- Campaign List Table -->    
<div class="card mt-4">
    <div class="card">
        <div class="col card-body table-responsive">
            <table class="credit_table hover stripe" id="credit_table"   style="width:100%">
                <thead>
                    <tr>
                    <th>No.</th>
		    <th>User Name</th>
                    <th>Total Credits</th>
                    <th>Used Credits</th>
		    <th>Available Credits</th>
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

<!-- Modal -->
<div class="modal" tabindex="-1" role="dialog" id="default-Modal1">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:80%;">

            <div class="modal-header">
                <h5 style="text-align:center;" class="modal-title" id="addCreditModalLabel">Add Credit</h5>
        <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  -->
            </div>
            <div class="modal-body">
		<p>User Name: <span id="userName" class="credit-value"></span></p>
                <p>Total Credit: <span id="totalCredit" class="credit-value"></span></p>
                <p>Used Credit: <span id="usedCredit" class="credit-value"></span></p>
                <p>Avail Credit: <span id="availableCredit" class="credit-value"></span></p>
                <div class="form-group">
                    <label for="creditAmount">Credit Amount:</label>
                    <input type="number" autofocus class="form-control" id="creditAmount" required min="1" >
		    <span id="creditAmountError" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-footer">
		<input type="hidden" name="hidd_user_id" id="hidd_user_id" class="form-control" value="">
          <!--      <button type="button" class="button" data-dismiss="modal">Close</button>  -->
		<button type="button" class=" md:w-full bg-gray-800 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" onclick="location.reload()">Cancel</button>
                <button type="button" class=" md:w-full bg-gray-700  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="addCreditBtn">Add Credit</button>
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

function add_credit(user_id, user_name, total_credit, used_credit, available_credit) 
{
        $("#hidd_user_id").val(user_id);
        $('#userName').text(user_name);
        $('#totalCredit').text(total_credit);
        $('#usedCredit').text(used_credit);
        $('#availableCredit').text(available_credit);
        $('#creditAmount').val(''); // Clear input field

	$('#creditAmountError').text('');

	$('#default-Modal1').modal('show');
}



document.getElementById('creditAmount').addEventListener('input', function () {
    var input = this.value;
    var maxLength = 10; // Change this to the desired maximum length

     // Check if the input is '0' and is the first character
    if (input === '0') {
        this.value = ''; // Clear the input
    }

    if (input.length > maxLength) {
        this.value = input.slice(0, maxLength); // Trim input to the maximum length
    }

    // Display an error message if the length exceeds the maximum
    var errorMessage = document.getElementById('creditAmountError');
    if (input.length > maxLength) {
        errorMessage.textContent = 'Credit Amount should be ' + maxLength + ' digits or less.';
    } else {
        errorMessage.textContent = '';
    }
});


$('#addCreditBtn').click(function () {
    var creditAmount = $('#creditAmount').val();
    var availableCredit = parseFloat($('#availableCredit').text()); // Get the current available credit
    var totalCredit = parseFloat($('#totalCredit').text()); // Get the total credit for validation
    $('#creditAmountError').text('');

    var userId = $('#hidd_user_id').val();
    console.log(userId);

 // Check if the creditAmount is empty
    if (creditAmount.trim() === '') {
        $('#creditAmountError').text('Credit Amount is required.'); // Display validation message
        return; // Prevent form submission
    } else {
        $('#creditAmountError').text(''); // Clear validation message
    }
    creditAmount = parseFloat(creditAmount); // Convert to a number

// Calculate new available credit
var newAvailableCredit = availableCredit + creditAmount;


    $.ajax({
        url: '{{ route('add_credit') }}',
        type: 'POST',
        data: {
            user_id: userId,
            credit_amount: creditAmount,
            new_available_credit: newAvailableCredit, 
            _token: '{{ csrf_token() }}',
        },
	success: function (response) {
    // Close the modal
    $('#default-Modal1').modal('hide');
    
    // Display the success message in the modal
    $('#successMessage').text('Credit updated successfully');
    $('#successModal').modal('show');
    
    // Reload the credit page after a short delay (e.g., 1 second)
    setTimeout(function () {
        location.reload();
    }, 500);
},

        error: function (error) {
            // Handle errors if any
            console.error(error);
        },
    });
});

</script>

</body>
</html>

@endsection

